<?php

namespace Main;

use Log\Logger;
use Operations\UserInteract;

class CLIApp
{
    private $userInteract;

    public function __construct()
    {
        $logger = new Logger();
        $this->userInteract = new UserInteract();
        $logger->info('Program started');
    }

    public function main()
    {
        //User Interfaces
        $this->userInteract->begin();
    }

}