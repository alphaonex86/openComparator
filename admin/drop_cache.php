<?php
require 'cache_url.php';
foreach($url_list as $index => $file)
	if(preg_match('#/$#',$file))
		$url_list[$index].='index.html';

$product_base_information_reply=unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `unique_identifier`=\''.addslashes($_POST['unique_identifier']).'\'');
if($product_base_information_data=mysql_fetch_array($product_base_information_reply))
{
	$url_list[]='/'.$product_base_information_data['table_product'].'/'.$product_base_information_data['url_alias_for_seo'].'.html';
	$url_list[]='/'.$product_base_information_data['table_product'].'/'.$product_base_information_data['url_alias_for_seo'].'.jpg';
	$url_list[]='/'.$product_base_information_data['table_product'].'/'.$product_base_information_data['url_alias_for_seo'].'-mini.jpg';
	$url_list[]='/'.$product_base_information_data['table_product'].'/'.$product_base_information_data['url_alias_for_seo'].'_note.json';
	$url_list[]='/'.$product_base_information_data['table_product'].'/index.html';
	$url_list[]='/'.$GLOBALS['unknownlib']['site']['reverse_link'][$product_base_information_data['table_product']].'.html';
}
else
	unknownlib_tryhack('Product not found'); 

foreach($url_list as $file)
{
	$real_file='..'.$file;
	if(file_exists($real_file))
		unlink($real_file);
}
