<?php

namespace Operations;

use Algorithm\Hyphenate;
use Cache\CacheItem;
use Log\Logger;

class UserInteract
{
    private $logger;
    private $executionTime;
    private $inputHandler;
    private $output;
    private $cache;
    private $hyphenate;

    public function __construct(Logger $logger,
                                CacheItem $cacheItem,
                                ExecutionCalculator $executionCalculator,
                                Output $output,
                                Hyphenate $hyphenate,
                                InputHandler $inputHandler)
    {
        $this->logger = $logger;
        $this->cache = $cacheItem;
        $this->executionTime = $executionCalculator;
        $this->output = $output;
        $this->inputHandler = $inputHandler;
        $this->hyphenate = $hyphenate;
    }

    public function begin()
    {
        echo "Hyphenation\n";
        $this->cacheInquiry();
        $this->hyphenationInput();
    }

    private function cacheInquiry()
    {
        echo "To clear cache or not to clear cache ? -y/-n\n";
        $handle = fopen("php://stdin", "r");
        $clearCache = fgets($handle);
        switch (trim($clearCache)) {
            case '':
            case '-y':
                $this->cache->clear();
                break;
            case '-n':
                break;
            default:
                echo "Welp... wrong input\n";
                break;
        }
    }

    private function hyphenationInput()
    {
        echo "What would you like to hyphenate (-w/-p) :";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        switch (trim($line)) {
            case '-w':
                echo "Word for hyphenation Algorithm: ";
                $handle = fopen("php://stdin", "r");
                $word = fgets($handle);
                $word = trim($word);
                $this->executionTime->start();
                $hyphenatedWord = $this->inputHandler->wordHyphenation($word, $this->hyphenate);
                $this->output->OutputToCli($hyphenatedWord);
                $this->executionTime->end();
                echo "\nExecution time : " . $this->executionTime->executionTime();
                $this->logger->info("Hyphenation successful :" . $hyphenatedWord . " finished in : " . $this->executionTime->executionTime());
                exit;
            case '-p':
                echo "Filename with paragraphs (must be inside Data/paragraph.txt directory) press Enter to hyphenate";
                $handle = fopen("php://stdin", "r");
                fgets($handle);
                $hyphenatedParagraph = $this->inputHandler->paragraphHyphenation($this->hyphenate);
                $outputFile = 'oop/Output/hyphenatedParagraph.txt';
                $this->output->outputToFile($outputFile, $hyphenatedParagraph);
                exit;
            case '':
                echo "Wrong input. Aborting.\n";
        }
        fclose($handle);
    }
}