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

if(!isset($_POST['value']) || !isset($_POST['unique_identifier']))
	unknownlib_tryhack('Wrong input value');
if($_POST['value']=='')
	die('Empty title not allowed!');

$product_base_information_reply=unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `unique_identifier`=\''.addslashes($_POST['unique_identifier']).'\'');
if($product_base_information_data=mysql_fetch_array($product_base_information_reply))
{
	$base='../'.$product_base_information_data['table_product'].'/';
	$source=$base.$product_base_information_data['url_alias_for_seo'];
	$destination=$base.$_POST['value'];
	if(file_exists($source.'.html'))
		rename($source.'.html',$destination.'.html');
	if(file_exists($source.'_note.json'))
		rename($source.'_note.json',$destination.'_note.json');
	rename($source.'.jpg',$destination.'.jpg');
	rename($source.'-mini.jpg',$destination.'-mini.jpg');
}
else
	unknownlib_tryhack('Product not found');

//drop cache here
require 'drop_cache.php';
//update the title
unknownlib_mysql_query('UPDATE `product_base_information` SET `url_alias_for_seo`=\''.addslashes($_POST['value']).'\',`rewriten`=1 WHERE `unique_identifier`=\''.addslashes($_POST['unique_identifier']).'\'');

echo 'OK';