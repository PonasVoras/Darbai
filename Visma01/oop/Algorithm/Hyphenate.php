<?php

namespace Algorithm;

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
        return $hyphenatedWord;
    }
}