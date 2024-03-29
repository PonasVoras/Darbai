<?php

namespace Algorithm;

use Operations\File;

class HyphenateParagraph
{
    private $words = [];
    private $hyphenatedWords = [];
    private $result = [];
    private $hyphenationAlgorithm;

    public function __construct(Hyphenate $hyphenate)
    {
        $this->hyphenationAlgorithm = $hyphenate;
    }

    public function extractWords()
    {
        $rawParagraph = File::readFromFile("oop/Data/paragraph.txt");
        $paragraphSplit[] = preg_split("/[\s,.]/", $rawParagraph[0]);
        $this->words = $paragraphSplit[0];
    }

    public function hyphenateParagraph(): array
    {
        $this->extractWords();
        foreach ($this->words as $key => $value) {
            if (!preg_match('/[^A-Za-z0-9]/', $value)) {
                array_push($this->hyphenatedWords, $this->hyphenationAlgorithm->getHyphenatedWord($value));
            } else {
                array_push($this->hyphenatedWords, $value);
            }
        }
        $this->result[] = implode(" ", $this->hyphenatedWords);
        return $this->result;
    }
}
