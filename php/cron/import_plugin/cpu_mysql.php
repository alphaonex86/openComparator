<?php
$data_product=array();
$reply_product_info = unknownlib_mysql_query('SELECT * FROM `information_extra_processor` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if($data_product_info = mysql_fetch_array($reply_product_info))
	$data_product=$data_product_info;
else
	unknownlib_mysql_query('INSERT INTO `information_extra_processor`(`unique_identifier_product`) VALUES (\''.$unique_identifier_product.'\')');
if(!isset($data_product['frequency']) || $data_product['frequency']==0)
	if(isset($item['frequency']) && preg_match('#^[0-9]{1,4}$#i',$item['frequency']))
		unknownlib_mysql_query('UPDATE `information_extra_processor` SET `frequency`='.$item['frequency'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['socket']) || $data_product['socket']=='')
	if(isset($item['socket']) && preg_match('#^[a-z0-9+]{3,5}$#i',$item['socket']))
		unknownlib_mysql_query('UPDATE `information_extra_processor` SET `socket`=\''.addslashes($item['socket']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['nm']) || $data_product['nm']==0)
	if(isset($item['nm']) && preg_match('#^[0-9]{1,3}$#i',$item['nm']))
		unknownlib_mysql_query('UPDATE `information_extra_processor` SET `nm`=\''.addslashes($item['nm']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['nbr_core']) || $data_product['nbr_core']==0)
	if(isset($item['nbr_core']) && preg_match('#^[0-9]{1,2}$#i',$item['nbr_core']))
		unknownlib_mysql_query('UPDATE `information_extra_processor` SET `nbr_core`=\''.addslashes($item['nbr_core']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['TDP']) || $data_product['TDP']==0)
	if(isset($item['TDP']) && preg_match('#^[0-9]{1,3}$#i',$item['TDP']))
		unknownlib_mysql_query('UPDATE `information_extra_processor` SET `TDP`=\''.addslashes($item['TDP']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['L2']) || $data_product['L2']==0)
	if(isset($item['L2']) && preg_match('#^[0-9]{1,5}$#i',$item['L2']))
		unknownlib_mysql_query('UPDATE `information_extra_processor` SET `L2`=\''.addslashes($item['L2']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['L3']) || $data_product['L3']==0)
	if(isset($item['L3']) && preg_match('#^[0-9]{1,5}$#i',$item['L3']))
		unknownlib_mysql_query('UPDATE `information_extra_processor` SET `L3`=\''.addslashes($item['L3']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');