<?php

namespace find_patterns;

class Find_patterns

{

}


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