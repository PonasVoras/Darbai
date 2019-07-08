<?php

namespace algorithm;

class Sort_by_dot{

    
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