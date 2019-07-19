<?php


namespace Operations;

use Cache\CacheItem;
use Log\Logger;

class UserInteract
{
    private $logger;
    private $cache;
    private $executionTime;
    private $inputHandler;
    private $output;
    private $useDatabase = FALSE;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->cache = new CacheItem();
        $this->executionTime = new ExecutionCalculator();
        $this->output = new Output();
        $this->inputHandler = new InputHandler();
    }

    public function begin()
    {
        //UI setup
        echo "Hyphenation\n";
        $this->databaseInquiry();
        //$this->cacheInquiry();
        $this->hyphenationInput();
    }


    private function databaseInquiry()
    {
        echo "To use database or not to use database ? -y/-n\n";
        $handle = fopen("php://stdin", "r");
        $clearCache = fgets($handle);
        switch (trim($clearCache)) {
            case '':
            case '-y':
                $this->useDatabase = TRUE;
                break;
            case '-n':
                break;
            default:
                echo "Welp... wrong input\n";
                break;
        }
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
            //case '';
            case '-w':
                echo "Word for hyphenation Algorithm: ";
                $handle = fopen("php://stdin", "r");
                $word = fgets($handle);
                $word = trim($word);
                $this->executionTime->start();
                $hyphenatedWord = $this->inputHandler->wordHyphenation($word, $this->useDatabase);
                $this->output->OutputToCli($hyphenatedWord);
                $this->executionTime->end();
                echo "\nExecution time : " . $this->executionTime->executionTime();
                $this->logger->info("Hyphenation successful :" . $hyphenatedWord . " finished in : " . $this->executionTime->executionTime());
                exit;
            case '-p':
                echo "Filename with paragraphs (must be inside Data/paragraph.txt directory) press Enter to hyphenate";
                $handle = fopen("php://stdin", "r");
                fgets($handle);
                $hyphenatedParagraph = $this->inputHandler->paragraphHyphenation();
                $outputFile = 'oop/Output/hyphenatedParagraph.txt';
                $this->output->outputToFile($outputFile, $hyphenatedParagraph);
                exit;
            case '':
                echo "Wrong input. Aborting.\n";
        }
        fclose($handle);
    }
}