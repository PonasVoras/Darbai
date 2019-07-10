<?php

namespace cache;

use cache\interfaces\CacheItemInterface;
use InvalidArgumentException;
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
        $expires_at = @filemtime($path);
        if ($expires_at === false) {
            return $default; // file not found
        }
        if ($this->getTime() >= $expires_at) {
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
        if (! file_exists($dir)) {
            // ensure that the parent path exists:
            $this->mkdir($dir);
        }
        $temp_path = $this->cache_path . DIRECTORY_SEPARATOR . uniqid('', true);
        if (is_int($ttl)) {
            $expires_at = $this->getTime() + $ttl;
        } elseif ($ttl instanceof DateInterval) {
            $expires_at = date_create_from_format("U", $this->getTime())->add($ttl)->getTimestamp();
        } elseif ($ttl === null) {
            $expires_at = $this->getTime() + $this->default_ttl;
        } else {
            throw new InvalidArgumentException("invalid TTL: " . print_r($ttl, true));
        }
        if (false === @file_put_contents($temp_path, serialize($value))) {
            return false;
        }
        if (false === @chmod($temp_path, $this->file_mode)) {
            return false;
        }
        if (@touch($temp_path, $expires_at) && @rename($temp_path, $path)) {
            return true;
        }
        @unlink($temp_path);
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
            if (! unlink($path)) {
                $success = false;
            }
        }
        return $success;
    }


    public function getMultiple($keys, $default = null)
    {
    }


    public function setMultiple($values, $ttl = null)
    {
    }


    public function deleteMultiple($keys)
    {
    }


    public function has($key)
    {
    }
}