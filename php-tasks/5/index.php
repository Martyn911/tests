<?php
$array = range(1, 100);

$paired = [];
$not_paired = [];
foreach ($array as $k => $value){
    if($value <= 0) continue;
    if(is_int($k/2)) {
        $paired[] = $value;
    } else {
        $not_paired[] = $value;
    }
}

//вычислить произведение тех элементов, которые больше ноля и у которых парные индексы
echo array_product($paired);

//После вывести на экран элементы, которые больше ноля и у которых не парный индекс
print_r($not_paired);