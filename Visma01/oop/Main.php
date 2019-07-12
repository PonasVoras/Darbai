<?php

namespace Main;

use Operations\ExecutionCalculator;
use Log\Logger;
use Config\Config;

use Operations\UserInterface;

class Main
{
    public function __construct()
    {
        // Config
        $config = new Config();
        $logger = new Logger();
        $config->applyLoggerConfig($logger); //setts true to Log to file, and gives the logger object
        $logger->info('Program started');
        // TODO paduoti loggerio objekta i konstruktoriu
    }

    public function main()
    {
        $userInterface = new UserInterface();
        $userInterface->userInterface();
    }

}