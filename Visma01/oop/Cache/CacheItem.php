<?php

namespace Cache;

use Cache\Interfaces\CacheItemInterface;
use InvalidArgumentException;
use Traversable;
use function file_exists;

use DateInterval;
use FilesystemIterator;
use Generator;
use function gettype;
use function is_int;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CacheItem implements CacheItemInterface
{
    /**
     * @var string control characters for keys, reserved by PSR-16
     */
    const PSR16_RESERVED = '/\{|\}|\(|\)|\/|\\\\|\@|\:/u';

    /**
     * @var string
     */
    private $cachePath ;
    /**
     * @var int
     */
    private $defaultTtl=86400;
    /**
     * @var int
     */
    private $dirMode = 0775;
    /**
     * @var int
     */
    private $fileMode = 0664;

    /**
     * @param string $cachePath absolute root path of Cache-file folder
     * @param int $defaultTtl default time-to-live (in seconds)
     * @param int $dirMode permission mode for created dirs
     * @param int $fileMode permission mode for created files
     */
    public function __construct(string $cachePath)
    {
        //$this->defaultTtl = $defaultTtl;
        //$this->dirMode = $this->dirMode;
        //$this->fileMode = $this->fileMode;
        $this->cachePath = $cachePath;
        if (!file_exists($cachePath) && file_exists(dirname($cachePath))) {
            $this->mkdir($cachePath); // ensure that the parent path exists
            print_r("mkdir");
        }
        $path = realpath($cachePath);
        if ($path === false) {
            throw new InvalidArgumentException("Cache path does not exist: {$cachePath}");
        }
        if (!is_writable($path . DIRECTORY_SEPARATOR)) {
            throw new InvalidArgumentException("Cache path is not writable: {$cachePath}");
        }
        $this->cachePath = $path;
    }

    public function get(string $key, $default = null)
    {
        $path = $this->getPath($key);
        $expiresAt = @filemtime($path);
        if ($expiresAt === false) {
            print_r("file not found \n");
            return $default; // file not found
        }
        if ($this->getTime() >= $expiresAt) {
            @unlink($path); // file expired
            return $default;
        }
        $data = @file_get_contents($path);
        if ($data === false) {
            return $default; // race condition: file not found
        }
        if ($data === 'b:0;') {
            return false; // because we can't otherwise distinguish a FALSE return-value from unserialize()
        }
        $value = @unserialize($data);
        if ($value === false) {
            return $default; // unserialize() failed
        }
        return $value;
    }

    public function set(string $key, $value, $ttl = null):bool
    {
        $path = $this->getPath($key);
        $dir = dirname($path);
        if (!file_exists($dir)) {
            // ensure that the parent path exists:
            $this->mkdir($dir);
            print_r("filename does exist");
        } else {
            print_r("file exists");
        }
        $tempPath = $this->cachePath . DIRECTORY_SEPARATOR . uniqid('', true);
        if (is_int($ttl)) {
            $expiresAt = $this->getTime() + $ttl;
        } elseif ($ttl instanceof DateInterval) {
            $expiresAt = date_create_from_format("U", $this->getTime())->add($ttl)->getTimestamp();
        } elseif ($ttl === null) {
            $expiresAt = $this->getTime() + $this->defaultTtl;
        } else {
            throw new InvalidArgumentException("invalid TTL: " . print_r($ttl, true));
        }
        if (false === @file_put_contents($tempPath, serialize($value))) {
            return false;
        }
        if (false === @chmod($tempPath, $this->fileMode)) {
            return false;
        }
        if (@touch($tempPath, $expiresAt) && @rename($tempPath, $path)) {
            return true;
        }
        @unlink($tempPath);
        return false;
    }

    public function delete(string $key):bool
    {
        $this->validateKey($key);
        $path = $this->getPath($key);
        return !file_exists($path) || @unlink($path);
    }

    public function clear():bool
    {
        $success = true;
        $paths = $this->listPaths();
        foreach ($paths as $path) {
            if (!unlink($path)) {
                $success = false;
            }
        }
        return $success;
    }

    public function has(string $key):bool
    {
        return $this->get($key, $this) !== $this;
    }

    /**
     * Clean up expired Cache-files.
     *
     * This method is outside the scope of the PSR-16 Cache concept, and is specific to
     * this implementation, being a file-Cache.
     *
     * In scenarios with dynamic keys (such as Session IDs) you should call this method
     * periodically - for example from a scheduled daily cron-job.
     *
     * @return void
     */
    public function cleanExpired():void
    {
        $now = $this->getTime();
        $paths = $this->listPaths();
        foreach ($paths as $path) {
            if ($now > filemtime($path)) {
                @unlink($path);
            }
        }
    }

    /**
     * For a given Cache key, obtain the absolute file path
     *
     * @param string $key
     *
     * @return string absolute path to Cache-file
     *
     * @throws InvalidArgumentException if the specified key contains a character reserved by PSR-16
     */

    protected function getPath(string $key):string
    {
        $this->validateKey($key);
        $hash = hash("sha256", $key);
        print_r($hash . "\n");
        return $this->cachePath
            . DIRECTORY_SEPARATOR
            . strtoupper($hash[0])
            . DIRECTORY_SEPARATOR
            . strtoupper($hash[1])
            . DIRECTORY_SEPARATOR
            . substr($hash, 2);
    }

//    protected function getPath(string $key):string
//    {
//        $this->validateKey($key);
//        $returnPath = $this->cachePath
//                . DIRECTORY_SEPARATOR
//                . $key;
//        print_r($returnPath);
//        return $returnPath;
//    }

    /**
     * @return int current timestamp
     */
    protected function getTime():int
    {
        return time();
    }


    /**
     * @param string $key
     *
     * @throws InvalidArgumentException
     */
    protected function validateKey(string $key)
    {
        print_r("Key for validation" . $key . "\n");
        if (! is_string($key)) {
            $type = is_object($key) ? get_class($key) : gettype($key);
            throw new InvalidArgumentException("invalid key type: {$type} given");
        }
        if ($key === "") {
            throw new InvalidArgumentException("invalid key: empty string given");
        }
        if ($key === null) {
            throw new InvalidArgumentException("invalid key: null given");
        }
        if (preg_match(self::PSR16_RESERVED, $key, $match) === 1) {
            throw new InvalidArgumentException("invalid character in key: {$match[0]}");
        }
    }


    /**
     * Recursively create directories and apply permission mask
     *
     * @param string $path absolute directory path
     */
    //private function mkdir(string $this->path)
    public function mkdir($path)
    {
        $parentPath = dirname($path);
        print_r($parentPath);
        if (!file_exists($parentPath)) {
            $this->mkdir($parentPath); // recursively create parent dirs first
        }
        mkdir($path);
        chmod($path, $this->dirMode);
    }


}