<?php

namespace index;

require "operations/Read_data.php";

use operations\Read_data;


class Index{
    function __construct(){
        echo __CLASS__.' has been initiated';
    }

    public static function main(){
        //UI setup
        echo "Hyphenation\n";
        echo "What would you like to hyphenate (-w/-p) :";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);

        //obj instantiation
        $readData = new Read_data();

        //variables for functions
        $readFile = $readData->read_data("./oop/data/data.txt");

        switch (trim($line)){
            case '-w':
                echo "Word for hyphenation algorithm: ";
                $handle = fopen ("php://stdin","r");
                $line = fgets($handle);
                echo $line . "Later\n";
                exit;
            case '-p':
                echo "Filename with paragraph (must be inside data/ directory): ";
                $handle = fopen ("php://stdin","r");
                $line = fgets($handle);
                echo $line . "Later\n";
                exit;

            case '':
                echo "File was read, it has : ";
                echo var_dump($readFile);
                echo "values";
                exit;
            default :
                echo "\n";
                echo "Wrong input. Aborting.\n";
        }
        fclose($handle);

    }

}