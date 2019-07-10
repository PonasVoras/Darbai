<?php

namespace cache;

use cache\interfaces\CacheItemInterface;
use InvalidArgumentException;
use Traversable;
use function file_exists;

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
    private $defaultTtl;
    /**
     * @var int
     */
    private $dirMode;
    /**
     * @var int
     */
    private $fileMode;

    /**
     * @param string $cachePath absolute root path of cache-file folder
     * @param int $defaultTtl default time-to-live (in seconds)
     * @param int $dirMode permission mode for created dirs
     * @param int $fileMode permission mode for created files
     */
    public function __construct($cachePath, $defaultTtl, $dirMode = 0775, $fileMode = 0664)
    {
        $this->defaultTtl = $defaultTtl;
        $this->dirMode = $dirMode;
        $this->fileMode = $fileMode;
        if (!file_exists($cachePath) && file_exists(dirname($cachePath))) {
            $this->mkdir($cachePath); // ensure that the parent path exists
        }
        $path = realpath($cachePath);
        if ($path === false) {
            throw new InvalidArgumentException("cache path does not exist: {$cachePath}");
        }
        if (!is_writable($path . DIRECTORY_SEPARATOR)) {
            throw new InvalidArgumentException("cache path is not writable: {$cachePath}");
        }
        $this->cachePath = $path;
    }

    public function get($key, $default = null)
    {
        $path = $this->getPath($key);
        $expiresAt = @filemtime($path);
        if ($expiresAt === false) {
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

    public function set($key, $value, $ttl = null)
    {
        $path = $this->getPath($key);
        $dir = dirname($path);
        if (!file_exists($dir)) {
            // ensure that the parent path exists:
            $this->mkdir($dir);
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
        if (false === @chmod($tempPath, $this->file_mode)) {
            return false;
        }
        if (@touch($tempPath, $expiresAt) && @rename($tempPath, $path)) {
            return true;
        }
        @unlink($tempPath);
        return false;
    }

    public function delete($key)
    {
        $this->validateKey($key);
        $path = $this->getPath($key);
        return !file_exists($path) || @unlink($path);
    }

    public function clear()
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


    public function getMultiple($keys, $default = null)
    {
        if (!is_array($keys) && !$keys instanceof Traversable) {
            throw new InvalidArgumentException("keys must be either of type array or Traversable");
        }
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key) ?: $default;
        }
        return $values;
    }


    public function setMultiple($values, $ttl = null)
    {
        if (! is_array($values) && ! $values instanceof Traversable) {
            throw new InvalidArgumentException("keys must be either of type array or Traversable");
        }
        $ok = true;
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $key = (string) $key;
            }
            $this->validateKey($key);
            $ok = $this->set($key, $value, $ttl) && $ok;
        }
        return $ok;
    }


    public function deleteMultiple($keys)
    {
        if (! is_array($keys) && ! $keys instanceof Traversable) {
            throw new InvalidArgumentException("keys must be either of type array or Traversable");
        }
        $ok = true;
        foreach ($keys as $key) {
            $this->validateKey($key);
            $ok = $ok && $this->delete($key);
        }
        return $ok;
    }


    public function has($key)
    {
        return $this->get($key, $this) !== $this;
    }

    public function increment($key, $step = 1)
    {
        $path = $this->getPath($key);
        $dir = dirname($path);
        if (! file_exists($dir)) {
            $this->mkdir($dir); // ensure that the parent path exists
        }
        $lockPath = $dir . DIRECTORY_SEPARATOR . ".lock"; // allows max. 256 client locks at one time
        $lockHandle = fopen($lockPath, "w");
        flock($lockHandle, LOCK_EX);
        $value = $this->get($key, 0) + $step;
        $ok = $this->set($key, $value);
        flock($lockHandle, LOCK_UN);
        return $ok ? $value : false;
    }

    public function decrement($key, $step = 1)
    {
        return $this->increment($key, -$step);
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

    /**
     * For a given cache key, obtain the absolute file path
     *
     * @param string $key
     *
     * @return string absolute path to cache-file
     *
     * @throws InvalidArgumentException if the specified key contains a character reserved by PSR-16
     */
    protected function getPath($key)
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
    protected function getTime()
    {
        return time();
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
     * @param string $key
     *
     * @throws InvalidArgumentException
     */
    protected function validateKey($key)
    {
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
    private function mkdir($path)
    {
        $parent_path = dirname($path);
        if (!file_exists($parent_path)) {
            $this->mkdir($parent_path); // recursively create parent dirs first
        }
        mkdir($path);
        chmod($path, $this->dir_mode);
    }


}