<?php

namespace algorithm;
require "oop/algorithm/RemoveSpaces.php";
require "oop/algorithm/RemoveDots.php";

use operations\File;

class SortPatterns
{
    public $possiblePatterns = [];
    private $word;

    public function __construct(string $word)
    {
        $this->possiblePatterns = File::readFromFile("oop/output/possible_patterns.txt");
        $this->word = $word;
        $this->sortPatterns();
    }

    public function sortPatterns(): string
    {
        $removeSpaces = new RemoveSpaces();
        $removeDots = new RemoveDots();
        $removeNumbers = new RemoveNumbers();
        $possiblePatternsModified = $removeSpaces->removeSpaces($this->possiblePatterns);
        $possiblePatternsModified = $removeDots->removeDots($possiblePatternsModified);
        $possiblePatternsModifiedNumberless = $removeNumbers->removeNumbers($possiblePatternsModified);
        $wordNumbersSplit = array_fill(0, strlen($this->word), "0");
        $wordSplit = str_split(trim($this->word, "\n"));
        $j = 0;

        foreach ($possiblePatternsModified as $key => $value) {
            $patternNr = $key;
            $patternPlace = strrpos($this->word, $possiblePatternsModifiedNumberless[$patternNr]);
            $possiblePattern = $possiblePatternsModified[$patternNr];
            $possiblePatternSplit = str_split($possiblePattern);

            $wasNumber = 0;
            foreach ($possiblePatternSplit as $key => $value) {
                if (is_numeric($value) && $patternPlace !== 0 && $patternPlace < strlen($this->word) - 2) {
                    if ($new_value = $wordNumbersSplit[$key + $patternPlace - $wasNumber] < $value) {
                        $wordNumbersSplit[$key + $patternPlace - $wasNumber] = $value;
                    }
                    $wasNumber++;

                }
            }
        }

        // Sujungia raides su turimu skaicius masyvu
        while ($j++ < (strlen($this->word) - 2)) {
            $wordSplit[$j] = $wordNumbersSplit[$j] . $wordSplit[$j];
        }

        $result = implode('', $wordSplit) . "0";
        return $result;
    }
}