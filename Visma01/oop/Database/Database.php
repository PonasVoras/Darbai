<?php

namespace Database;

use PDO;
use PDOException;

class Database
{
    public function __construct()
    {

    }

    public function tryConnection(){
        /* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
        try{
            $pdo = new PDO("mysql:host=localhost", "hyphenationuser" , "letsdothis");

            // Set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Print host information
            echo "Connect Successfully. Host info: " .
                $pdo->getAttribute(constant("PDO::ATTR_CONNECTION_STATUS"));
        } catch(PDOException $e){
            die("ERROR: Could not connect. " . $e->getMessage());
        }
    }

    public function import(){

    }
}