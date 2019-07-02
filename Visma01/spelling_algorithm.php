<?php
include "get_values.php";

//Arrays of purified ones, according to dot possition
$find_front_array = [];
$find_front_array_key = [];
$find_middle_array = [];
$find_middle_array_key = [];
$find_back_array = [];
$find_back_array_key = [];

$sort_back_array = [];
$sort_front_array = [];
$sort_middle_array = [];




function remove_numbers($letters){
    $no_numbers = preg_replace('/\d/', '', $letters);
    return ($no_numbers);
}


function sort_by_dot(){
    global $no_numbers_array;
    global $find_back_array;
    global $find_back_array_key;
    global $find_front_array;
    global $find_front_array_key;
    global $find_middle_array;
    global $find_middle_array_key;
    //string front variable
    //string back variable

    foreach ($no_numbers_array as $key => $value){
        if ((strrpos($value, "e." ) === false) && (strrpos(strrev($value), "m." ) === false)) {
            array_push($find_middle_array, $value);
            array_push($find_middle_array_key, $key);
            //array_push($find_middle_array[0], $value);
        }

        if((strrpos($value, "e." ) == true)){
            array_push($find_back_array, $value);
            array_push($find_back_array_key, $key);
        }

        if((strrpos(strrev($value), "m." ) == true)){
            array_push($find_front_array, $value);
            array_push($find_front_array_key, $key);
        }
    }
}


function find_front() {
    global $test_string;
    global $find_front_array;
    global $find_front_array_key;
    global $sort_front_array;
    $i =0;

    $longest_find_map = array_map('strlen', $find_front_array);
    $longest_find = max($longest_find_map) -1; //6
    $find_front_array_stripped = preg_replace('/\s/', '', $find_front_array); //tarpu naikinimas

    while($i++ < $longest_find){
        $search_word = ("." . substr($test_string, 0, $i));
        echo "Itteration results for " . $search_word . " are : ". $i . " ->>>" . "\n";
        if(in_array($search_word, $find_front_array_stripped)){
            $key = array_search($search_word, $find_front_array_stripped);
            array_push($sort_front_array, $find_front_array_key[$key]);
        }
    }
    print_r($sort_front_array);
    print_r("Front array has : " . count($find_front_array_key) . "\n");
}


function find_middle(){
    global $test_string;
    global $find_middle_array;
    global $find_middle_array_key;
    global $sort_middle_array;
    $i =0;

    array_map('strlen', $find_middle_array);
    $longest_find = 5; //6
    $find_middle_array_stripped = preg_replace('/\s/', '', $find_middle_array); //tarpu naikinimas


    while($i++ < $longest_find){
        $search_word = (substr($test_string, 1, $i));
        echo "Itteration results for " . $search_word . " are : ". $i . " ->>>" . "\n";
        if(in_array($search_word, $find_middle_array_stripped)){
            $key = array_search($search_word, $find_middle_array_stripped);
            //array_push($sort_front_array, $find_middle_array_key[$key]);
            echo $search_word;
        }
    }

    print_r("Middle array has : " . count($find_middle_array_key) . "\n");
    //print_r("Middle array has : " . count($find_middle_array) . "\n");
}


function find_back() {
    global $test_string;
    global $find_back_array;
    global $find_back_array_key;
    global $sort_back_array;
    print_r("Back array has : " . count($find_back_array_key) . "\n");

}


function sort_front(){
    // ima po du pirmus, tikrina nuo devyniu iki vieno ar yra, ideda rasta.
    // patikrina ar visi turi tarp raidziu po skaiciu, jei ne iterpia 0
    // nukerpa  pirmas 2 raides ir taska ir kelia i find_middle[0]
    // ikelia turima i hyphenate_array atvaizduoja jau turima masyva : .m
    // parodo ka sukele i find_middle[0]
}

function sort_middle(){
    // iesko ar yra nuo 9 iki mazesniu, pushina i hyphenate masyva
    // patikrina ar visi turi skaiciu prie raides, jei ne, prideda issimtis galui
    //
    // nukerpa visu pirmas
}


function hyphenate(){

}


//Calling functions

$test_string = "mistranslate";

print_r("_____________________________________________"  . "\n");
$no_numbers_array = array_map('remove_numbers', $values);
sort_by_dot();
//find_front();
find_middle();
find_back();
print_r("_____________________________________________");




