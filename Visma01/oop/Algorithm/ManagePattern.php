<?php

namespace Algorithm;
class ManagePattern
{
    private $word;
    private $findPattern;
    private $sortPattern;

    public function __construct(FindPattern $findPattern,
                                SortPattern $sortPattern)
    {
        $this->findPattern = $findPattern;
        $this->sortPattern = $sortPattern;
    }

    public function setWord($word)
    {
        $this->word = $word;
    }

    private function makeWordWithNumbers(): string
    {
        $this->findPattern->setWord($this->word);
        $this->findPattern->possiblePattern();
        $this->sortPattern->setWord($this->word);
        $wordWithNumbers = $this->sortPattern->sortPattern();
        return $wordWithNumbers;
    }

    public function getWordWithNumbers(): string
    {
        return $this->makeWordWithNumbers();
    }
}