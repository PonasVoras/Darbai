<?php


namespace Operations;


use Algorithm\Hyphenate;
use Operations\Interfaces\HyphenationSourceInterface;

class HyphenationPrimary implements HyphenationSourceInterface
{
    private $hyphenationAlgorithm;

    public function __construct(Hyphenate $hyphenate)
    {
        $this->hyphenationAlgorithm = $hyphenate;
    }

    private function saveInDatabase(string $word){

    }

    public function findHyphenatedWord(string $word): string
    {
        $this->saveInDatabase($word);
        $hyphenatedWord = $this->hyphenationAlgorithm->getHyphenatedWord($word);
        return $hyphenatedWord;
    }

}