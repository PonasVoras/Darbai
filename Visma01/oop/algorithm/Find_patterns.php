<?php

namespace algorithm;

require "oop/operations/File_actions.php";
require  "oop/algorithm/Remove_numbers.php";

use operations\File;
use algorithm\RemoveNumbers;

class Find_patterns{
    public $allPatterns;
    public $allPatternsNumberless;
    private $word;
    private $possiblePatterns = [];

    public function __construct(string $word){
        $this->allPatterns = File::ReadFromFile("oop/data/data.txt");
        $removeNumbers = new RemoveNumbers();
        $this->allPatternsNumberless = array_map($removeNumbers->removeNumbers(), $this->allPatterns);
    }

    public function finalPatternArray(array $frontPattern, array $middlePattern, array $backPattern):array {

    }


}

/*
 * function sort_by_dot(){
    global $values;
    $find_back_array = [];
    $find_back_array_key = [];
    $find_front_array = [];
    $find_front_array_key = [];
    $find_middle_array = [];
    $find_middle_array_key = [];

    $no_numbers_array = array_map('remove_numbers', $values);

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

    return[
        'back_array'=> $find_back_array,
        'back_key_array' => $find_back_array_key,

        'middle_array' => $find_middle_array,
        'middle_key_array' => $find_middle_array_key,

        'front_array' => $find_front_array,
        'front_key_array' => $find_front_array_key,
    ];
}
 */


//find front function
/*
 * function find_front() {
    $front_array = [];
    $i =0;

    $longest_find_map = array_map('strlen', sort_by_dot()['front_array']);
    $longest_find = max($longest_find_map) -1; //6
    $find_front_array_stripped = preg_replace('/\s/', '', sort_by_dot()['front_array']); //tarpu naikinimas

    while($i++ < $longest_find){
        $search_word = ("." . substr(user_input(), 0, $i));
        //echo "Itteration results for " . $search_word . " are : ". $i . " ->>>" . "\n";
        if(in_array($search_word, $find_front_array_stripped)){
            $key = array_search($search_word, $find_front_array_stripped);
            array_push($front_array, hyphens()[sort_by_dot()['front_key_array'][$key]]);
        }
    }
    return $front_array;
}
 */

//find middle function
/*
 * function find_middle(){
    $middle_array = [];
    $i =-1;

    $longest_find = 5; //6
    $find_middle_array_stripped = preg_replace('/\s/', '', sort_by_dot()['middle_array']); //tarpu naikinimas
    //print_r($find_middle_array);
    //print_r($find_middle_array_stripped);
    while($i++ < $longest_find){
        $j = -1;

        while ($j++ < 8) {
            $search_word = (substr(user_input(), $j, $i));
            if (in_array($search_word, $find_middle_array_stripped)) {
                $key = array_search($search_word, $find_middle_array_stripped);
                array_push($middle_array, hyphens()[sort_by_dot()['middle_key_array'][$key]]);
                //echo "\n"."Itteration results for " . $search_word . " are : " . $i . " ->";s
            }

        }
    }
   return $middle_array;
}
 */

//find back function
/*
 * function find_back() {
    $back_array = [];
    $i =0;

    $longest_find_map = array_map('strlen', sort_by_dot()['back_array']);
    $longest_find = max($longest_find_map) -1; //6
    $find_back_array_stripped = preg_replace('/\s/', '', sort_by_dot()['back_array']); //tarpu naikinimas
    $find_back_array_stripped_revved = array_map('strrev', $find_back_array_stripped);

    while($i++ < $longest_find){
        $search_word = ("." . substr(strrev(user_input()), 0, $i));
        //echo "Itteration results for " . $search_word . " are : ". $i . " ->>>" . "\n";
        if(in_array($search_word, $find_back_array_stripped_revved)){
            $key = array_search($search_word, $find_back_array_stripped_revved);
            array_push($back_array, hyphens()[sort_by_dot()['back_key_array'][$key]]);
        }
    }

    return $back_array;
}
 */