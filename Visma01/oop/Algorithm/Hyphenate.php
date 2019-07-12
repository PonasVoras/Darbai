<?php

namespace Algorithm;

use Cache\CacheItem;


class Hyphenate
{
    public $wordWithNumbers;
    private $word;
    private $cache;
    private $managePattern;

    public function __construct()
    {
        //$this->cache = new CacheItem();
        $this->managePattern = new ManagePattern();
    }

    public function setHyphenationWord($word)
    {
        $this->word = str_replace(' ', '', $word);
        $this->managePattern->setWord($word);
    }

    public function getHyphenatedWord(): string
    {
        $this->wordWithNumbers = $this->managePattern->getWordWithNumbers();
        $wordWithNumbers = $this->wordWithNumbers;
        //var_dump($wordWithNumbers);
        $odds = array("1", "3", "5");
        $evens = array("0", "2", "4");
        $hyphenatedWord = str_replace($odds, '-', $wordWithNumbers);
        $hyphenatedWord = str_replace($evens, '', $hyphenatedWord);

        $cache = new CacheItem();
        //$this->cache->saveHyphenatedWordInCache($hyphenatedWord, $this->word);
        $cache->saveHyphenatedWordInCache($hyphenatedWord, $this->word);
        return $hyphenatedWord;
    }
}