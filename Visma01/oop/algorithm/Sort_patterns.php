<?php

namespace algorithm;
require "oop/algorithm/Remove_spaces.php";
require "oop/algorithm/Remove_dots.php";

use operations\File;

class Sort_patterns {
    public $possiblePatterns = [];
    public $allPatternsNumberless = [];
    private $word;

    private $wordWithNumbers = 'm2i0s1t';

    public function __construct(string $word){
        $this->possiblePatterns = File::ReadFromFile("oop/output/possible_patterns.txt");
        $this->word = $word;
        $this->sort_patterns();
    }

    private function sort_patterns(){
        $removeSpaces = new RemoveSpaces();
        $removeDots = new RemoveDots();
        $possiblePatternsModified = $removeSpaces->removeSpaces($this->possiblePatterns);
        $possiblePatternsModified = $removeDots->removeDots($possiblePatternsModified);
        $wordNumbersSplit = [];
        $wordSplit = str_split($this->word);
        $i = 0;
        $j = 0;

        //possiblePatternsModified, patterns with no dots and spaces.

        while ($i++ < strlen($this->word)){
            $wordNumbersSplit[$i] = "0";
        }

        foreach ($possiblePatternsModified as $key => $value){
            $patternNr = $key;
            $patternPlace = strrpos($this->word, $possiblePatternsModified[$patternNr]); //neranda, nes taskai maiso
            $possiblePattern = $possiblePatternsModified[$patternNr];
            $possiblePatternSplit = str_split($possiblePattern);

            $wasNumber = 0;
            foreach ($possiblePatternSplit as $key => $value){
                if (is_numeric($value)) {
                    $new_value = $wordNumbersSplit[$key + $patternPlace - $wasNumber];
                    if ($new_value < $value) {
                        $new_value = $value;
                    }
                    $wasNumber++;

                }
            }


        }

        // Sujungia raides su turimu skaicius masyvu
        while ($j++ < strlen($this->word)-1){
            $wordSplit[$j] = $wordNumbersSplit[$j] . $wordSplit[$j] ;
        }
        $result = implode('',$wordSplit) . "0";

        $wordWithNumbers = $result;
        $this->wordWithNumbers = $wordWithNumbers;
    }

    public function finalWordWithNumbers():string {
        return $this->wordWithNumbers;
    }

}