<?php

namespace Cache;

use Cache\Interfaces\CacheItemInterface;
use DateInterval;
use FilesystemIterator;
use Generator;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function file_exists;
use function gettype;
use function is_int;

class CacheItem implements CacheItemInterface
{
    /**
     * @var string control characters for keys, reserved by PSR-16
     */
    const PSR16_RESERVED = '/\{|\}|\(|\)|\/|\\\\|\@|\:/u';

    /**
     * @var string
     */
    private $cachePath;
    /**
     * @var int
     */
    private $defaultTtl = 86400;
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
    public function __construct()
    {
        $this->cachePath = "oop/Cache/CacheFiles/Patterns";
        $cachePath = $this->cachePath;
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
            //print_r("file not found \n");
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

    public function set(string $key, $value, $ttl = null): bool
    {
        $path = $this->getPath($key);
        $dir = dirname($path);
        if (!is_dir($dir)) {
            // ensure that the parent path exists:
            $this->mkdir($dir);
            //print_r("filename does exist");
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

    public function delete(string $key): bool
    {
        $this->validateKey($key);
        $path = $this->getPath($key);
        return !file_exists($path) || @unlink($path);
    }

    /**
     * @return Generator|string[]
     */
    protected function listPaths()
    {
        $iterator = new RecursiveDirectoryIterator(
            $this->cachePath,
            FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS
        );
        $iterator = new RecursiveIteratorIterator($iterator);
        foreach ($iterator as $path) {
            if (is_dir($path)) {
                continue; // ignore directories
            }
            yield $path;
        }
    }

    /**
     * Clean up expired cache-files.
     *
     * This method is outside the scope of the PSR-16 cache concept, and is specific to
     * this implementation, being a file-cache.
     *
     * In scenarios with dynamic keys (such as Session IDs) you should call this method
     * periodically - for example from a scheduled daily cron-job.
     *
     * @return void
     */
    public function cleanExpired()
    {
        $now = $this->getTime();
        $paths = $this->listPaths();
        foreach ($paths as $path) {
            if ($now > filemtime($path)) {
                @unlink($path);
            }
        }
    }

    public function clear(): bool
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

    public function saveHyphenatedWordInCache(string $hyphenatedWord, string $word)
    {
        // key 1 stores words with space
        // key 2 stores amount of numbers
        // key 3... in stores hyphenated words
        if ($this->has(2)) {
            $cachedWords = $this->get(1);
            $cachedWordsCount = $this->get(2);
            $this->set($cachedWordsCount + 3, $hyphenatedWord); // problema buvo indeksas...
            $this->set(1, $cachedWords . " " . $word); //appends
            $this->set(2, $cachedWordsCount + 1);
        } else {
            $this->clear();
            $this->set(1, $word);
            $this->set(2, 1);
            $this->set(3, $hyphenatedWord);
        }

    }

    public function has(string $key): bool
    {
        return $this->get($key, $this) !== $this;
    }

    public function hasWord(string $word): bool
    {
        // TODO find word
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

    protected function getPath(string $key): string
    {
        $this->validateKey($key);
        $hash = hash("sha256", $key);
        return $this->cachePath
            . DIRECTORY_SEPARATOR
            . strtoupper($hash[0])
            . DIRECTORY_SEPARATOR
            . strtoupper($hash[1])
            . DIRECTORY_SEPARATOR
            . substr($hash, 2);
    }

    /**
     * @return int current timestamp
     */
    protected function getTime(): int
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
        //print_r("Key for validation" . $key . "\n");
        if (!is_string($key)) {
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
    private function mkdir($path)
    {
        $parentPath = dirname($path);
        if (!file_exists($parentPath)) {
            $this->mkdir($parentPath); // recursively create parent dirs first
        }
        mkdir($path);
        chmod($path, $this->dirMode);
    }


}