<?php

namespace Operations;

class Output
{
    //TODO rewrite, add interface and all
    public function outputToCli(string $hyphenatedWord)
    {
        print_r("\n Thy hyphenated word : " . $hyphenatedWord);
    }

    public function outputToFile(string $fileName, array $hyphenatedParagraph)
    {
        $fileOperation = File::writeToFile($fileName, $hyphenatedParagraph);
        if ($fileOperation !== 0) {
            echo "Hyphenated paragraph/sentence can be found in hyphenatedParagraph.txt it weights :" . $fileOperation . " bytes \n";
        } else {
            echo "It should work next time";
        }
    }

}