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
    }

    private function tryConnection()
    {
        /* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
        try {
            // Set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Print host information
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

    public function hasWord(string $word)
    {
        $this->tryConnection();
        $this->checkIfPatternsArePresent();
        // TODO find word
    }

    public function importPatterns()
    {
        $this->tryConnection();
        if (!empty($this->checkIfPatternsArePresent())) {
            $allPatterns = File::readFromFile("oop/Data/Data.txt");
            $allPatterns = $this->remove->removeSpaces($allPatterns);
            $sql = "REPLACE INTO patterns (pattern) VALUES ";
            foreach ($allPatterns as $value) {
                $value = "('" . $value . "'),";
                $sql .= $value;
            }
            $sql = substr($sql, 0, -1);
            //$sql .= ";";
            try {
                $this->pdo->exec($sql);
                echo "Records inserted successfully.";
            } catch (PDOException $e) {
                die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            }
        } else {
            print_r("Database is not empty");
        }

    }

    private function erasePatterns()
    {
        $sql = "TRUNCATE TABLE patterns";
        //$this->pdo->query($sql);
        try {
            $numberOfRows = $this->pdo->exec($sql);
            echo "Records erased successfully." . $numberOfRows;
        } catch (PDOException $e) {
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
        }
    }


}