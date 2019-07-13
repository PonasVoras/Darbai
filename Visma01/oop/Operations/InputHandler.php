<?php

namespace Operations;

use Algorithm\Hyphenate;
use Algorithm\HyphenateParagraph;
use Cache\CacheItem;
use Log\Logger;

class InputHandler
{
    private $hyphenateParagraph;
    private $hyphenationAlgorithm;
    private $cacheItem;
    private $log;

    public function __construct()
    {
        $this->hyphenateParagraph = new HyphenateParagraph();
        $this->cacheItem = new CacheItem();
        $this->log = new Logger();
        $this->hyphenationAlgorithm = new Hyphenate();
    }

    public function wordHyphenation(string $word): string
    {

        if ($this->cacheItem->has(1)){
            $hyphenatedWords = explode(" ",$this->cacheItem->get(1));
            //print_r($hyphenatedWords);
            $hyphenatedWordKey = array_search($word, $hyphenatedWords);
            print_r($hyphenatedWordKey);

            if ($hyphenatedWordKey !== false){
                $hyphenatedWord = $this->cacheItem->get($hyphenatedWordKey + 3);
                print_r("Database thing :" . $hyphenatedWord);
                //print_r("Found it, loaded from cache \n");
            } else{
                // TODO make it log
                print_r("No match in cache");
                $this->hyphenationAlgorithm->setHyphenationWord($word);
                //$hyphenationAlgorithm = new Hyphenate($word);
                $hyphenatedWord = $this->hyphenationAlgorithm->getHyphenatedWord();
                //$hyphenatedWord = $hyphenationAlgorithm->final();
            }

        } else {
            // TODO make it to log
            print_r("Cache empty");
            $this->hyphenationAlgorithm->setHyphenationWord($word);
            $hyphenatedWord = $this->hyphenationAlgorithm->getHyphenatedWord();
            //$hyphenationAlgorithm = new Hyphenate($word);
            //$hyphenatedWord = $hyphenationAlgorithm->final();

        }
        return $hyphenatedWord;

    }

    public function paragraphHyphenation(): array
    {
        return $this->hyphenateParagraph->hyphenateParagraph();
    }
}