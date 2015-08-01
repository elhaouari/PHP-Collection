<?php

require_once 'Collection.php';

// create collection of $colors
$colors = array('red', 'whait', 'black');
$collection = new Collection($colors);
/**
 * OR :
 * $collection = new Collection();
 * $collection->set(NULL, $colors);
 */

echo '<pre>';
var_dump($collection);
$collection->set(4, 'rouge');
$collection->set(5, 'rouge');

var_dump($collection);
echo $collection->get('5');
echo '<hr>';

// clear and set auther collection
$users = array(
    array('fname'=>'nabil', 'lname'=>'elhaouari', 'age'=>23),
    array('fname'=>'Diogo', 'lname'=>'Silva', 'age'=>25));
$collection->set(null, $users);

var_dump($collection);
echo $collection->get('0.fname') . ' ' . $collection->get('0.lname') . '<br />';


// set element
$collection->set('2', array('fname'=>'Souza', 'lname'=>'Silva', 'age'=>20));
/**
 * OR
 * $collection->set('2.fname', 'Souza');
 * $collection->set('2.lname', 'Silva');
 * $collection->set('2.age', 20);
 */
echo '<hr> collection with set:<br />';
var_dump($collection);


// get element by path
echo '<hr><br />get method: <br />';
echo $collection->get('2.fname') . ' ' . $collection->get('2.lname') . '<br /><br />';


// how to loop of element
echo '<br />loop: <br />';
foreach ($collection as $value) {
    echo $value['fname'] . ' ' . $value['lname'] . ' '. $value['age'].  '<br />';
}
echo '<hr>';


// get a list
echo '<br />list fname -- age: <br />';
var_dump($collection->lists('fname', 'age'));


// get max 
echo '<hr/><br />max of age: <br />';
echo $collection->max('age');


// extract method
echo '<hr/><br />extract fname: <br />';
print_r($collection->extract('fname'));


// join method
echo '<hr/><br />join method: <br />';
echo $collection->extract('fname')->join(', ');
