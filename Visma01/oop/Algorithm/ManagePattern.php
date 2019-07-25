<?php

namespace Algorithm;
class ManagePattern
{
    private $findPattern;
    private $sortPattern;

    public function __construct(FindPattern $findPattern,
                                SortPattern $sortPattern)
    {
        $this->findPattern = $findPattern;
        $this->sortPattern = $sortPattern;
    }

    private function makeWordWithNumbers(string $word): string
    {
        $this->findPattern->setWord($word);
        $this->findPattern->possiblePattern();
        $this->sortPattern->setWord($word);
        $wordWithNumbers = $this->sortPattern->sortPattern();
        return $wordWithNumbers;
    }

    public function getWordWithNumbers(string $word): string
    {
        return $this->makeWordWithNumbers($word);
    }
}