<?php
include "get_values.php";

/**
 * @param array
 * @return array
 * function returns array without numbers
 *
 */

function remove_numbers($letters){
    $no_numbers = preg_replace('/\d/', '', $letters);
    return ($no_numbers);
}

/**
 * @param array
 * @return array
 * function retrieves user input
 *
 */

function user_input(){
    $test_string = "mistranslate";
    return $test_string;
}

/**
 *
 * @return array
 * function sorts by dot and putts to arrays.
 *
 */

function sort_by_dot(){
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

/**
 * @param array
 * @return array
 * function finds matching fronts, using sort_by_dot values
 *
 */

function find_front() {
    global $values;
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
            array_push($front_array, $values[sort_by_dot()['front_key_array'][$key]]);
        }
    }
    return $front_array;
}

/**
 * @param array
 * @return array
 * function finds matching middles
 *
 */

function find_middle(){
    global $values;
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
                array_push($middle_array, $values[sort_by_dot()['middle_key_array'][$key]]);
                //echo "\n"."Itteration results for " . $search_word . " are : " . $i . " ->";s
            }

        }
    }
   return $middle_array;
}

/**
 * @param array
 * @return array
 * function finds matching backs
 *
 */

function find_back() {
    global $values;
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
            array_push($back_array, $values[sort_by_dot()['back_key_array'][$key]]);
        }
    }

    return $back_array;
}

/**
 * @param array
 * @return string
 * function makes a hyphenate-ready string
 *
 */

function sort_patterns(){
    $sort_array = array_merge(find_front(), find_middle(), find_back());
    $sort_array_stripped = preg_replace('/\s/', '', $sort_array);
    $test_string_split = str_split(user_input());
    $test_string_numbers_split = [];
    $no_numbers_sort_array_split = array_map('remove_numbers', $sort_array_stripped);
    $no_numbers_sort_array_split_no_dots = str_replace('.','',$no_numbers_sort_array_split);
    $sort_array_stripped_no_dots = str_replace('.','',$sort_array_stripped);
    //print_r($no_numbers_sort_array_split_no_dots);
    $i = 0;
    $j = 0;

    // Skaiciu masyvas
    while ($i++ < strlen(user_input())){
        $test_string_numbers_split[$i] = "0";
    }

    // jungia taskus
    foreach ($sort_array_stripped as $key => $value){
        $pattern_nr = $key;
        $pattern_place = strrpos(user_input(), $no_numbers_sort_array_split_no_dots[$pattern_nr]); //neranda, nes taskai maiso
        //print_r("Pattern place :" . $pattern_place);
        $middle_string = $sort_array_stripped_no_dots[$pattern_nr];
        $middle_string = str_split($middle_string);

        $was_number = 0;
        foreach ($middle_string as $key => $value) {
            if (is_numeric($value)) {
                if ($test_string_numbers_split[$key + $pattern_place - $was_number] < $value) {
                    $test_string_numbers_split[$key + $pattern_place - $was_number] = $value;
                }
                $was_number++;

            }
        }
    }
    // Sujungia raides su turimu skaicius masyvu
    while ($j++ < strlen(user_input())-1){
        $test_string_split[$j] = $test_string_numbers_split[$j] . $test_string_split[$j] ;
    }
    $result = implode('',$test_string_split) . "0";
    return $result;
}

/**
 * @param array
 * function changes numbers to spaces and minuses
 *
 */

function hyphenate(){
    $odds = array("1", "3", "5");
    $hyphens = str_replace($odds, '-', sort_patterns());
    $hyphen = explode('-', $hyphens);
    $hyphenated = [];
    foreach ($hyphen as $item){
        $i = 0;
        $max_numbers = max(preg_split('/[a-zA-Z]+/', $item));
        $item_raw = preg_split('/\d/', $item);
        while ( $i++ <= $max_numbers -1){
            $item_raw[$i] = ' '.$item_raw[$i];
        }
        $imploded_items = implode("", $item_raw);
        array_push($hyphenated,$imploded_items);
    }
    print_r(implode('-', $hyphenated));
}

//Calling functions





