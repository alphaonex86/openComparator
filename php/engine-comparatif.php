<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	$GLOBALS['unknownlib']['site']['engine_loaded']=true;
else
	unknownlib_die_perso('Engine already loaded');

//include here the core function only function
require_once 'unknownlib-function.php';
//include here all the config file
require $_SERVER['DOCUMENT_ROOT'].'/config/general.php';
require $_SERVER['DOCUMENT_ROOT'].'/config/mysql.php';
unknownlib_check_unknownlib_config();
//include all the unknownlib modules needed and the the linked global variable
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/unknownlib-cache.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/unknownlib-mysql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/unknownlib-comparator.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/unknownlib-text-operation.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/unknownlib-rss.php';
//connect to mysql

unknownlib_mysql_connect_or_quit('','','','',true);

$page_found_and_generate_cache=true;
if($GLOBALS['unknownlib']['site']['do_cache'])
	ob_start();
if($_SERVER['REQUEST_URI']=='/')
	require 'index.php';
elseif($_SERVER['REQUEST_URI']=='/js/news-content.js')
	require 'news-content.php';
elseif($_SERVER['REQUEST_URI']=='/rss.xml')
	require 'rss.php';
else
{
	if(preg_match('#^/[a-z\-_]+/#isU',$_SERVER['REQUEST_URI']))
	{
		$sub_folder=preg_replace('#^/([a-z\-_]+)/(.+\.html)?$#isU','$1',$_SERVER['REQUEST_URI']);
		if($sub_folder=='boutiques')
		{
			$url=preg_replace('#^/[a-z\-_]+/((.+)\.html)?$#isU','$2',$_SERVER['REQUEST_URI']);
			if($url=='')
				require 'shop_list.php';
			else
			{
				$shop_informations=unknownlib_comparator_get_shops_seo($url);
				$_GET['seo']=$url;
				require 'shop_details.php';
			}
		}
		else
		{
			$url=preg_replace('#^/[a-z\-_]+/((.+)\.html)?$#isU','$2',$_SERVER['REQUEST_URI']);
			if(isset($GLOBALS['unknownlib']['site']['reverse_link'][$sub_folder]))
			{
				if($url!='')
				{
					$_GET['seo']=$url;
					$_GET['cat']=$GLOBALS['unknownlib']['site']['reverse_link'][$sub_folder];
					$_GET['sub_cat']=$sub_folder;
					$array_current_cat=$GLOBALS['unknownlib']['site']['categories'][$GLOBALS['unknownlib']['site']['reverse_link'][$_GET['sub_cat']]]['sub_cat'][$_GET['sub_cat']];
					//get the mysql content
					$product_informations=unknownlib_comparator_get_single_product_informations_seo($_GET['seo']);
					if(!isset($product_informations['id']))
					{
						$page_found_and_generate_cache=false;
						require '404-not-found.php';
					}
					else
					{
						$_GET['id']=$product_informations['id'];
						require 'product_details.php';
					}
				}
				else
				{
					$_GET['cat']=$GLOBALS['unknownlib']['site']['reverse_link'][$sub_folder];
					$_GET['sub_cat']=$sub_folder;
					require 'product_list.php';
				}
			}
			else
			{
				$page_found_and_generate_cache=false;
				require '404-not-found.php';
			}
		}
	}
	elseif(preg_match('#/([a-z\-]+)\.html#isU',$_SERVER['REQUEST_URI']))
	{
		$base_name=preg_replace('#/([a-z\-]+)\.html#isU','$1',$_SERVER['REQUEST_URI']);
		if(isset($GLOBALS['unknownlib']['site']['categories'][$base_name]))
		{
			$_GET['cat']=$base_name;
			require 'cat.php';
		}
		elseif($base_name=='login')
		{
			require 'login.php';
		}
		elseif(preg_match('#^message-[a-z\-]+$#isU',$base_name))
		{
			$mode=preg_replace('#^message-([a-z\-]+)$#isU','$1',$base_name);
			$good=false;
			if($mode=='logged')
			{
				$good=true;
				$text_main='<img src="/images/security-high.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Vous êtes loggé</span>';
			}
			elseif($mode=='login-wrong')
				$text_main='<img src="/images/security-low.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Login ou mot pass incorrect</span>';
			elseif($mode=='account-disabled')
				$text_main='<img src="/images/security-medium.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Votre compte est désactivé</span>';
			elseif($mode=='not-activated')
				$text_main='<img src="/images/security-medium.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Votre compte est désactivé</span>';
			elseif($mode=='no-image')
				$text_main='<img src="/images/security-low.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Lien de sécurité faux</span>';
			elseif($mode=='registred')
			{
				$good=true;
				$text_main='<img src="/images/security-high.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Validez votre compte avec l\'email envoyé</span>';
			}
			elseif($mode=='invalid-email')
				$text_main='<img src="/images/security-low.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Votre email n\'est pas valide</span>';
			elseif($mode=='code-antibot')
				$text_main='<img src="/images/security-low.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Le code antibox est faux</span>';
			elseif($mode=='field-empty')
				$text_main='<img src="/images/security-medium.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Une partie du formulaire est vide</span>';
			elseif($mode=='account-already-activated')
				$text_main='<img src="/images/security-medium.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Votre compte viens d\'être activé</span>';
			elseif($mode=='account-activated')
			{
				$good=true;
				$text_main='<img src="/images/security-high.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Votre compte est déjà activé</span>';
			}
			elseif($mode=='account-missing')
				$text_main='<img src="/images/security-low.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Ce compte n\'existe pas</span>';
			elseif($mode=='wrong-register-code')
				$text_main='<img src="/images/security-low.png" alt="" style="float:left" height="64px" width=64px" /> <span style="font-size:2em;">Code d\'enregistrement incorrecte</span>';
			else
				die('Missing file: '.$mode);
			if($good)
				$text_main.='<script type="text/javascript"><!--
				setTimeout(\'location.href=\\\'/\\\';\',1000);
				// -->
				</script>';
			else
				$text_main.='<script type="text/javascript"><!--
				setTimeout(\'location.href=\\\'/\\\';\',5000);
				// -->
				</script>';
			require 'message.php';
		}
		elseif($base_name=='contact-et-informations-legales')
		{
			require 'contact-et-informations-legales.php';
		}
		elseif($base_name=='register')
		{
			require 'register.php';
		}
		elseif($base_name=='top')
		{
			require 'top.php';
		}
		else
		{
			$page_found_and_generate_cache=false;
			require '404-not-found.php';
		}
	}
	else
	{
		$page_found_and_generate_cache=false;
		require '404-not-found.php';
	}
}
mysql_close();

if($GLOBALS['unknownlib']['site']['do_cache'] && $page_found_and_generate_cache)
{
	$content=ob_get_contents();
	ob_end_clean();
	echo $content;
	$content=unknownlib_text_operation_clean_html($content);
	$file=$_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'];
	if(preg_match('#/$#',$file))
		$file.='index.html';
	if(file_exists($file))
		unlink($file);
	if($filecurs=fopen($file, 'w'))
	{
		fwrite($filecurs,$content);
		fclose($filecurs);
	}
}