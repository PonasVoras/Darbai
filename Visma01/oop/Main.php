<?php

namespace main;

//Files are being called from root
require "oop/operations/Input.php";
require "oop/operations/Output.php";

use operations\Input;
use operations\Output;

class Main{
    function __construct(){
        echo __CLASS__.' has been initiated';
    }

    public static function main(){
        //UI setup
        echo "Hyphenation\n";
        echo "What would you like to hyphenate (-w/-p) :";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);

        switch (trim($line)){
            case '-w':
                echo "Word for hyphenation algorithm: ";
                $handle = fopen ("php://stdin","r");
                $word = fgets($handle);
                $hyphenatedWord = Input::wordHyphenation($word);
                Output::outputToCli($hyphenatedWord);
                echo "\n It has been done";
                exit;
            case '-p':
                echo "Filename with paragraph (must be inside data/ directory): ";
                $handle = fopen ("php://stdin","r");
                $fileName = fgets($handle);
                $hyphenatedParagraph = Input::paragraphHyphenation($fileName);
                $outputFile = 'oop/output/hyphenatedParagraph.txt';
                Output::outputToFile($outputFile ,$hyphenatedParagraph);
                exit;

            case '':
                echo "Wrong input. Aborting.\n";
        }
        fclose($handle);

    }

}