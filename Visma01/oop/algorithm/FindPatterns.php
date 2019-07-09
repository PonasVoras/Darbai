<?php

namespace algorithm;

require "oop/algorithm/RemoveNumbers.php";

use operations\File;

class FindPatterns{
    public $allPatterns;
    public $allPatternsNumberless;
    private $word;
    private $possiblePatterns = [];

    public function __construct(string $word){
        $this->word = $word;
        $this->allPatterns = File::ReadFromFile("oop/data/data.txt");
        $removeNumbers = new RemoveNumbers();
        $this->allPatternsNumberless = $removeNumbers->removeNumbers($this->allPatterns); // nice array with no numbers, trimmed
        $this->possiblePatterns();
    }

    private function possiblePatterns(){
        $first_rev = substr($this->word, strlen($this->word)-2,1);
        $first = substr($this->word, 0,1);
        foreach ($this->allPatternsNumberless as $key =>$value){
            $front_case = strrpos($value,"." . $first);
            $back_case = (strrpos(strrev($value), "." . $first_rev ));
            if($front_case === 0 ){
                $i = 0;
                while ($i++ < 5){
                    $search_word = ("." . substr($this->word, 0, $i));
                    if ($search_word == $value){
                        array_push($this->possiblePatterns, $this->allPatterns[$key]);
                    }

                }
            }
            if (($back_case === false) && ($front_case === false)) {
                $i =0;
                while($i++ < 5){
                    $j = -1;
                    while ($j++ < 8) {
                        $search_word = (substr($this->word, $j, $i));
                        if ($search_word == $value){
                            array_push($this->possiblePatterns, $this->allPatterns[$key]);
                        }
                    }
                }
            }
            if($back_case === 0){
                //array_push($find_back_array, $value);
                //array_push($find_back_array_key, $key);
                $i = 0;
                while ($i++ < 5){
                    $search_word = ("." . substr(trim(strrev($this->word), "\n"), 0, $i));
                    if ($search_word == strrev($value)){
                        array_push($this->possiblePatterns, $this->allPatterns[$key]);
                    }
                }
            }
        }
        $this->finalPatternArray();
    }

    public function finalPatternArray() {
        if(File::WriteToFile("oop/output/possible_patterns.txt", $this->possiblePatterns)) {
            //print_r($this->possiblePatterns);
        } else {
            //print_r("Final pattern file is not well");
        }
    }
}


