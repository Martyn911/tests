<?php
function getCard($card){
    if(in_array($card, range(1,10))) return $card;
    $cards = [
      '11' => 'Валет',
      '12' => 'Дама',
      '13' => 'Король',
      '14' => 'Туз'
    ];
    if(in_array($card, array_keys($cards))) return $cards[$card];
}
echo getCard(13);