<?php
include_once '../config/mysql.php';
include_once '../config/general.php';
include_once '../lib/unknownlib-function.php';
include_once '../lib/unknownlib-mysql.php';

unknownlib_mysql_connect_or_quit();

session_start();
if(isset($_SESSION['is_admin']))
{
	if($_SESSION['is_admin']!=1)
		unknownlib_tryhack('The user is not admin');
}
else
	unknownlib_tryhack('The user is not logged');

if(isset($_GET['need_new_product']) && preg_match('#^[0-9]+$#',$_GET['need_new_product']))
	$number_of_entry=$_GET['need_new_product'];
else
	$number_of_entry=20;

if(isset($_GET['offset']) && preg_match('#^[0-9]+$#',$_GET['offset']))
	$offset=$_GET['offset'];
else
	$offset=0;

$product_base_information=array();
$product_base_information_reply=unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `rewriten`=0 ORDER BY `product_base_information`.`date` DESC LIMIT '.$offset.','.$number_of_entry);
while($product_base_information_data=mysql_fetch_array($product_base_information_reply))
{
	$reply=unknownlib_mysql_query('SELECT * FROM `information_extra_'.$product_base_information_data['table_product'].'` WHERE `unique_identifier_product`=\''.addslashes($product_base_information_data['unique_identifier']).'\'');
	while($data=mysql_fetch_array($reply))
		$product_base_information_data['spec']=unknownlib_mysql_clean_data_return($data,array('unique_identifier_product'));
	
	$reply=unknownlib_mysql_query('SELECT * FROM `product_base_information_given` WHERE `unique_identifier`=\''.addslashes($product_base_information_data['unique_identifier']).'\'');
	while($data=mysql_fetch_array($reply))
	{
		$data['title']=@unserialize($data['title']);
		if($data['title']===FALSE)
			$data['title']=array();
		$product_base_information_data['altern'][]=unknownlib_mysql_clean_data_return($data,array('id','unique_identifier'));
	}
	
	$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($product_base_information_data['unique_identifier']).'\'');
	if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
		$product_base_information_data['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
	
	if(!isset($product_base_information['thumb_overwrite']))
	{
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$product_base_information_data['table_product'].'/'.$product_base_information_data['url_alias_for_seo'].'.jpg'))
			$product_base_information_data['thumb_normal']='1';
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$product_base_information_data['table_product'].'/'.$product_base_information_data['url_alias_for_seo'].'-mini.jpg'))
			$product_base_information_data['thumb_mini']='1';
	}
	
	$product_base_information_data=unknownlib_mysql_clean_data_return($product_base_information_data);
	$product_base_information[]=$product_base_information_data;
}
echo unknownlib_array_to_json($product_base_information);