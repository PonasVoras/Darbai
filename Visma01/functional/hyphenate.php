<?php
include 'algorithm.php';

print_r("_____________________________________________"  . "\n");
$start_timing = microtime(true);
hyphenate();
$end_timing = microtime(true);
print_r("\nExecution :". ($end_timing - $start_timing));
print_r("\n"."_____________________________________________");