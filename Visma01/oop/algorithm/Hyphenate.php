<?php

namespace algorithm;

require "oop/algorithm/Find_patterns.php";
require "oop/algorithm/Find_patterns.php";

use algorithm\Sort_patterns;
use algorithm\Find_patterns;


class Hyphenate {
    private $word;
    private $wordWithNumbers;
    private $possiblePatternArray = [];

    //it is important to set wordWithNumbers to our words with numbers
    //finals return value gets printed, we don't have to touch it

    public function __construct(string $word){
        $this->word = $word;
        $this->find_patterns($this->word);
    }

    public function find_patterns(){
        $findPatterns = new Find_patterns($this->word);
        $this->possiblePatternArray = $findPatterns->finalPatternArray();
    }

    public function sort_patterns(){
        $sortPatterns = new Sort_patterns($this->word);
        $this->wordWithNumbers = $sortPatterns->
    }

    public function final():string {
        $wordWithNumbers = $this->wordWithNumbers;
        //$wordWithNumbers = "m2i0s1t4r";
        $odds = array("1", "3", "5");
        $evens = array("0", "2", "4");
        $hyphenatedWord = str_replace($odds, '-', $wordWithNumbers);
        //$hyphenatedWord = str_replace($odds, '-', sort_patterns());
        $hyphenatedWord = str_replace($evens, ' ', $hyphenatedWord);
        return $hyphenatedWord;
    }

}

/*                echo "File was read, it has : ";
                echo var_dump($readFile);
                echo "values";
                exit;
            default :
                echo "\n";*/
/*include 'algorithm.php';*/

/*print_r("_____________________________________________"  . "\n");
$start_timing = microtime(true);
hyphenate();
$end_timing = microtime(true);
print_r("\nExecution :". ($end_timing - $start_timing));
print_r("\n"."_____________________________________________");*/

/*    //obj instantiation
    $readData = new Read_data();

    //variables for functions
    $readFile = $readData->read_data("./oop/data/data.txt");*/