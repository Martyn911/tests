<?php
function getColor($int){
    $minute = $int % 5;
    if($minute < 2) {
        return 'Зеленый';
    }
    if($minute > 2 && $minute < 5){
        return 'Красный';
    }
}

echo getColor(26);