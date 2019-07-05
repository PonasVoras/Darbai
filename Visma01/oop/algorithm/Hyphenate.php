<?php
/*include 'algorithm.php';*/

/*print_r("_____________________________________________"  . "\n");
$start_timing = microtime(true);
hyphenate();
$end_timing = microtime(true);
print_r("\nExecution :". ($end_timing - $start_timing));
print_r("\n"."_____________________________________________");*/

namespace hyphenate;

class Hyphenate
{

    public function __construct()
    {
        echo "The class " . __CLASS__ . " was initiated\n";
    }

    public $prop = "I am class property\n";
    public function setProperty($newval)
    {
        $this->prop1 = $newval;
    }
    public function getProperty()
    {
        return $this->prop1 . "<br />";
    }
}

/*
 *
 * function hyphenate(){
    $odds = array("1", "3", "5");
    $evens = array("0", "2", "4");
    $hyphens = str_replace($odds, '-', sort_patterns());
    $hyphens = str_replace($evens, ' ', $hyphens);
    print_r($hyphens);
}
 */