<?php
function getCountNumbers($string, $find){
    return preg_match_all('/' . $find . '/', $string);
}

echo getCountNumbers(442158755745, 5);
