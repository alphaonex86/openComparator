<?php
include_once '../config/mysql.php';
include_once '../config/general.php';
include_once 'unknownlib-function.php';
include_once 'unknownlib-mysql.php';

unknownlib_mysql_connect_or_quit();
session_start();

if(isset($_POST['antibot']) && isset($_POST['email']) && isset($_POST['pseudo']) && isset($_POST['password']))
{
	if($_POST['antibot']!='' && $_POST['email']!='' && $_POST['pseudo']!='' && $_POST['password']!='')
	{
		if(!isset($_SESSION['random']))
		{
			//$_SESSION['register_text']='Enlace de imagen no encontrado';
			header('Location: /message-no-image.html');
			exit;
		}
		if($_SESSION['random']==$_POST['antibot'])
		{
			if(preg_match('#^[a-z0-9\.\-_]+@[a-z0-9\.\-_]+\.[a-z]{2,4}$#',$_POST['email']))
			{
				$reply = unknownlib_mysql_query('SELECT * FROM `account` WHERE login=\''.unknownlib_add_slashes($_POST['pseudo']).'\' OR email=\''.unknownlib_add_slashes($_POST['email']).'\'');
				if($data = mysql_fetch_array($reply))
				{
					$_SESSION['register_text']='Este login o Email ya esta registrado en la base';
					header('Location: /register.html');
					exit;
				}
				else
				{
					$rand=rand(100000,999999);
					$return_val=unknownlib_mysql_query('INSERT INTO `account`(login, pass, email, date, code_val) VALUES(\''.unknownlib_add_slashes($_POST['pseudo']).'\',\''.sha1($_POST['password']).'\',\''.unknownlib_add_slashes($_POST['email']).'\','.time().','.$rand.')') or die_perso();
					//$_SESSION['message_text']='Consulte su correo electr&oacute;nico para validar su registro';
					//$_SESSION['message_icon']='/images/security-high.png';
					header('Location: /message-registred.html');
					unknownlib_send_mail('Activa su cuenta en '.$_SERVER['HTTP_HOST'],'Para activar su cuenta en http://'.$_SERVER['HTTP_HOST'].' , haz click aqui: http://'.$_SERVER['HTTP_HOST'].'/php/register-up.php?code='.$rand.'&email='.unknownlib_add_slashes($_POST['email']),$_POST['email'],'text/plain');
					exit;
				}
			}
			else
			{
				//$_SESSION['register_text']='El correo electr&oacute;nico no parece valido';
				header('Location: /message-invalid-email.html');
				//header('Location: /register.html');
				exit;
			}
		}
		else
		{
			//$_SESSION['register_text']='El c&oacute;digo antibot no es bueno!';
			//header('Location: /register.html');
			header('Location: /message-code-antibot.html');
			exit;
		}
	}
	else
	{
		//$_SESSION['register_text']='Uno de los formularios esta vacio, gracias por llenarlos todos';
		//header('Location: /register.html');
		header('Location: /message-field-empty.html');
		exit;
	}
}
else
{
	if(isset($_GET['code']) && isset($_GET['email']))
	{
		if(preg_match('#^[0-9]+$#',$_GET['code']) && preg_match('#^[a-z0-9\.\-_]+@[a-z0-9\.\-_]+\.[a-z]{2,4}$#',$_GET['email']))
		{
			$reply = unknownlib_mysql_query('SELECT * FROM `account` WHERE email=\''.unknownlib_add_slashes($_GET['email']).'\'');
			if($data = mysql_fetch_array($reply))
			{
				if($data['code_val']==0 && $data['enabled']==0)
				{
					//$_SESSION['message_text']='Su cuenta esta desactivada, contacte el administrador';
					//$_SESSION['message_icon']='/images/security-medium.png';
					header('Location: /message-account-disabled.html');
					exit;
				}
				elseif($data['code_val']!=0)
				{
					if($_GET['code']==$data['code_val'])
					{
						unknownlib_mysql_query('UPDATE `account` SET `code_val`=0, `enabled`=1 WHERE id='.unknownlib_add_slashes($data['id']));
						//$_SESSION['message_text']='Su cuenta ha sido activada, gracias por su registro';
						//$_SESSION['message_icon']='/images/security-high.png';
						$_SESSION['id_account']		= $data['id'];
						$_SESSION['login_account']	= $data['login'];
						$_SESSION['email_account']	= $data['email'];
						setcookie('username',$_SESSION['login_account'],0,'/');
						header('Location: /message-account-activated.html');
						exit;
					}
					else
					{
						header('Location: /message-wrong-register-code.html');
						exit;
					}
				}
				else
				{
					//$_SESSION['message_text']='Su cuenta ya esta activada';
					//$_SESSION['message_icon']='/images/security-medium.png';
					header('Location: /message-account-already-activated.html');
					exit;
				}
			}
			else
			{
				//$_SESSION['message_text']='Su cuenta no existe';
				//$_SESSION['message_icon']='/images/security-medium.png';
				header('Location: /message-account-missing.html');
				exit;
			}
		}
		else
		{
			unknownlib_tryhack('the value are not int and email');
			exit;
		}
	}
	else
	{
		unknownlib_tryhack('deny to access to this url without arguement or post data');
		exit;
	}
}
?>