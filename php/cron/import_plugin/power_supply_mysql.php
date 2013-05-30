<?php
$data_product=array();
$reply_product_info = unknownlib_mysql_query('SELECT * FROM `information_extra_powersupply` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if($data_product_info = mysql_fetch_array($reply_product_info))
	$data_product=$data_product_info;
else
	unknownlib_mysql_query('INSERT INTO `information_extra_powersupply`(`unique_identifier_product`) VALUES (\''.$unique_identifier_product.'\')');
if(!isset($data_product['power']) || $data_product['power']==0)
	if(isset($item['power']) && preg_match('#^[0-9]{1,4}$#i',$item['power']))
		unknownlib_mysql_query('UPDATE `information_extra_powersupply` SET `power`='.$item['power'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['modulaire']))
	if(isset($item['modulaire']))
		unknownlib_mysql_query('UPDATE `information_extra_powersupply` SET `modulaire`='.$item['modulaire'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['certification']) || $data_product['certification']=='')
	if(isset($item['certification']))
		unknownlib_mysql_query('UPDATE `information_extra_powersupply` SET `certification`=\''.addslashes($item['certification']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
