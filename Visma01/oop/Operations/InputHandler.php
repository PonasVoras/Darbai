<?php

namespace Operations;

use Algorithm\Hyphenate;
use Algorithm\HyphenateParagraph;
use Cache\CacheItem;
use Log\Logger;
use Database\Database;

class InputHandler
{
    private $hyphenateParagraph;
    private $hyphenationAlgorithm;
    private $cacheItem;
    private $log;
    private $database;

    public function __construct()
    {

        $this->cacheItem = new CacheItem();
        $this->log = new Logger();
        $this->hyphenationAlgorithm = new Hyphenate();
        $this->database = new Database();
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
                print_r("Cache thing :" . $hyphenatedWord);
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
        //if (!$this->cacheItem->hasWord() && !$this->database->hasWord() ){ }
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
        $this->hyphenateParagraph = new HyphenateParagraph();
        return $this->hyphenateParagraph->hyphenateParagraph();

    }
}


/*
 *
public function wordHyphenation(string $word): string
{

    if ($this->cacheItem->hasWord($this->word))
    {
        $hyphenatedWord = $this->cacheItem->findHyphenatedWord()
    }
    else if(this->database->hasWord($this->word)
    {
        $hyphenatedWord = $this->database->findHyphenatedWord();
    }
    else
    {
        $hyphenatedWord = $this->hyphenationAlgorithm->getHyphenatedWord();
    }
    return $hyphenatedWord;
}

*/
