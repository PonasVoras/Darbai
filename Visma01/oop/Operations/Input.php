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
        if ($cache->has(1)){
            $hyphenatedWords = explode(" ",$cache->get(1));
            //print_r($hyphenatedWords);
            $hyphenatedWordKey = array_search($word, $hyphenatedWords);
            //print_r($hyphenatedWordKey);
            if ($hyphenatedWordKey !== false){
                $hyphenatedWord = $cache->get($hyphenatedWordKey + 1);
                //print_r("Found it, loaded from cache \n");
            } else{
                print_r("No match in cache");
                $hyphenationAlgorithm = new Hyphenate($word);
                $hyphenatedWord = $hyphenationAlgorithm->final();
            }
        } else {
            print_r("Cache empty");
            $hyphenationAlgorithm = new Hyphenate($word);
            $hyphenatedWord = $hyphenationAlgorithm->final();

        }
        return $hyphenatedWord;

    }

    public static function paragraphHyphenation(): array
    {
        $hyphenatedParagraph = new HyphenateParagraph();
        return $hyphenatedParagraph->final();
    }
}