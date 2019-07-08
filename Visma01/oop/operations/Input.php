<?php

namespace operations;

require "oop/algorithm/Hyphenate.php";
require "oop/algorithm/HyphenateParagraph.php";

use algorithm\Hyphenate;
use algorithm\HyphenateParagraph;

class InputChoice {
    public static function wordHyphenation(string $word):string {
        $hyphenationAlgorithm = new Hyphenate($word);
        return $hyphenationAlgorithm->final();
    }

    public static function paragraphHyphenation(string $fileName):array {
        $hyphenatedParagraph = new HyphenateParagraph($fileName);
        return $hyphenatedParagraph->final();
    }
}