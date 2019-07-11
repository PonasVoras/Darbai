<?php

namespace Main;

use Operations\ExecutionCalculator;
use Log\Logger;
use Config\Config;
use Operations\Input;
use Operations\Output;

class Main
{
    public function __construct()
    {
        $this->main();
        // TODO paduoti loggerio objekta i konstruktoriu
    }

    public function main()
    {
        // Config
        $config = new Config();
        $logger = new Logger();
        $config->applyLoggerConfig($logger); //setts true to Log to file, and gives the logger object
        $logger->info('Program started');
        //UI setup
        echo "Hyphenation\n";
        echo "What would you like to hyphenate (-w/-p) :";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);

        switch (trim($line)) {
            case '':
            //case '-w':
                echo "Word for hyphenation Algorithm: ";
                $handle = fopen("php://stdin", "r");
                $word = fgets($handle);
                $executionTime = new ExecutionCalculator();
                $executionTime->start();
                $word = 'mistranslate';
                $hyphenatedWord = Input::wordHyphenation($word);
                Output::outputToCli($hyphenatedWord);
                $executionTime->end();
                echo "\nExecution time : " . $executionTime->executionTime();
                exit;
            case '-p':
                echo "Filename with paragraphs (must be inside Data/paragraph.txt directory) press Enter to hyphenate";
                $handle = fopen("php://stdin", "r");
                fgets($handle);
                $hyphenatedParagraph = Input::paragraphHyphenation();
                $outputFile = 'oop/Output/hyphenatedParagraph.txt';
                Output::outputToFile($outputFile, $hyphenatedParagraph);
                exit;
            /*case '':
                echo "Wrong input. Aborting.\n";*/
        }
        fclose($handle);
    }

}