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

    public function findHyphenatedWord(string $word): string
    {
        $hyphenatedWord = $this->hyphenationAlgorithm->getHyphenatedWord($word);
        return $hyphenatedWord;
    }
}