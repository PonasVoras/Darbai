<?php

/**
 *A menu is created
 *
 * */

spl_autoload_register(function($class) {
    include  __DIR__ . '/algorithms/' . $class . '.php';
});

$obj = new Test\Hyphenate();

