#!/usr/bin/env php
<?php

include "./oop/Main.php";

require('oop/Config/AutoLoader.php');
config\AutoLoader::autoInclude();

new Main\Main();
new Config\Config();
