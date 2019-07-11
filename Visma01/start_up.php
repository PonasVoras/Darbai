#!/usr/bin/env php
<?php

include "./Oop/Main.php";

require('Oop/Config/AutoLoader.php');
config\AutoLoader::autoInclude();

new Main\Main();
new Config\Config();
