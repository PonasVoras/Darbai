<?php

namespace Operations;

use Algorithm\Hyphenate;
use Algorithm\HyphenateParagraph;
use Cache\CacheItem;
use Database\Database;
use Log\Logger;
use Operations\Interfaces\HyphenationSourceInterface;


class InputHandler
{
    private $hyphenateParagraph;

    public function wordHyphenation(string $word): string
    {
//        $hyphenatedWord = $this->cacheItem->findHyphenatedWord($word);
//        if ($hyphenatedWord == "" && $useDatabase !== FALSE) {
//            $hyphenatedWord = $this->database->findHyphenatedWord($word);
//        }
//        if ($hyphenatedWord == "") {
//            $hyphenatedWord = $this->hyphenationAlgorithm->getHyphenatedWord($word);
//        }
        $hyphenationPrimary = new HyphenationPrimary();
        $proxy = new HyphenationSource($hyphenationPrimary);
        $hyphenatedWord = $proxy->findHyphenatedWord($word);
        return $hyphenatedWord;
    }

    public function paragraphHyphenation(): array
    {
        $this->hyphenateParagraph = new HyphenateParagraph();
        return $this->hyphenateParagraph->hyphenateParagraph();

    }

}


/*namespace Operations;

use Algorithm\Hyphenate;
use Algorithm\HyphenateParagraph;
use Cache\CacheItem;
use Database\Database;
use Log\Logger;
use Operations\Interfaces\HyphenationSourceInterface;


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

    public function wordHyphenation(string $word, bool $useDatabase): string
    {
        $hyphenatedWord = $this->cacheItem->findHyphenatedWord($word);
        if ($hyphenatedWord == "" && $useDatabase !== FALSE) {
            $hyphenatedWord = $this->database->findHyphenatedWord($word);
        }
        if ($hyphenatedWord == "") {
            $hyphenatedWord = $this->hyphenationAlgorithm->getHyphenatedWord($word);
        }
        return $hyphenatedWord;
    }

    public function paragraphHyphenation(): array
    {
        $this->hyphenateParagraph = new HyphenateParagraph();
        return $this->hyphenateParagraph->hyphenateParagraph();

    }

}*/