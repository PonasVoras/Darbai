<?php

namespace Algorithm;

use Algorithm\Utils\Remove;
use Operations\File;

class SortPattern
{
    public $possiblePatterns = [];
    private $word;
    private $remove;

    public function __construct(Remove $remove)
    {
        $this->remove = $remove;
    }

    public function setWord(string $word)
    {
        $this->word = $word;
    }

    public function sortPattern(): string
    {
        if (empty($this->possiblePatterns)){
            $this->possiblePatterns = File::readFromFile("oop/Output/possible_patterns.txt");
        }
        $remove = $this->remove;
        $possiblePatternsModified = $remove->removeSpaces($this->possiblePatterns);
        $possiblePatternsModified = $remove->removeDots($possiblePatternsModified);
        $possiblePatternsModifiedNumberless = $remove->removeNumbers($possiblePatternsModified);
        $wordNumbersSplit = array_fill(0, strlen($this->word), "0");
        $wordSplit = str_split(trim($this->word, "\n"));
        $j = 0;
        foreach ($possiblePatternsModified as $key => $value) {
            $patternNr = $key;
            $patternPlace = strrpos($this->word, $possiblePatternsModifiedNumberless[$patternNr]);
            $possiblePattern = $possiblePatternsModified[$patternNr];
            $possiblePatternSplit = str_split($possiblePattern);
            $wasNumber = 0;
            foreach ($possiblePatternSplit as $key1 => $value1) {
                if (is_numeric($value1) && $patternPlace !== 0 && $patternPlace < strlen($this->word) - 2) {
                    if ($new_value = $wordNumbersSplit[$key1 + $patternPlace - $wasNumber] < $value1) {
                        $wordNumbersSplit[$key1 + $patternPlace - $wasNumber] = $value1;
                    }
                    $wasNumber++;
                }
            }
        }
        //Joins numbers and chars
        while ($j++ < (strlen($this->word) - 2)) {
            $wordSplit[$j] = $wordNumbersSplit[$j] . $wordSplit[$j];
        }
        $result = implode('', $wordSplit) . "0";
        return $result;
    }
}
