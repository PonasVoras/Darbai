<?php

namespace API\Model;


use Database\Database;
use PDO;

class Model
{
    //DB stuff
    private $table = 'words';
    private $pdo;
    private $view;

    //Post properties
    public $word; // is being set by wordController
    public $hyphenatedWord; // is being set by wordController
    public $id; // is being set by wordController

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->pdo;
    }

    public function postWord(string $word)
    {
        $sql = "INSERT INTO words (word) VALUES (:word)";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word', $word);
        $this->database->executeQuery($sql, 'POST method executing word array retrieval :');
    }

    public function getWordByID(string $id): string
    {
        $sql = "SELECT words. word FROM words WHERE word_id = :word_id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word_id', $id);
        $this->database->executeQuery($sql, 'GET method executing word retrieval :');
        $word = $sql->fetch(PDO::FETCH_ASSOC);
        $result = !empty($word['word']) ? $word['word'] : 404;
        return $result;
    }

    public function getWords(): array
    {
        $sql = "SELECT words. word FROM words";
        $sql = $this->pdo->prepare($sql);
        $this->database->executeQuery($sql, 'GET method executing word array retrieval :');
        $words = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $words;
    }

    public function updateWord(string $word, string $updateWord)
    {
        $sql = "UPDATE words SET word = :value WHERE word = :word";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':value', $updateWord);
        $sql->bindParam(':word', $word);
        $this->database->executeQuery($sql, 'PUT method executing word array retrieval :');
    }

    public function deleteWord(string $word)
    {
        $sql = "DELETE FROM words WHERE word = :word";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word', $word);
        $this->database->executeQuery($sql, 'PUT method executing word array retrieval :');
    }
}