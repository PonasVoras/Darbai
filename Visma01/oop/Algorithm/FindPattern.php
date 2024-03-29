<?php

namespace Algorithm;

use Algorithm\Utils\Remove;
use Database\Database;
use Operations\File;

class FindPattern
{
    public $allPatterns;
    public $allPatternsNumberless;
    protected $possiblePatterns = [];
    private $word;
    private $database;
    private $removeNumbers;

    public function __construct(Database $database, Remove $remove)
    {
        $this->database = $database;
        $this->removeNumbers = $remove;
    }

    public function setWord(string $word)
    {
        $this->word = $word;
    }

    public function possiblePattern()
    {
        $this->allPatterns = File::readFromFile("oop/Data/Data.txt");
        $this->allPatternsNumberless = $this->removeNumbers->removeNumbers($this->allPatterns); // nice array with no numbers, trimmed
        $first_rev = substr($this->word, strlen($this->word) - 2, 1);
        $first = substr($this->word, 0, 1);
        foreach ($this->allPatternsNumberless as $key => $value) {
            $front_case = strrpos($value, "." . $first);
            $back_case = (strrpos(strrev($value), "." . $first_rev));
            if ($front_case === 0) {
                $i = 0;
                while ($i++ < strlen($this->word)) {
                    $search_word = ("." . substr($this->word, 0, $i));
                    if ($search_word == $value) {
                        array_push($this->possiblePatterns, $this->allPatterns[$key]);
                        $this->database->savePattern(trim($this->allPatterns[$key]), $this->word);
                    }
                }
            }
            if (($back_case === false) && ($front_case === false)) {
                $i = 0;
                while ($i++ < strlen($this->word)) {
                    $j = -1;
                    while ($j++ < strlen($this->word)) {
                        $search_word = (substr($this->word, $j, $i));
                        if ($search_word == $value AND !in_array($this->allPatterns[$key], $this->possiblePatterns)) {
                            array_push($this->possiblePatterns, $this->allPatterns[$key]);
                            $this->database->savePattern(trim($this->allPatterns[$key]), $this->word);
                        }
                    }
                }
            }
            if ($back_case === 0) {
                $i = 0;
                while ($i++ < strlen($this->word)) {
                    $search_word = ("." . substr(trim(strrev($this->word), "\n"), 0, $i));
                    if ($search_word == strrev($value)) {
                        array_push($this->possiblePatterns, $this->allPatterns[$key]);
                        $this->database->savePattern(trim($this->allPatterns[$key]), $this->word);
                    }
                }
            }
        }
        File::writeToFile("oop/Output/possible_patterns.txt", $this->possiblePatterns);
    }
}