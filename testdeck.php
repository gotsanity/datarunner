<?php
require_once 'classes/cards.class.php';
require_once 'functions.php';

$new = new deck(3, 'runner', 1, 'bad runner');

print '<img src="'.$new->identity->image().'">';

$cards = agenda::all_ids();
$cards = program::all_ids();
debug($cards);
foreach ($cards as $k => $v) {
	$new->addcard($v['card_id'], 'agenda');
	$i = count($new->cards) - 1;
	print '<img src="'.$new->cards[$i]->image().'">';	
}


debug($new);
?>
