<?php

namespace Main;

use Log\Logger;

use Operations\UserInteract;

class Main
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
        //User interface
        $this->userInteract->begin();
    }

}