<?php
$data_product=array();
$reply_product_info = unknownlib_mysql_query('SELECT * FROM `information_extra_memory` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if($data_product_info = mysql_fetch_array($reply_product_info))
	$data_product=$data_product_info;
else
	unknownlib_mysql_query('INSERT INTO `information_extra_memory`(`unique_identifier_product`) VALUES (\''.$unique_identifier_product.'\')');
if(!isset($data_product['memory_type']) || $data_product['memory_type']=='')
	if(isset($item['memory_type']))
		unknownlib_mysql_query('UPDATE `information_extra_memory` SET `memory_type`=\''.addslashes($item['memory_type']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['frequency']) || $data_product['frequency']==0)
	if(isset($item['frequency']) && preg_match('#^[0-9]+$#',$item['frequency']))
		unknownlib_mysql_query('UPDATE `information_extra_memory` SET `frequency`='.$item['frequency'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['size']) || $data_product['size']==0)
	if(isset($item['size']) && preg_match('#^[0-9]+$#',$item['size']))
		unknownlib_mysql_query('UPDATE `information_extra_memory` SET `size`='.$item['size'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['ecc']) || $data_product['ecc']=='')
	if(isset($item['ecc']))
		unknownlib_mysql_query('UPDATE `information_extra_memory` SET `ecc`='.((int)$item['ecc']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['format']) || $data_product['format']=='')
	if(isset($item['format']))
		unknownlib_mysql_query('UPDATE `information_extra_memory` SET `format`=\''.addslashes($item['format']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['kit']) || $data_product['kit']==0)
	if(isset($item['kit']) && preg_match('#^[0-9]+$#',$item['kit']))
		unknownlib_mysql_query('UPDATE `information_extra_memory` SET `kit`='.$item['kit'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['cas']) || $data_product['cas']==0)
	if(isset($item['cas']))
		unknownlib_mysql_query('UPDATE `information_extra_memory` SET `cas`='.$item['cas'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['voltage']) || $data_product['voltage']==0)
	if(isset($item['voltage']) && preg_match('#^[0-9]+\.[0-9]+?$#',$item['voltage']))
		unknownlib_mysql_query('UPDATE `information_extra_memory` SET `voltage`='.$item['voltage'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
