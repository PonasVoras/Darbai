<?php

namespace API\Model;


use Database\Database;

class WordModel
{
    //DB stuff
    private $table = 'words';
    private $pdo;

    //Post properties
    public $word; // is being set by wordController
    public $hyphenatedWord; // is being set by wordController
    public $id; // is being set by wordController

    public function __construct(){
        $this->pdo = new Database();
    }

    public function getWordByID(string $id){
        $sql = "SELECT ";
    }
    public function getWordColumnByID(){

    }
    public function updateWordByID(){

    }
    public function deleteWordByID(){

    }
}