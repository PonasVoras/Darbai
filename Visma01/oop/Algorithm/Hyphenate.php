<?php

namespace Algorithm;

use Cache\CacheItem;
use Database\Database;

class Hyphenate
{
    public $wordWithNumbers;
    private $cache;
    private $managePattern;
    private $database;

    public function __construct(ManagePattern $managePattern,
                                Database $database,
                                CacheItem $cacheItem)
    {
        $this->managePattern = $managePattern;
        $this->database = $database;
        $this->cache = $cacheItem;
    }


    public function getHyphenatedWord(string $word): string
    {
        $word = str_replace(' ', '', $word);
        // TODO HandleData
        $this->database->saveWord($word);
        $this->wordWithNumbers = $this->managePattern->getWordWithNumbers($word);
        $wordWithNumbers = $this->wordWithNumbers;
        $odds = array("1", "3", "5");
        $evens = array("0", "2", "4");
        $hyphenatedWord = str_replace($odds, '-', $wordWithNumbers);
        $hyphenatedWord = str_replace($evens, '', $hyphenatedWord);
        $this->cache->saveHyphenatedWord($hyphenatedWord, $word);
        $this->database->saveHyphenatedWord($hyphenatedWord, $word);
        // $this->manageSaving->
        return $hyphenatedWord;
    }
}