<?php
require '../config/general.php';
require '../config/mysql.php';
require 'unknownlib-function.php';
unknownlib_check_unknownlib_config();
require 'unknownlib-mysql.php';
if(!isset($_POST['url']))
	exit;
if(!isset($_POST['seo']))
	exit;
unknownlib_mysql_connect_or_quit();

$reply_product=unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `url_alias_for_seo`=\''.addslashes($_POST['seo']).'\'');
while($data_product=mysql_fetch_array($reply_product))
{
	$reply_price=unknownlib_mysql_query('SELECT * FROM `prices` WHERE `url`=\''.addslashes($_POST['url']).'\'');
	while($data_price=mysql_fetch_array($reply_price))
	{
		$time=floor(time()/(60*15))*60*15;
		$reply_output=unknownlib_mysql_query('SELECT * FROM `output_product` WHERE `timestamp`='.$time.' AND `unique_identifier_product`=\''.$data_price['unique_identifier_product'].'\' AND `id_shop`='.$data_price['id_shop']);
		if($data_output=mysql_fetch_array($reply_output))
			unknownlib_mysql_query('UPDATE `output_product` SET `count`='.($data_output['count']+1).' WHERE `timestamp`='.$time.' AND `unique_identifier_product`=\''.$data_price['unique_identifier_product'].'\' AND `id_shop`='.$data_price['id_shop']);
		else
			unknownlib_mysql_query('INSERT INTO `output_product`(`timestamp`,`unique_identifier_product`,`id_shop`,`count`,`categorie`,`sub_categorie`) VALUES ('.$time.',\''.$data_price['unique_identifier_product'].'\','.$data_price['id_shop'].',1,\''.$GLOBALS['unknownlib']['site']['reverse_link'][$data_product['table_product']].'\',\''.$data_product['table_product'].'\')');
	}
}
