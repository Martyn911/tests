<?php

$result = array_filter(range(20, 45), function ($item){
    if(is_int(($item/5))) return $item;
});

print_r($result);
/*
Array
(
    [0] => 20
    [5] => 25
    [10] => 30
    [15] => 35
    [20] => 40
    [25] => 45
)
*/

echo array_sum($result); //195