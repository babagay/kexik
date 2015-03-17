<?php

@$contacts_selection = unserialize( file_get_contents($contacts_selection_arr) );	

if(!is_array($contacts_selection))
	$contacts_selection = array();

$r =  file_get_contents('contacts_selection_arr.txt');
	
$arr = unserialize($r);

$c = count($arr);

echo "<pre>";
print_r($c);
//print_r($arr);
//var_dump( array_merge($contacts_selection, $arr) );