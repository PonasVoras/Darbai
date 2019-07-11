<?php

namespace Algorithm;

use Cache\CacheItem;

class Hyphenate
{
    public $wordWithNumbers;
    private $word;

    public function __construct(string $word)
    {
        $this->word = str_replace(' ', '', $word);
        $this->findPatterns();
        $this->sortPatterns();
    }

    public function findPatterns()
    {
        new FindPatterns($this->word);
    }

    public function sortPatterns()
    {
        $sortPatterns = new SortPatterns($this->word);
        $this->wordWithNumbers = $sortPatterns->sortPatterns();
    }

    public function final(): string
    {
        $wordWithNumbers = $this->wordWithNumbers;
        //var_dump($wordWithNumbers);
        $odds = array("1", "3", "5");
        $evens = array("0", "2", "4");
        $hyphenatedWord = str_replace($odds, '-', $wordWithNumbers);
        $hyphenatedWord = str_replace($evens, '', $hyphenatedWord);

        $cache = new CacheItem("oop/Cache/CacheFiles/Patterns");

        // key 1 stores words with space
        // key 2 stores amount of numbers
        // key 3... in stores hyphenated words

        if ($cache->has(2)) {
            $cachedWords = $cache->get(1);
            $cachedWordsCount = $cache->get( 2);

            $cache->set($cachedWordsCount + 1, $hyphenatedWord);
            $cache->set(1, $cachedWords . " " . $this->word); //appends
            $cache->set(2, $cachedWordsCount +1);
        } else {
            $cache->clear();
            $cache->set(1, $this->word );
            $cache->set(2, 1);
            $cache->set(3, $hyphenatedWord);
        }



        print_r("First thing" . $cache->get(2));
        //var_dump($cache->get(1), " ");
        var_dump(explode(" ",$cache->get(1)));

        return $hyphenatedWord;
    }
}