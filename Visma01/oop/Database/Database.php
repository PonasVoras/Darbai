<?php

namespace Database;

use Algorithm\Utils\Remove;
use Operations\File;
use PDO;
use PDOException;

class Database
{
    private $dbName = 'hyphenationdb';
    private $user = 'hyphenationuser';
    private $password = 'letsdothis';

    private $options;
    private $pdo;
    private $remove;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=" . $this->dbName, $this->user, $this->password, $this->options);
        $this->remove = new Remove();
        $this->tryConnection();
    }

    private function tryConnection()
    {
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connect Successfully. Host info: " .
                $this->pdo->getAttribute(constant("PDO::ATTR_CONNECTION_STATUS"));
        } catch (PDOException $e) {
            die("ERROR: Could not connect. " . $e->getMessage());
            //call logger
        }
    }

    public function checkIfPatternsArePresent(): bool
    {
        $sql = $this->pdo->query("SELECT 1 FROM patterns");
        $result = $sql->fetch();
        return empty($result);
    }

    public function hasWord(string $word):bool
    {
        $this->checkIfPatternsArePresent();
        // TODO find word
        // TODO
        return true;
    }

    // TODO not safe, rewrite
    public function importPatterns()
    {
        if (!empty($this->checkIfPatternsArePresent())) {
            $allPatterns = File::readFromFile("oop/Data/Data.txt");
            $allPatterns = $this->remove->removeSpaces($allPatterns);

            $sql = "INSERT INTO patterns (pattern) VALUES ";
            foreach ($allPatterns as $value) {
                $value = "('" . $value . "'),";
                $sql .= $value;
            }
            $sql = substr($sql, 0, -1);
            $sql = $this->pdo->prepare($sql);
            $this->executeQuery($sql, 'Patterns');
        } else {
            // call logger
            print_r("Database is not empty");
        }
    }


    public function saveWord(string $value):bool
    {
        // save word
        $message = "Insertion of word successful";
        $sql = "INSERT INTO words ( word ) VALUES ( :value )";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':value', $value);
        $this->executeQuery($sql, $message);
        return TRUE;
    }

    public function saveHyphenatedWord(string $value, string $word):bool
    {
        // save hyphenatedWord
        $message = "Insertion of hyphenatedWords successful";
        $sql = "UPDATE words SET hyphenatedword = :value WHERE word = :word";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':value', $value);
        $sql->bindParam(':word', $word);
        $this->executeQuery($sql, $message);
        return TRUE;
    }

    public function insertPattern(string $pattern, string $word){
        $sql = "INSERT INTO patternsToWords (word_id, pattern_id) VALUES (";
        $sql .= "(SELECT word_id FROM words WHERE word = :word ),";
        $sql .= "(SELECT pattern_id FROM patterns WHERE pattern = :pattern));";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word', $word);
        $sql->bindParam(':pattern', $pattern);
        var_dump($sql);

        $this->executeQuery($sql, $pattern);
    }

    public function getPattern(string $word){
        $sql = "SELECT patterns.pattern FROM patterns INNER JOIN patternsToWords ON patternsToWords.pattern_id = patterns.pattern_id
         AND patternsToWords.word_id = (SELECT words.word_id FROM words WHERE word = :word)";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word', $word);
        $this->executeQuery($sql, $word);

        print_r("Patterns used:");
        while ($row = $sql->fetch(PDO::FETCH_NUM)) {
            print "\n$row[0]";
        }
    }

    public function getHyphenatedWord(string $word){
        $sql = "SELECT words.hyphenatedword FROM words WHERE word = :word";
        $sql = $this->pdo->prepare($sql);
        $sql->bindParam(':word', $word);
        $this->executeQuery($sql, $word);
        $hyphenatedWord = $sql->fetch(PDO::FETCH_ASSOC);
        print_r($hyphenatedWord['hyphenatedword']);
    }

    private function executeQuery(object $sql, string $value= null){
        try {
            $sql->execute();
            echo "Value : " . $value . " inserted successfully. \n";
        } catch (PDOException $e) {
            //call logger
            die("ERROR: Could not able to execute " . $e->getMessage());
        }
    }

    public function truncateTable(string $table)
    {
        $message = "Truncation of " . $table . " was successful";
        $sql = "SET FOREIGN_KEY_CHECKS=0";
        $sql = $this->pdo->prepare($sql);
        $this->executeQuery($sql);
        $sql = "TRUNCATE TABLE " . $table;
        $sql = $this->pdo->prepare($sql);
        $this->executeQuery($sql, $message);
        $sql = "SET FOREIGN_KEY_CHECKS=1";
        $sql = $this->pdo->prepare($sql);
        $this->executeQuery($sql);
    }
}