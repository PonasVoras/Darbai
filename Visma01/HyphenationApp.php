#!/usr/bin/env php
<?php

include "./oop/CLIApp.php";
require('oop/Config/AutoLoader.php');
Config\AutoLoader::autoInclude();

$mainClass = new Main\CLIApp();
$mainClass->main();