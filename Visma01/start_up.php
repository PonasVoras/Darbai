#!/usr/bin/env php
<?php

/*
 * If this file is chose,  a few options will appear :
 *  oop
 *      'Word to hyphenate' , takes a value
 *      sends a value to hyphenate.php
 *
 *  functional 'Word to hyphenate' :
 *      'Word to hyphenate', takes a value
 *      sends a value to start_up.php
 *
 */

include "./oop/Main.php";

use main\Main;

Main::main();

