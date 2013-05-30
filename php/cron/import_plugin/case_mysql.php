<?php
$data_product=array();
$reply_product_info = unknownlib_mysql_query('SELECT * FROM `information_extra_cases` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if($data_product_info = mysql_fetch_array($reply_product_info))
	$data_product=$data_product_info;
else
	unknownlib_mysql_query('INSERT INTO `information_extra_cases`(`unique_identifier_product`) VALUES (\''.$unique_identifier_product.'\')');
if(!isset($data_product['format']) || $data_product['format']=='')
	if(isset($item['format']))
		unknownlib_mysql_query('UPDATE `information_extra_cases` SET `format`=\''.addslashes($item['format']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['matiere']) || $data_product['matiere']=='')
	if(isset($item['matiere']))
		unknownlib_mysql_query('UPDATE `information_extra_cases` SET `matiere`=\''.addslashes($item['matiere']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['format']) || $data_product['format']=='')
	if(isset($item['format']))
		unknownlib_mysql_query('UPDATE `information_extra_cases` SET `format`=\''.addslashes($item['format']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['dimension']) || $data_product['dimension']=='')
	if(isset($item['dimension']))
		unknownlib_mysql_query('UPDATE `information_extra_cases` SET `dimension`=\''.addslashes($item['dimension']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['weight']) || $data_product['weight']==0)
	if(isset($item['weight']))
		unknownlib_mysql_query('UPDATE `information_extra_cases` SET `weight`='.$item['weight'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
