<?php

include "./API/Router.php";
require("./Config/AutoLoader.php");
Config\AutoLoader::autoInclude();

$Router = new API\Router();
$Router->useController();