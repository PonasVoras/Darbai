<?php

namespace operations;

require "oop/operations/File_actions.php";

use operations\File;

class Output{

    public function outputToCli(string $hyphenatedWord){
        print_r($hyphenatedWord);
    }

    public function outputToFile(string $fileName, array $hyphenatedParagraph) {
        $file_operation = File::WriteToFile($fileName, $hyphenatedParagraph);
        if ($file_operation !== 0 ) {
            echo "Hyphenated paragraph can be found in hyphenatedParagraph.txt it weights :" . $file_operation . " bytes \n";
        }
        else {
            echo "It should work next time";
        }
    }

}