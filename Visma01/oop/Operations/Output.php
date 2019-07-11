<?php

namespace Operations;

class Output
{

    public static function outputToCli(string $hyphenatedWord)
    {
        print_r($hyphenatedWord);
    }

    public static function outputToFile(string $fileName, array $hyphenatedParagraph)
    {
        $fileOperation = File::writeToFile($fileName, $hyphenatedParagraph);
        if ($fileOperation !== 0) {
            echo "Hyphenated paragraph/sentence can be found in hyphenatedParagraph.txt it weights :" . $fileOperation . " bytes \n";
        } else {
            echo "It should work next time";
        }
    }

}