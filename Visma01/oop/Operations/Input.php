<?php

namespace Operations;

use Algorithm\Hyphenate;
use Algorithm\HyphenateParagraph;
use Cache\CacheItem;

class Input
{
    public static function wordHyphenation(string $word): string
    {
        $cache = new CacheItem("oop/Cache/CacheFiles/Patterns");
        print_r($cache->get(1));
        //print_r($cache->mkdir());
        $hyphenationAlgorithm = new Hyphenate($word);
        $hyphenatedWord = $hyphenationAlgorithm->final();
        return $hyphenatedWord;
    }

    public static function paragraphHyphenation(): array
    {
        $hyphenatedParagraph = new HyphenateParagraph();
        return $hyphenatedParagraph->final();
    }
}