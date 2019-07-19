<?php

namespace API\Model;


use Database\Database;
use PDO;

class Model
{
    //DB stuff
    private $table = 'words';
    private $pdo;

    //Post properties
    public $word; // is being set by wordController
    public $hyphenatedWord; // is being set by wordController
    public $id; // is being set by wordController

    public function __construct(){
        $this->database = new Database();
        $this->pdo = $this->database->pdo;
    }

    public function getWordByID(string $id) {
        $sql = "SELECT words. word FROM words WHERE word_id = :word_id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word_id', $id);
        $this->database->executeQuery($sql, 'GET method executing word retrieval');
        $hyphenatedWord = $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function getWordColumnByID(){

    }
    public function updateWordByID(){

    }
    public function deleteWordByID(){

    }
}