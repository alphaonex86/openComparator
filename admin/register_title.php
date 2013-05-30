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

//drop cache here
require 'drop_cache.php';
//update the title
unknownlib_mysql_query('UPDATE `product_base_information` SET `title`=\''.addslashes($_POST['value']).'\',`rewriten`=1 WHERE `unique_identifier`=\''.addslashes($_POST['unique_identifier']).'\'');

echo 'OK';
