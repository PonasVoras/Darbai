<?php

namespace Algorithm;

use Algorithm\Utils\Remove;
use Cache\CacheItem;
use Operations\File;

class SortPatterns
{
    public $possiblePatterns = [];
    private $word;

    public function __construct(string $word)
    {
        $this->possiblePatterns = File::readFromFile("oop/Output/possible_patterns.txt");
        $this->word = $word;
        $this->sortPatterns();
    }

    public function sortPatterns(): string
    {
        $remove = new Remove();
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

        $cache = new CacheItem("oop/Cache/CacheFiles/Patterns");
        $cache->set(2, $result);

        return $result;
    }
}