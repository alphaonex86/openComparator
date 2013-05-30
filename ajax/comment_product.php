<?php
include_once '../config/mysql.php';
include_once '../config/general.php';
include_once 'unknownlib-function.php';
include_once 'unknownlib-mysql.php';

unknownlib_mysql_connect_or_quit();
session_start();

if(!isset($_GET['action']))
	unknownlib_tryhack('action var not set');
if(!isset($_POST['seo']))
	unknownlib_tryhack('seo var not set');
$_GET['seo']=$_POST['seo'];

switch($_GET['action'])
{
	case 'del':
		if(isset($_SESSION['id_account']))
		{
			$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `url_alias_for_seo`=\''.addslashes($_POST['seo']).'\'');
			if($data_product = mysql_fetch_array($reply_product))
			{
				unknownlib_mysql_query('DELETE FROM `comment_product` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\' AND `id_account`='.$_SESSION['id_account']);
				ob_start();
				include 'product_note.php';
				$content=ob_get_contents();
				ob_end_clean();
				unknownlib_filewrite($_SERVER['DOCUMENT_ROOT'].'/'.$data_product['table_product'].'/'.$data_product['url_alias_for_seo'].'_note.json',$content);
				echo 'OK';
			}
			else
				unknownlib_tryhack('Try set comment to product not in database');
		}
		else
			unknownlib_tryhack('The user is not logged');
	break;
	case 'new':
		if(!isset($_POST['seo']))
			unknownlib_tryhack('seo var not set');
		if(isset($_POST['note']) && isset($_POST['comment']))
		{
			if($_POST['comment']=='')
			{
				echo 'Gracias para llenar tu comentario';
				exit;
			}
			if(preg_match('#^[0-9]+$#',$_POST['note']))
			{
				if(isset($_SESSION['id_account']))
				{
					$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `url_alias_for_seo`=\''.addslashes($_POST['seo']).'\'');
					if($data_product = mysql_fetch_array($reply_product))
					{
						unknownlib_mysql_query('UPDATE `comment_product` SET `note`='.addslashes($_POST['note']).',`comment`=\''.unknownlib_add_slashes($_POST['comment']).'\',`date`='.time().' WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\' AND `id_account`='.addslashes($_SESSION['id_account']));
						if(mysql_affected_rows()==0)
							unknownlib_mysql_query('INSERT INTO `comment_product`(`unique_identifier_product`,`id_account`,`note`,`comment`,`date`) VALUES(\''.addslashes($data_product['unique_identifier']).'\','.addslashes($_SESSION['id_account']).','.addslashes($_POST['note']).',\''.unknownlib_add_slashes($_POST['comment']).'\','.time().')') or die(mysql_error());
						ob_start();
						include 'product_note.php';
						$content=ob_get_contents();
						ob_end_clean();
						unknownlib_filewrite($_SERVER['DOCUMENT_ROOT'].'/'.$data_product['table_product'].'/'.$data_product['url_alias_for_seo'].'_note.json',$content);
						echo 'OK';
					}
					else
						unknownlib_tryhack('Try set comment to product not in database');
				}
				else
					unknownlib_tryhack('The user is not logged');
			}
			else
				unknownlib_tryhack('id and note is not a int');
		}
		else
			unknownlib_tryhack('Missing var');
	break;
	case 'warn':
		if(isset($_POST['user_name']))
		{
			if($_POST['user_name']!='')
			{
				if(isset($_SESSION['id_account']))
				{
					$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `url_alias_for_seo`=\''.addslashes($_POST['seo']).'\'');
					if($data_product = mysql_fetch_array($reply_product))
					{
						unknownlib_send_mail('Comment problem','On the product: '.htmlspecialchars($data_product['title']).' ('.htmlspecialchars($data_product['id']).'), the user: '.htmlspecialchars(unknownlib_strip_slashes($_POST['user_name'])).' seam have suppect comment repported by: '.htmlspecialchars($_SESSION['login_account']).' ('.htmlspecialchars($_SESSION['id_account']).')');
						ob_start();
						include 'template/product_note.php';
						$content=ob_get_contents();
						ob_end_clean();
						unknownlib_filewrite($_SERVER['DOCUMENT_ROOT'].'/'.$data_product['table_product'].'/'.$data_product['url_alias_for_seo'].'_note.json',$content);
						echo 'Los moderadores han sido avisados';
					}
					else
						unknownlib_tryhack('Try set comment to product not in database');
				}
				else
					unknownlib_tryhack('The user is not logged');
			}
			else
				unknownlib_tryhack('Var empty');
		}
		else
			unknownlib_tryhack('Missing var');
	break;
}
