<?php


namespace Operations;

use Cache\CacheItem;
use Database\Database;
use Operations\Interfaces\HyphenationSourceInterface;

// this one will chose from sources
class HyphenationSource implements HyphenationSourceInterface
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