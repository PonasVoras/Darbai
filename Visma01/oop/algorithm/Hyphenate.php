<?php

namespace algorithm;

require "oop/algorithm/FindPatterns.php";
require "oop/algorithm/SortPatterns.php";

use algorithm\FindPatterns;
use algorithm\SortPatterns;


class Hyphenate {
    private $word;
    public $wordWithNumbers;

    public function __construct(string $word){
        $this->word = str_replace(' ', '', $word);
        $this->find_patterns();
        $this->sort_patterns();
    }

    public function find_patterns(){
        new FindPatterns($this->word);
    }

    public function sort_patterns(){
        $sortPatterns = new SortPatterns($this->word);
        $this->wordWithNumbers = $sortPatterns->sort_patterns();
    }

    public function final():string {
        $wordWithNumbers = $this->wordWithNumbers;
        //var_dump($wordWithNumbers);
        $odds = array("1", "3", "5");
        $evens = array("0", "2", "4");
        $hyphenatedWord = str_replace($odds, '-', $wordWithNumbers);
        $hyphenatedWord = str_replace($evens, '', $hyphenatedWord);
        return $hyphenatedWord;
    }
}