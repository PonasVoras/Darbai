<?php


namespace API;

include "View/MainView.php";

use API\Controller\WordController;
use API\Model\WordModel;
use Database\Database;


class Index
{
    private $wordModel;
    private $wordController;

    public function __construct()
    {
       $this->wordModel = new WordModel();
        echo $_GET["request_name"];
    }
}
