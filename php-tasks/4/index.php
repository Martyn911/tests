<?php
$array = range(24, 35);
shuffle($array);
sort($array);

$start_key = 0;
$end_key = count($array) - 1;

echo 'Min: ' . $array[$start_key];
echo 'Max: ' . $array[$end_key];
list($array[$start_key], $array[$end_key]) = array($array[$end_key], $array[$start_key]);
print_r($array);
