<?php
require '../config/general.php';
require '../config/mysql.php';
require 'unknownlib-function.php';
unknownlib_check_unknownlib_config();
require 'unknownlib-mysql.php';
if(!isset($_POST['product_type']))
	exit;
if(!isset($GLOBALS['unknownlib']['site']['reverse_link'][$_POST['product_type']]))
	exit;
unknownlib_mysql_connect_or_quit();

$reply=unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `url_alias_for_seo`=\''.$_POST['seo'].'\' AND `table_product`=\''.$_POST['product_type'].'\'');
while($data=mysql_fetch_array($reply))
{
	$array_data=array();
	$reply_prices = unknownlib_mysql_query('SELECT * FROM `prices` WHERE `ean_product`=\''.addslashes($data['ean']).'\'');
	while($data_prices = mysql_fetch_array($reply_prices))
		$array_data[]=$data_prices;
	echo unknownlib_array_to_json($array_data);
}
/** *************************** Load the content ************************** **/

