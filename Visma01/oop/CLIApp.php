<?php

namespace Main;

use Dependencies\Container;

class CLIApp
{
    private $userInteract;

    public function __construct()
    {
        $container = new Container();
        $userInteract = $container->get('Operations\UserInteract');
        $logger = $container->get('Log\Logger');
        $this->userInteract = $userInteract;
        $logger->info('Program started');
    }

    public function main()
    {
        $this->userInteract->begin();
    }

}