#!/usr/bin/env php
<?php

include "./oop/Main.php";

require ('oop/config/AutoLoader.php');
config\AutoLoader::autoInclude();

new main\Main();
new config\Config();
