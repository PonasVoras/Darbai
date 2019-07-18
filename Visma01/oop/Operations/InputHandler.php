<?php

namespace Operations;

use Algorithm\Hyphenate;
use Algorithm\HyphenateParagraph;
use Cache\CacheItem;
use Database\Database;
use Log\Logger;

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
            print_r("else if works");
            $hyphenatedWord = $this->hyphenationAlgorithm->getHyphenatedWord($word);
        }

        return $hyphenatedWord;
    }

    public function paragraphHyphenation(): array
    {
        $this->hyphenateParagraph = new HyphenateParagraph();
        return $this->hyphenateParagraph->hyphenateParagraph();

    }
}
