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
			$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` WHERE `url_alias_for_seo`=\''.addslashes($_POST['seo']).'\'');
			if($data_shop = mysql_fetch_array($reply_shop))
			{
				unknownlib_mysql_query('DELETE FROM `comment_shop` WHERE `id_shop`='.$data_shop['id'].' AND `id_account`='.$_SESSION['id_account']);
				ob_start();
				include 'shop_note.php';
				$content=ob_get_contents();
				ob_end_clean();
				unknownlib_filewrite($_SERVER['DOCUMENT_ROOT'].'/tiendas-informatica/'.$data_shop['url_alias_for_seo'].'_note.json',$content);
				echo 'OK';
			}
			else
				unknownlib_tryhack('Try set comment to shop not in database');
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
					$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` WHERE `url_alias_for_seo`=\''.addslashes($_POST['seo']).'\'');
					if($data_shop = mysql_fetch_array($reply_shop))
					{
						unknownlib_mysql_query('UPDATE `comment_shop` SET `note`='.addslashes($_POST['note']).',`comment`=\''.unknownlib_add_slashes($_POST['comment']).'\',`date`='.time().' WHERE `id_shop`='.$data_shop['id'].' AND `id_account`='.addslashes($_SESSION['id_account']));
						if(mysql_affected_rows()==0)
							unknownlib_mysql_query('INSERT INTO `comment_shop`(`id_shop`,`id_account`,`note`,`comment`,`date`) VALUES('.$data_shop['id'].','.addslashes($_SESSION['id_account']).','.addslashes($_POST['note']).',\''.unknownlib_add_slashes($_POST['comment']).'\','.time().')') or die(mysql_error());
						ob_start();
						include 'shop_note.php';
						$content=ob_get_contents();
						ob_end_clean();
						unknownlib_filewrite($_SERVER['DOCUMENT_ROOT'].'/tiendas-informatica/'.$data_shop['url_alias_for_seo'].'_note.json',$content);
						echo 'OK';
					}
					else
						unknownlib_tryhack('Try set comment to shop not in database');
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
					$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` WHERE `url_alias_for_seo`=\''.addslashes($_POST['seo']).'\'');
					if($data_shop = mysql_fetch_array($reply_shop))
					{
						unknownlib_send_mail('Comment problem','On the shop: '.htmlspecialchars($data_shop['title']).' ('.htmlspecialchars($data_shop['id']).'), the user: '.htmlspecialchars(unknownlib_strip_slashes($_POST['user_name'])).' seam have suppect comment repported by: '.htmlspecialchars($_SESSION['login_account']).' ('.htmlspecialchars($_SESSION['id_account']).')');
						ob_start();
						include 'template/shop_note.php';
						$content=ob_get_contents();
						ob_end_clean();
						unknownlib_filewrite($_SERVER['DOCUMENT_ROOT'].'/tiendas-informatica/'.$data_shop['url_alias_for_seo'].'_note.json',$content);
						echo 'Los moderadores han sido avisados';
					}
					else
						unknownlib_tryhack('Try set comment to shop not in database');
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
