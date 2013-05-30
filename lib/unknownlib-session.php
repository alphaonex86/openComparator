<?php
/** \note Need include before the unknownlib-function.php **/
include_once 'unknownlib-function.php';
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['session']=true;

/// \brief at the module inclusion check the variable global and load session if needed
if(!isset($_SESSION))
	session_start();

/** *************************** Ip ban function *********************** 
\note require table ipban with: id (int, primary auto increment), ip (var char 64), failcount (int), lastfail (int)
**********************************************************************/

/** \brief return true if the current ip if ban
\return true or false */
function unknownlib_current_ip_is_ban_mysql($number_failed_allowed=4,$timeban=3600)
{
	unknownlib_mysql_query('DELETE FROM `ipban` WHERE `lastfail`<'.(time()-$timeban));
	$reply = unknownlib_mysql_query('SELECT * FROM `ipban` WHERE `ip`=\''.addslashes($_SERVER['REMOTE_ADDR']).'\' AND `failcount`>'.$number_failed_allowed);
	if($data = mysql_fetch_array($reply))
	{
		unknownlib_mysql_query('UPDATE `ipban` SET `lastfail`='.time().' WHERE `ip`=\''.addslashes($_SERVER['REMOTE_ADDR']).'\'');
		return true;
	}
	else
		return false;
}

/// \brief mysql warper
function unknownlib_current_ip_is_ban($number_failed_allowed=4,$timeban=3600)
{
	$is_ban=unknownlib_current_ip_is_ban_mysql($number_failed_allowed,$timeban);
	return $is_ban;
}

function unknownlib_add_fail_for_this_ip_mysql()
{
	if($_SERVER['SERVER_ADDR']==$_SERVER['REMOTE_ADDR'])
		return;
	$reply = unknownlib_mysql_query('SELECT * FROM `ipban` WHERE `ip`=\''.addslashes($_SERVER['REMOTE_ADDR']).'\'');
	if($data = mysql_fetch_array($reply))
		unknownlib_mysql_query('UPDATE `ipban` SET `lastfail`='.time().',`failcount`=`failcount`+1 WHERE `ip`=\''.addslashes($_SERVER['REMOTE_ADDR']).'\'');
	else
		unknownlib_mysql_query('INSERT INTO `ipban`(`ip`,`failcount`,`lastfail`) VALUES(\''.addslashes($_SERVER['REMOTE_ADDR']).'\', 1, '.time().')');
}

/// \brief mysql warper
function unknownlib_add_fail_for_this_ip()
{
	return unknownlib_add_fail_for_this_ip_mysql();
}

/// \brief show error message and quit
function unknownlib_check_if_ban_site($text,$delete_session=false,$number_failed_allowed=4,$timeban=3600)
{
	if(unknownlib_current_ip_is_ban($number_failed_allowed,$timeban))
	{
		if($delete_session)
		{
			if(session_id()=='')
				@session_start();
			@session_destroy();
		}
		echo $text;
		if(isset($_SERVER['REMOTE_ADDR']))
			$ip=$_SERVER['REMOTE_ADDR'];
		else
			$ip='';
		unknownlib_send_mail('Too many try login on '.$_SERVER['HTTP_HOST'],'<div id="debug" style="color:#000;border:1px solid #555;background-color:#f9f9f9;padding:5px;">Too many try login with the ip: '.$ip.'</b></u></div><br />On the site: '.$_SERVER['HTTP_HOST'].'<br />On the url: '.$_SERVER['REQUEST_URI'].'<br />'.unknownlib_env());
		exit;
	}
}

/** *************************** Session function *********************** 
\note require table account with: id (int, primary auto increment), login (text as clear without '), pass (text as md5), group (text)
\note destroy current session if wrong login/pass
\warning the session should be started here
\param login send login as clear with '
\param pass send pass as clear
\return Return true if good and false if wrong
**********************************************************************/
function unknownlib_create_session_mysql($login,$pass,$table='account')
{
	include_once 'unknownlib-mysql.php';
	if(unknownlib_is_logged())
	{
		unknownlib_session_destroy();
		@session_start();
	}
	$reply = unknownlib_mysql_query('SELECT * FROM `'.$GLOBALS['unknownlib']['mysql']['prefix'].$table.'` WHERE `login`=\''.addslashes($login).'\' AND `pass`=\''.md5($pass).'\'');
	if($data = mysql_fetch_array($reply))
	{
		$_SESSION['account_id']=$data['id'];
		$_SESSION['account_login']=$data['login'];
		if(isset($data['group']))
			$_SESSION['account_group']=$data['group'];
		if(isset($_SERVER['REMOTE_ADDR']))
			$_SESSION['account_session_ip']=$_SERVER['REMOTE_ADDR'];
		return true;
	}
	else
	{
		unknownlib_add_fail_for_this_ip();
		unknownlib_session_destroy();
		return false;
	}
}

/// \brief mysql warper
function unknownlib_create_session($login,$pass,$table='account')
{
	return unknownlib_create_session_mysql($login,$pass,$table);
}

/// \brief check if logged
function unknownlib_is_logged()
{
	if(session_id()=='')
		return false;
	if(!isset($_SESSION['account_id']))
		return false;
	if(!isset($_SESSION['account_login']))
		return false;
	if($_SESSION['account_id']=='')
		return false;
	if($_SESSION['account_login']=='')
		return false;
	if(isset($_SESSION['account_session_ip']) && isset($_SERVER['REMOTE_ADDR']) && $_SESSION['account_session_ip']!=$_SERVER['REMOTE_ADDR'])
		return false;
	return true;
}

function unknownlib_session_redirect($location='/')
{
	if(!unknownlib_is_logged())
	{
		header('Location: '.$location);
		exit;
	}
	if(isset($_SERVER['HTTP_REFERER']) && !preg_match('#^http(s)?://'.preg_quote($_SERVER['SERVER_NAME']).'(:[0-9]{1,5})?/#',$_SERVER['HTTP_REFERER']))
		unknownlib_tryhack('Try access to this page without good referer');
}

/// \brief destroy the current session
function unknownlib_session_destroy()
{
	if(session_id()=='')
		@session_start();
	@session_destroy();
}

?>