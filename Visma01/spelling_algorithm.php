<?php
include "get_values.php";

function remove_numbers($letters){
    $no_numbers = preg_replace('/\d/', '', $letters);
    return ($no_numbers);
}

$no_numbers_array = array_map('remove_numbers', $values);
$dotted_array = [];
$nonditted_array = [];

function array_split($no_numbers_array){
    global $dotted_array;
    global $nonditted_array;
    foreach ($no_numbers_array as $value){
        if (strrpos($value, ".") === false){
           array_push($nonditted_array, $value);
        } else{
            array_push($dotted_array, $value);
        }
    }
    print_r("Total length" . count() . "Length of non dotted array:" . count($nonditted_array) . "\r\n" . "and another one" . count($dotted_array));
}


//Array with no numbers
//print_r($no_numbers_array);

//Compare it with string input

$test_string = 'mistranslate';
$search_values = [];
$result = "";

function compared_results($test_string, $array, $search_values){
    $reversed_test_string = strrev($test_string);
    $result = substr($reversed_test_string, -1);
    foreach ($array as $value) {
        if (strpos($value, $result)!== false){
            array_push($search_values, $value);
        }
    }

    print_r($search_values);
}



//$search_values = array_map('compared_results', $no_numbers_array);

array_split($no_numbers_array);
print_r(count($nonditted_array));

//compared_results($test_string, $no_numbers_array, $search_values);



