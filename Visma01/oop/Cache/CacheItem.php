<?php

namespace Cache;

use DateInterval;
use FilesystemIterator;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function file_exists;
use function gettype;
use function is_int;

class CacheItem //implements CacheItemInterface, HyphenationSourceInterface
{

    const PSR16_RESERVED = '/\{|\}|\(|\)|\/|\\\\|\@|\:/u';

    private $cachePath;

    private $defaultTtl = 86400;

    private $dirMode = 0775;

    private $fileMode = 0664;

    const RESERVED_FOR_WORDS = 1;
    const RESERVED_FOR_WORD_COUNT = 2;
    const RESERVED_OFFSET = 3;


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

    public function saveHyphenatedWord(string $hyphenatedWord, string $word)
    {
        if ($this->has(self::RESERVED_FOR_WORD_COUNT)) {
            $cachedWords = $this->get(self::RESERVED_FOR_WORDS);
            $cachedWordsCount = $this->get(self::RESERVED_FOR_WORD_COUNT);
            $this->set($cachedWordsCount + self::RESERVED_OFFSET, $hyphenatedWord);
            $this->set(self::RESERVED_FOR_WORDS, $cachedWords . " " . $word);
            $this->set(self::RESERVED_FOR_WORD_COUNT, $cachedWordsCount + self::RESERVED_FOR_WORDS);
        } else {
            $this->clear();
            $this->set(self::RESERVED_FOR_WORDS, $word);
            $this->set(self::RESERVED_FOR_WORD_COUNT, 1);
            $this->set(self::RESERVED_OFFSET, $hyphenatedWord);
        }
    }

    public function findHyphenatedWord(string $word): string
    {
        $hyphenatedWord = "";
        $hyphenatedWords = explode(" ", $this->get(1));
        if (!empty($hyphenatedWords)) {
            $hyphenatedWordKey = array_search($word, $hyphenatedWords);
            if ($hyphenatedWordKey !== false) {
                $hyphenatedWord = $this->get($hyphenatedWordKey + self::RESERVED_OFFSET);
                print_r("Cache thing :" . $hyphenatedWord);
            }
        }
        return $hyphenatedWord;
    }

    public function has(string $key): bool
    {
        return $this->get($key, $this) !== $this;
    }

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

    protected function getTime(): int
    {
        return time();
    }

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