<?php

namespace Main;

use Log\Logger;
use Config\Config;

use Operations\UserInterface;

class Main
{
    public function __construct()
    {
        // Logger config
        $config = new Config();
        $logger = new Logger();
        $config->applyLoggerConfig($logger); //setts true to Log to file, and gives the logger object
        $logger->info('Program started');
    }

    public function main()
    {
        //User interface
        $userInterface = new UserInterface();
        $userInterface->userInterface();
    }

}