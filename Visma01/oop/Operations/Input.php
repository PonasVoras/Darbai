<?php

namespace Operations;

use Algorithm\Hyphenate;
use Algorithm\HyphenateParagraph;

class Input
{
    public static function wordHyphenation(string $word): string
    {
        $hyphenationAlgorithm = new Hyphenate($word);
        return $hyphenationAlgorithm->final();
    }

    public static function paragraphHyphenation(): array
    {
        $hyphenatedParagraph = new HyphenateParagraph();
        return $hyphenatedParagraph->final();
    }
}