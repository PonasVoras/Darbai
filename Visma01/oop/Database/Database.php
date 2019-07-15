<?php

namespace Database;

use PDO;
use PDOException;

class Database
{
    private $dbName = 'hyphenationdb';
    private $user = 'hyphenationuser';
    private $password = 'letsdothis';
    private $options;
    private $pdo;


    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=" . $this->dbName, $this->user , $this->password, $this->options);
    }

    private function tryConnection(){
        /* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
        try{
            // Set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Print host information
            echo "Connect Successfully. Host info: " .
                $this->pdo->getAttribute(constant("PDO::ATTR_CONNECTION_STATUS"));
        } catch(PDOException $e){
            die("ERROR: Could not connect. " . $e->getMessage());
            //call logger
        }
    }

    private function checkIfPatternsArePresent():bool{
        $sql  = $this->pdo->query("SELECT 1 FROM patterns");
        $result = $sql->fetch();
        return empty($result);
    }

    public function importPatterns(){
        $this->tryConnection();
        $this->checkIfPatternsArePresent();

    }


}