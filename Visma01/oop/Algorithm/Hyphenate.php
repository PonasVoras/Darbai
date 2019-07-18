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

    public function __construct()
    {
        $this->cache = new CacheItem();
        $this->managePattern = new ManagePattern();
        $this->database = new Database();
    }

    public function getHyphenatedWord(string $word): string
    {
        $word = str_replace(' ', '', $word);
        $this->database->saveWord($word);
        $this->managePattern->setWord($word);
        $this->wordWithNumbers = $this->managePattern->getWordWithNumbers();
        $wordWithNumbers = $this->wordWithNumbers;
        //var_dump($wordWithNumbers);
        $odds = array("1", "3", "5");
        $evens = array("0", "2", "4");
        $hyphenatedWord = str_replace($odds, '-', $wordWithNumbers);
        $hyphenatedWord = str_replace($evens, '', $hyphenatedWord);
        $this->cache->saveHyphenatedWord($hyphenatedWord, $word);
        $this->database->saveHyphenatedWord($hyphenatedWord, $word);
        return $hyphenatedWord;
    }
}