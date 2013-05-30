<?php
include_once '../config/mysql.php';
include_once '../config/general.php';
include_once 'unknownlib-function.php';
include_once 'unknownlib-mysql.php';

if(!unknownlib_mysql_connect())
{
	unknownlib_die_perso('Unable to connect to mysql',false);
	header('Location: /message-bug.html');
	exit;
}
session_start();

unknownlib_mysql_query('DELETE FROM `ipban` WHERE lastfail<'.(time()-3600));
$reply = unknownlib_mysql_query('SELECT * FROM `ipban` WHERE ip=\''.addslashes($_SERVER['REMOTE_ADDR']).'\' AND failcount>4');
if($data = mysql_fetch_array($reply))
{
	unknownlib_mysql_query('UPDATE `ipban` SET lastfail='.time().' WHERE ip=\''.addslashes($_SERVER['REMOTE_ADDR']).'\'');
	unset($_SESSION['id_account']);
	header('Location: /message-login-wrong.html');
	exit;
}
else
{
	if(isset($_POST['password']) && isset($_POST['pseudo']))
	{
		$reply = mysql_query('SELECT * FROM `account` WHERE `login`=\''.unknownlib_add_slashes($_POST['pseudo']).'\' AND `pass`=\''.sha1($_POST['password']).'\'') or die(mysql_error());
		if($data = mysql_fetch_array($reply))
		{
			if($data['enabled']==0)
			{
				unset($_SESSION['id_account']);
				header('Location: /message-account-disabled.html');
				exit;
			}
			elseif($data['code_val']!=0)
			{
				unset($_SESSION['id_account']);
				header('Location: /message-not-activated.html');
				exit;
			}
			else
			{
				$_SESSION['id_account']		= $data['id'];
				$_SESSION['login_account']	= $data['login'];
				$_SESSION['email_account']	= $data['email'];
				$_SESSION['is_admin']		= $data['admin'];
				setcookie('username',$_SESSION['login_account'],0,'/');
				header('Location: /message-logged.html');
				exit;
			}
		}
		else
		{
			$reply = mysql_query('SELECT * FROM `ipban` WHERE ip=\''.addslashes($_SERVER['REMOTE_ADDR']).'\'') or die(mysql_error());
			if($data = mysql_fetch_array($reply))
				mysql_query('UPDATE `ipban` SET lastfail='.time().',failcount=failcount+1 WHERE ip=\''.addslashes($_SERVER['REMOTE_ADDR']).'\'') or die(mysql_error());
			else
				mysql_query('INSERT INTO `ipban` VALUES(\'\', \''.addslashes($_SERVER['REMOTE_ADDR']).'\', 1, '.time().')') or die(mysql_error());
			unset($_SESSION['id_account']);
			header('Location: /message-login-wrong.html');
			exit;
		}
	}
	else
	{
		$reply = mysql_query('SELECT * FROM `ipban` WHERE ip=\''.addslashes($_SERVER['REMOTE_ADDR']).'\'') or die(mysql_error());
		if($data = mysql_fetch_array($reply))
			mysql_query('UPDATE `ipban` SET lastfail='.time().',failcount=failcount+1 WHERE ip=\''.addslashes($_SERVER['REMOTE_ADDR']).'\'') or die(mysql_error());
		else
			mysql_query('INSERT INTO `ipban` VALUES(\'\', \''.addslashes($_SERVER['REMOTE_ADDR']).'\', 1, '.time().')') or die(mysql_error());
		unset($_SESSION['id_account']);
		header('Location: /message-login-wrong.html');
		exit;
	}
}
?>