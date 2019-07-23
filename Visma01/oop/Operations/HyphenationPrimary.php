<?php


namespace Operations;


use Algorithm\Hyphenate;
use Operations\Interfaces\HyphenationSourceInterface;

class HyphenationPrimary implements HyphenationSourceInterface
{

    public function findHyphenatedWord(string $word): string
    {
        $hyphenationAlgorithm = new Hyphenate();
        $hyphenatedWord = $hyphenationAlgorithm->getHyphenatedWord($word);
        return $hyphenatedWord;
    }
}