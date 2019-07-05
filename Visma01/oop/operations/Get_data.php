<?php

namespace operations;

class Get_data{

    public static function read_data(string $file_name): string{
        return file_get_contents($file_name);
    }

}



/*$values = file("values.txt");*/