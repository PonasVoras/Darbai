#!/usr/bin/env php
<?php

include "./oop/Main.php";

require('oop/Config/AutoLoader.php');
config\AutoLoader::autoInclude();

$mainClass = new Main\Main();
$mainClass->main();