<?php

namespace operations;

class Read_data{

    function __construct(){
        echo __CLASS__. 'has been initiated';
    }

    public function read_data(string $file_name): array {
        return file($file_name);
    }
}