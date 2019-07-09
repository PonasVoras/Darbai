<?php
 namespace config;

 class AutoLoader
 {
     public static function autoInclude()
     {
         spl_autoload_register(function (string $class) {
             $class = str_replace('\\', '/', $class);
             if (file_exists("oop/$class.php")) {
                 include "oop/$class.php";
             }
         });
     }
 }