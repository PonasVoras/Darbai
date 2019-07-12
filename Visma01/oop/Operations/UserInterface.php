<?php


namespace Operations;

use Cache\CacheItem;
use Log\Logger;

class UserInterface
{
    public function userInterfaceCache(){


        //UI setup
        echo "Hyphenation\n";
        echo "To clear cache or not to clear cache ? -y/-n\n";
        $handle = fopen("php://stdin", "r");
        $clearCache = fgets($handle);
        switch (trim($clearCache)){
            case '-y':
                $cache = new CacheItem("oop/Cache/CacheFiles/Patterns");
                $cache->clear();
                $this->userInterfaceHyphenate();
                break;
            case '-n':
                $this->userInterfaceHyphenate();
                break;
            default:
                echo "Welp... wrong input\n";
                break;
        }

    }

    public function userInterfaceHyphenate(){
        $logger = new Logger();
        echo "What would you like to hyphenate (-w/-p) :";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        switch (trim($line)) {
            case '-w':
                echo "Word for hyphenation Algorithm: ";
                $handle = fopen("php://stdin", "r");
                $word = fgets($handle);

                $executionTime = new ExecutionCalculator();
                $executionTime->start();
                $hyphenatedWord = InputHandler::wordHyphenation($word);
                Output::outputToCli($hyphenatedWord);
                $executionTime->end();
                echo "\nExecution time : " . $executionTime->executionTime();
                $logger->info("Hyphenation successful :" . $hyphenatedWord . " finished in : " . $executionTime->executionTime());
                exit;
            case '-p':
                echo "Filename with paragraphs (must be inside Data/paragraph.txt directory) press Enter to hyphenate";
                $handle = fopen("php://stdin", "r");
                fgets($handle);
                $hyphenatedParagraph = InputHandler::paragraphHyphenation();
                $outputFile = 'oop/Output/hyphenatedParagraph.txt';
                Output::outputToFile($outputFile, $hyphenatedParagraph);
                exit;
            case '':
                echo "Wrong input. Aborting.\n";
        }
        fclose($handle);
    }
}