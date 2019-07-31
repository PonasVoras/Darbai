<?php


namespace Operations;

use Cache\CacheItem;
use Database\Database;
use Log\Logger;
use Operations\Interfaces\HyphenationSourceInterface;

class HyphenationProxy implements HyphenationSourceInterface
{
    private $hyphenationPrimary;
    private $database;
    private $cacheItem;

    public function __construct(HyphenationPrimary $hyphenationPrimary)
    {
        $this->hyphenationPrimary = $hyphenationPrimary;
        $this->database = new Database();
        $this->cacheItem = new CacheItem();
    }

    public function findHyphenatedWord(string $word): string
    {
        $hyphenatedWord = $this->cacheItem->findHyphenatedWord($word);
        if ($hyphenatedWord == "") {
            $hyphenatedWord = $this->database->findHyphenatedWord($word);
        }
        if ($hyphenatedWord == "") {
            $hyphenatedWord = $this->hyphenationPrimary->findHyphenatedWord($word);
        }
        return $hyphenatedWord;
    }
}