<?php

namespace algorithm;

use operations\File;

class Sort_patterns {
    public $possiblePatterns;
    public $allPatternsNumberless;
    private $word;

    public function __construct(string $word){
        $this->possiblePatterns = File::ReadFromFile("oop/data/possible_patterns.txt");
        $this->word = $word;

    }

}


/*
 * function sort_patterns(){

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
 */