<?php

require_once 'Collection.php';

$colors = ['red', 'whait', 'black'];

$collection = new Collection();
$collection->push('colors', $colors);

echo '<pre>';
var_dump($collection->get('colors'));
// remove the first element
var_dump($collection->pop('colors.0'));

// add the color yello to other colors
$collection->push('colors', 'yello');
var_dump($collection->get('colors'));

// remove old colors and add the blue
$collection->push('colors', 'blue');
var_dump($collection->get('colors'));