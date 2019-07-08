<?php

namespace algorithm;

class HyphenateParagraph {
    private $fileName;
    private $wordWithNumbers;
    //it is important to set wordWithNumbers to our words with numbers
    //finals return value gets printed, we don't have to touch it

    public function __construct(string $fileName){
        $this->fileName = $fileName;
    }

    public function sorting(){

    }

    public function final():array {
        return array('It', ' worked');
    }

}