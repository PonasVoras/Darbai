<?php

namespace algorithm;

class Hyphenate {
    private $word;
    private $wordWithNumbers;
    //it is important to set wordWithNumbers to our words with numbers
    //finals return value gets printed, we don't have to touch it

    public function __construct(string $word){
        $this->word = $word;

    }

    public function sorting(){

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