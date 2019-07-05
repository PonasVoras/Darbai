<?php

/*
 *A menu is created
 *
 * */

class MyClass
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

$obj = new MyClass;

echo $obj->prop;
