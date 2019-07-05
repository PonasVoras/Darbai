<?php

namespace operations;

class Output{
    private $hyphenated = '';
    function __construct(string $hyphenated){
        echo __CLASS__. 'has been initiated';
        $this->hyphenated=$hyphenated;
    }

    public function output_to_cli(){
        print_r($this->hyphenated);
    }
}