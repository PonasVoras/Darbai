<?php

namespace API\Model;


use Database\Database;
use Database\QueryBuilder;
use Log\Logger;
use PDO;

class Model
{
    //DB stuff
    private $pdo;
    private $database;
    private $query;

    //Post properties
    public $word; // is being set by wordController
    public $hyphenatedWord; // is being set by wordController
    public $id; // is being set by wordController

    public function __construct()
    {
        $this->query = new QueryBuilder();
        $this->database = new Database();
        $this->pdo = $this->database->pdo;
    }

    public function postWord(string $word)
    {
        $sql = $this->query
            ->insert('words (word)')
            ->values('(:word)')
            ->build();
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word', $word);
        $this->database->executeQuery($sql);
    }

    public function getWordByID(string $id): string
    {
        $sql = $this->query
            ->select('word')
            ->from('words')
            ->where('word_id = :word_id')
            ->build();
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word_id', $id);
        $this->database->executeQuery($sql);
        $word = $sql->fetch(PDO::FETCH_ASSOC);
        $result = !empty($word['word']) ? $word['word'] : 404;
        return $result;
    }

    public function getWords(): array
    {
        $sql = $this->query
            ->select('words.word')
            ->from('words')
            ->build();
        $sql = $this->pdo->prepare($sql);
        $this->database->executeQuery($sql);
        $words = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $words;
    }

    public function updateWordById(string $id, string $updateWord)
    {
        $sql = $this->query
            ->update('words')
            ->set('word = :value')
            ->where('word_id = :word_id')
            ->build();

        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':value', $updateWord);
        $sql->bindParam(':word_id', $id);
        $this->database->executeQuery($sql);
    }

    public function deleteWordById(string $id)
    {
        $sql = $this->query
            ->delete('words')
            ->where('word_id = :word_id')
            ->build();
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word_id', $id);
        $this->database->executeQuery($sql);
    }
}