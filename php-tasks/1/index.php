<?php
function getSum($integer){
    $integer = intval($integer);
    $array = str_split($integer);
    return array_sum($array);
}

echo getSum(123);