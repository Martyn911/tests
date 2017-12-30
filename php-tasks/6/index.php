<?php
function shortFIO($fio){
    $array = explode(' ', $fio);
    list($array[1], $array[2]) = [mb_strimwidth($array[1], 0, 2, '.'), mb_strimwidth($array[2], 0, 2, '.')];
    return implode(' ', $array);
}

echo shortFIO('Иванов Иван Сергеевич');