<?php
/// \note This file require unknownlib-function.php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['array']=true;

function unknownlib_array_sort($array,$type,$sort='greater_on_top')
{
	if($sort!='greater_on_top' && $sort!='lower_on_top')
		unknownlib_die_perso('$sort is wrong');
	if(count($array)<=1)
		return $array;
	usort($array, create_function('array $a, array $b','return $a[\''.$type.'\'] < $b[\''.$type.'\'] ? -1 : 1;'));
	if($sort=='lower_on_top')
		return $array;
	else
		return array_reverse($array);
}

function unknownlib_array_filter($array,$key,$value)
{
	foreach($array as $no => $sub_array)
		if(!isset($sub_array[$key]))
			unknownlib_die_perso('For the array, the sub_array have not the key '.$key);
		elseif($sub_array[$key]!=$value)
			unset($array[$no]);
	return $array;
}

?>