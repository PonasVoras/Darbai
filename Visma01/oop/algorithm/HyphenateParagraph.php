<?php

namespace algorithm;

use operations\File;

class HyphenateParagraph {
    private $words =[];
    private $hyphenatedWords = [];
    private $result =[];


    public function __construct(){
        $this->extractWords();
        $this->hyphenateParagraph();
    }

    public function extractWords() {
        $rawParagraph = File::ReadFromFile("oop/data/paragraph.txt");
        $paragraphSplit[] = preg_split("/[\s,.]/", $rawParagraph[0]);
        $this->words = $paragraphSplit[0];
    }

    public function hyphenateParagraph() {
        foreach ($this->words as $key => $value){
            if (!preg_match('/[^A-Za-z0-9]/', $value)) {
                $hyphenationAlgorithm = new Hyphenate($value);
                array_push($this->hyphenatedWords, $hyphenationAlgorithm->final());
            } else {
                array_push($this->hyphenatedWords, $value);
            }
        }
        $this->result[] = implode(" ", $this->hyphenatedWords);
    }

    public function final():array {
        return $this->result;
    }

}