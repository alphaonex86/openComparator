<?php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['general']=true;

/** *********************** Admin repport function ********************
**********************************************************************/

//for use unknownlib error management and email on error put this in final php file:
//set_error_handler('unknownlib_errorHandler');

function unknownlib_env()
{
	$GLOBALS_temp=$GLOBALS;
	if(isset($GLOBALS_temp['unknownlib']['ignore_debug_global']))
	{
		foreach($GLOBALS_temp['unknownlib']['ignore_debug_global'] as $ignore_var)
		{
			if(count($ignore_var)==1)
			{
				if(isset($GLOBALS_temp[$ignore_var[0]]))
					unset($GLOBALS_temp[$ignore_var[0]]);
			}
			elseif(count($ignore_var)==2)
			{
				if(isset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]]))
					unset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]]);
			}
			elseif(count($ignore_var)==3)
			{
				if(isset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]][$ignore_var[2]]))
					unset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]][$ignore_var[2]]);
			}
			elseif(count($ignore_var)==4)
			{
				if(isset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]][$ignore_var[2]][$ignore_var[3]]))
					unset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]][$ignore_var[2]][$ignore_var[3]]);
			}
			elseif(count($ignore_var)==5)
			{
				if(isset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]][$ignore_var[2]][$ignore_var[3]][$ignore_var[4]]))
					unset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]][$ignore_var[2]][$ignore_var[3]][$ignore_var[4]]);
			}
			elseif(count($ignore_var)==6)
			{
				if(isset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]][$ignore_var[2]][$ignore_var[3]][$ignore_var[4]][$ignore_var[5]]))
					unset($GLOBALS_temp[$ignore_var[0]][$ignore_var[1]][$ignore_var[2]][$ignore_var[3]][$ignore_var[4]][$ignore_var[5]]);
			}
		}
	}
	if(!isset($GLOBALS_temp['unknownlib']['debug']['path']))
		$GLOBALS_temp['unknownlib']['debug']['path']=array();
	$post='';
	if(isset($_POST))
	{
		foreach($_POST as $key => $val)
			$post.='$_POST['.unknownlib_colorize_var($key).']='.unknownlib_colorize_var($val).'<br />';
	}
	$server='';
	if(isset($_SERVER))
	{
		foreach($_SERVER as $key => $val)
			$server.='$_SERVER['.unknownlib_colorize_var($key).']='.unknownlib_colorize_var($val).'<br />';
	}
	$get='';
	if(isset($_GET))
	{
		foreach($_GET as $key => $val)
			$post.='$_GET['.unknownlib_colorize_var($key).']='.unknownlib_colorize_var($val).'<br />';
	}
	$session='';
	if(isset($_SESSION))
	{
		foreach($_SESSION as $key => $val)
			$session.='$_SESSION['.unknownlib_colorize_var($key).']='.unknownlib_colorize_var($val).'<br />';
	}
	$unknownlib='';
	if(isset($GLOBALS_temp['unknownlib']))
	{
		if(isset($GLOBALS_temp['unknownlib']['debug']) && is_array($GLOBALS_temp['unknownlib']['debug']) && count($GLOBALS_temp['unknownlib']['debug'])==0)
			unset($GLOBALS_temp['unknownlib']['debug']);
		foreach($GLOBALS_temp['unknownlib'] as $key => $val)
			$unknownlib.='$GLOBALS[\'unknownlib\']['.unknownlib_colorize_var($key).']='.unknownlib_colorize_var($val).'<br />';
	}
	$backtrace='';
	$debug=debug_backtrace();
	$die_before=false;
	foreach($debug as $line)
	{
		$bold_start='';
		$bold_stop='';
		if($line['function']!='unknownlib_mysql_query' && $die_before)
			$die_before=false;
		if($line['function']=='unknownlib_die_perso' || $line['function']=='unknownlib_tryhack')
			$die_before=true;
		if($die_before)
		{
			$bold_start='<b>';
			$bold_stop='</b>';
		}
		if(isset($line['file']))
			$backtrace.=$bold_start.$line['file'].':'.$line['line'].$bold_stop.' call '.$line['function'].'('.unknownlib_decompose_array_var_and_colorize($line['args']).')'.$bold_stop.'<br />'."\n";
		else
			$backtrace.='Call '.$line['function'].'('.unknownlib_decompose_array_var_and_colorize($line['args']).')<br />'."\n";
	}
	return 'Backtrace: <br /><div style="color:#000;border:1px solid #555;background-color:#ffeeee;padding:5px;">'.$backtrace.'</div>Details:<br /><div style="color:#000;border:1px solid #555;background-color:#d9d9f9;padding:5px;">'.$post.$session.$unknownlib.$server.$get.'</div>';
}

function unknownlib_colorize_var($var)
{
	if(is_string($var))
		return '<pre style="display:inline;"><span style="color:#008800;">\''.htmlentities($var,ENT_NOQUOTES,'UTF-8').'\'</span></pre>';
	elseif(is_array($var))
		return 'array('.unknownlib_decompose_array_var_and_colorize($var).')';
	elseif(is_object($var))
		return 'OBJECT of the class <span style="font-style:italic;">'.get_class($var).'</span>';
	elseif(is_numeric($var))
		return '<pre style="display:inline;"><span style="color:#880000;">'.$var.'</span></pre>';
	else
		return $var;
}

/// \param $tryhack is the reason of the hacking detection
function unknownlib_tryhack($tryhack)
{
	$body_text_content='Hacker detected';
	if(isset($_SESSION['account_id']))
		$body_text_content.=' with <b>the account '.htmlentities($_SESSION['account_id'],ENT_NOQUOTES,'UTF-8').'</b>';
	if(isset($_SERVER['REMOTE_ADDR']))
		$body_text_content.=' with the ip <b>'.$_SERVER['REMOTE_ADDR'].'</b>';
	$body_text_content.=', <b>'.htmlspecialchars($tryhack).'</b><br />'.unknownlib_env();
	unknownlib_send_mail('Hacker detected',$body_text_content);
	echo 'Hacking detected, administrator warned';
	exit;
}

function unknownlib_errorHandler($errno, $errstr, $errfile, $errline)
{
	switch($errno)
	{
		case E_ERROR:		$error_type='E_ERROR';			$need_die=true;		break;
		case E_WARNING:		$error_type='E_WARNING';		$need_die=false;	break;
		case E_NOTICE:		$error_type='E_NOTICE';			$need_die=false;	break;
		case E_USER_ERROR:	$error_type='E_USER_ERROR';		$need_die=true;		break;
		case E_USER_NOTICE:	$error_type='E_USER_NOTICE';		$need_die=false;	break;
		case E_CORE_WARNING:	$error_type='E_CORE_WARNING';		$need_die=false;	break;
		case E_CORE_ERROR:	$error_type='E_CORE_ERROR';		$need_die=true;		break;
		case E_COMPILE_WARNING:	$error_type='E_COMPILE_WARNING';	$need_die=false;	break;
		case E_USER_WARNING:	$error_type='E_USER_WARNING';		$need_die=false;	break;
		case E_STRICT:		$error_type='E_STRICT';			$need_die=false;	break;
		default:		$error_type='Unknow ('.$errno.')';	$need_die=true;		break;
	}
	echo $error_type;
	unknownlib_die_perso('Bug php detected with error: '.$error_type.', with error message <b>'.$errstr.'</b> in the file '.$errfile.' at the line '.$errline.' ',$need_die);
}

function unknownlib_decompose_array_var_and_colorize($arr)
{
	$final_args='';
	foreach($arr as $num=>$args)
	{
		if($final_args!='')
			$final_args.=',';
		if(is_array($args))
			$final_args.=$num.'=>array('.unknownlib_decompose_array_var_and_colorize($args).')';
		else
			$final_args.=unknownlib_colorize_var($num).'=>'.unknownlib_colorize_var($args);
	}
	return $final_args;
}

function unknownlib_die_perso($text_error,$need_die=true)
{
	if(isset($_SERVER['HTTP_HOST']))
		$title='Bug on the site '.$_SERVER['HTTP_HOST'].': '.$text_error;
	elseif(isset($_SERVER['PHP_SELF']))
		$title='Bug on the site '.$_SERVER['PHP_SELF'].': '.$text_error;
	else
		$title='Bug on the unknow part: '.$text_error;
	if(isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI']))
		$location_details='On the site: '.$_SERVER['HTTP_HOST'].'<br />On the url: '.$_SERVER['REQUEST_URI'].'<br />';
	elseif(isset($_SERVER['PHP_SELF']))
		$location_details='On file: '.$_SERVER['PHP_SELF'].'<br />';
	else
		$location_details='';
	if(isset($_SERVER['SERVER_ADDR']) && isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']==$_SERVER['SERVER_ADDR'])
		die('<div id="debug" style="color:#000;border:1px solid #555;background-color:#f9f9f9;padding:5px;">Reason: <u><b>'.$text_error.'</b></u></div><br />'.$location_details.unknownlib_env());
	if(unknownlib_send_mail($title,'<div id="debug" style="color:#000;border:1px solid #555;background-color:#f9f9f9;padding:5px;">Reason: <u><b>'.$text_error.'</b></u></div><br />'.$location_details.unknownlib_env()))
		echo 'Bug detected, administrator have been warned';
	else
		echo 'Bug detected, please contact the administrator at: '.unknownlib_protect_email($GLOBALS['unknownlib']['site']['email_admin']);
	if($need_die)
		die();
}

function unknownlib_add_to_debug_text($text,$level='',$time=-1)
{
}

function unknownlib_debug_is_enabled()
{
	return false;
}

function unknownlib_add_to_debug_show()
{
	return '';
}

function unknownlib_round_number_to_string($value,$length_in_chart=true,$length_of_number=4)
{
	return '';
}

/** ************************* General function ************************
**********************************************************************/

/*function unknownlib_round_number_to_string($value,$length_in_chart=true,$length_of_number=4)
{
	if($length_in_chart)
		$length_of_number-=1;
	if($value>=10)
		$number_int=ceil(log10($value+1));
	else
		$number_int=1;
	$number_float=$length_of_number-$number_int;
	$fix_number_float=$number_float;
	if($length_in_chart && $number_float>2)
		$number_float-=1;
	if($number_float<=0)
		return (int)$value;
	$value=round($value,$fix_number_float);
	if(strpos($value,'.') === false)
		return str_pad($value.'.',$length_of_number+1,'0');
	else
		return str_pad($value,$length_of_number+1,'0');
}*/

function unknownlib_include_the_config_files($config_folder_name='config')
{
	if(!isset($GLOBALS['unknownlib']['config']['config_file_loaded']))
	{
		$start_time=unknownlib_get_microtime();
		$include_text='';
		$dir_config=$_SERVER['DOCUMENT_ROOT'].'/'.$config_folder_name.'/';
		if(is_dir($dir_config))
		{
			if ($dh = opendir($dir_config))
			{
				while (($file = readdir($dh)) !== false)
					if($file!='.' && $file!='..')
					{
						require unknownlib_clean_path($dir_config.$file);
						if($include_text!='')
							$include_text.='<br />'."\n";
						$include_text.='<span style="color:#bbbb66;font-size:small;">include_once "<span style="font-style:italic;">'.addslashes(unknownlib_clean_path($dir_config.$file)).'</span>";</span>';
					}
				closedir($dh);
			}
		}
		unknownlib_check_unknownlib_config();
		$GLOBALS['unknownlib']['config']['config_file_loaded']=true;
	}
}

function unknownlib_get_microtime()
{
	return microtime(true)*1000;
}

function unknownlib_clean_path($path)
{
	return preg_replace('#/+#', '/', $path);
}

function unknownlib_fileopen($file)
{
	if(file_exists($file) && $filecurs=file_get_contents($file))
		return $filecurs;
	else
		return '';
}

function unknownlib_urlopen($url)
{
	$content='';
	if($filecurs=fopen($url, 'r'))
	{
		while($temp=fread($filecurs,4096))
			$content.=$temp;
		fclose($filecurs);
		return $content;
	}
	else
		return '';
}

function unknownlib_filewrite($file,$content)
{
	if($filecurs=fopen($file, 'w'))
	{
		if(fwrite($filecurs,$content) === false)
			unknownlib_die_perso('Unable to write the file: '.$file);
		fclose($filecurs);
	}
	else
		unknownlib_die_perso('Unable to write or create the file: '.$file);
}

function unknownlib_text_with_number($text,$number)
{
	if($number==0)
		return '0 '.$text;
	elseif($number>1)
		return $number.' '.$text.'s';
	else
		return $number.' '.$text;
}

//convert content to js
function unknownlib_convert_source_to_js($source)
{
	$source=str_replace('\'','\\\'',$source);
	$source=str_replace("\n",'',$source);
	$source=str_replace("\r",'',$source);
	$source=str_replace("\t",'',$source);
	return $source;
}

//check the unknownlib config, and load the default value if needed
function unknownlib_check_unknownlib_config()
{
	/// \brief at the module inclusion check the variable global
	//load the global variable
	if(!isset($GLOBALS['unknownlib']['site']['email_admin']))
	{
		if(!isset($_SERVER['SERVER_ADMIN']))
			$_SERVER['SERVER_ADMIN']='admin@barnix.net';
		if(isset($_SERVER['SERVER_ADMIN']))
			$GLOBALS['unknownlib']['site']['email_admin']=$_SERVER['SERVER_ADMIN'];
		else
			$GLOBALS['unknownlib']['site']['email_admin']='admin@barnix.net';
	}
	if(!isset($GLOBALS['unknownlib']['site']['email_admin_name']))
		$GLOBALS['unknownlib']['site']['email_admin_name']='';
}

function unknownlib_file_size($file,$unit_letter='B')
{
	if($file=='')
		return '0K'.$unit_letter;
	if(@filesize($file)===FALSE)
		return '???K'.$unit_letter;
	@$size=filesize($file);
	$unit=0;
	while($size>1024 && $unit<5)
	{
		$size/=1024;
		$unit++;
	}
	$arr=array($unit_letter,'K'.$unit_letter,'M'.$unit_letter,'G'.$unit_letter,'T'.$unit_letter);
	if($size<10)
		return round($size,1).$arr[$unit];
	else
		return ceil($size).$arr[$unit];
}

function unknownlib_create_base_dir($file)
{
	$dir=dirname($file);
	if(!is_dir($dir))
		if(!@mkdir($dir,0777,true))
			return false;
	return true;
}

function unknownlib_random_password($lenght=16,$char_user='abcdefghijkmnopqrstuvwxyz023456789AZERTYUIOPMLKJHGFDSQWXCVBN')
{
	$pass='';
	$i=0;
	while($i<$lenght)
	{
		$pass.=substr($char_user,rand(0,strlen($char_user)),1);
		$i++;
	}
	return $pass;
} 

function unknownlib_date_month_fr($timestamps=-1)
{
	if($timestamps==-1)
		$timestamps=time();
	$date=(int)date('n',$timestamps);
	if($date==1)
		return 'Janvier';
	elseif($date==2)
		return 'Février';
	elseif($date==3)
		return 'Mars';
	elseif($date==4)
		return 'Avril';
	elseif($date==5)
		return 'Mai';
	elseif($date==6)
		return 'Juin';
	elseif($date==7)
		return 'Juillet';
	elseif($date==8)
		return 'Aout';
	elseif($date==9)
		return 'Septembre';
	elseif($date==10)
		return 'Octobre';
	elseif($date==10)
		return 'Novembre';
	else
		return 'Décembre';
}

/** ************************** Input function *************************
**********************************************************************/


function unknownlib_add_slashes($content)
{
	return addslashes($content);
}

function unknownlib_strip_slashes($content)
{
	return stripslashes($content);
}

function unknownlib_clean_slashes_input()
{
	foreach($_GET as $var => $value)
		$_GET[$var]=unknownlib_strip_slashes($value);
	foreach($_POST as $var => $value)
		$_POST[$var]=unknownlib_strip_slashes($value);
}

/** \brief return array with error message, the array is empty if no error, check only hacking error
The both array should have the same size
*/
function unknownlib_check_input_hacking($input,$var_to_check,$full_var_name=array())
{
	return unknownlib_check_input($input,$var_to_check,$full_var_name,true,false);
}

/** \brief return array with error message, the array is empty if no error, check only input error
The both array should have the same size
*/
function unknownlib_check_input_error($input,$var_to_check,$full_var_name=array())
{
	return unknownlib_check_input($input,$var_to_check,$full_var_name,false,true);
}

/** \brief return array with error message, the array is empty if no error
The both array should have the same size
\param $input $_GET or $_POST
\param $var_to_check array('id'=>'uint not null')
*/
function unknownlib_check_input($input,$var_to_check,$full_var_name=array(),$check_hacking=true,$check_error_input=true)
{
	if($check_hacking==false && $check_error_input==false)
		unknownlib_die_perso('Wrong using of the function unknownlib_check_input(), $check_hacking and $check_error_input can\'t be both at false');
	$array_to_return=array();
	foreach($var_to_check as $var => $type)
	{
		if(isset($full_var_name[$var]))
			$the_first_string='The '.$full_var_name[$var].' ';
		else
			$the_first_string='The variable "'.$var.'" ';
		if(!isset($input[$var]))
		{
			if($check_hacking)
				$array_to_return[$var]=$the_first_string.'is not set';
		}
		else
		{
			if($check_hacking)
			{
				if(is_array($type))
				{
					if(!in_array($input[$var],$type))
						$array_to_return[$var]=$the_first_string.'have not allowed content';
				}
			}
			if($check_error_input)
			{
				if(!is_array($type))
				{
					switch($type)
					{
						case 'int':
							if(!preg_match('#^-?[0-9]*$#',$input[$var]))
								$array_to_return[$var]=$the_first_string.'is not a int';
						break;
						case 'uint':
							if(!preg_match('#^[0-9]*$#',$input[$var]))
								$array_to_return[$var]=$the_first_string.'is not a usigned int';
						break;
						case 'int not null':
							if(!preg_match('#^-?[1-9][0-9]*$#',$input[$var]))
								$array_to_return[$var]=$the_first_string.'is not a int not null';
						break;
						case 'uint not null':
							if(!preg_match('#^[1-9][0-9]*$#',$input[$var]))
								$array_to_return[$var]=$the_first_string.'is not a usigned int not null';
						break;
						case 'bool':
							if($input[$var]!='0' && $input[$var]!='1')
								$array_to_return[$var]=$the_first_string.'is not a boolean';
						break;
						case '*':
						break;
						case 'email':
							if(!preg_match('#^[a-z0-9\.\-_]+@[a-z0-9\.\-_]+\.[a-z]{2,4}$#',$input[$var]))
								$array_to_return[$var]=$the_first_string.'is not a email';
						break;
						case 'email full':
							if(!preg_match('#^[A-Z ]{5,30} [a-z0-9\.\-_]+@[a-z0-9\.\-_]+\.[a-z]{2,4}$#',$input[$var]))
								$array_to_return[$var]=$the_first_string.'is not a email';
						break;
						default:	unknownlib_die_perso('Type: '.$type.' unknow in type verification');
					}
				}
			}
		}
	}
	return $array_to_return;
}

function unknownlib_string_to_json($text)
{
	return str_replace("\r",'\\r',str_replace("\t",'\\t',str_replace("\n",'\\n',str_replace('"','\\"',str_replace('\\','\\\\',$text)))));
}

function unknownlib_array_to_json($obj)
{
	if(is_array($obj))
	{
		$code = array();
		if(array_keys($obj)!==range(0,count($obj)-1))
		{
			foreach($obj as $key=>$val)
				$code []= '"'.unknownlib_string_to_json($key).'":'.unknownlib_array_to_json($val);
			$code ='{'.implode(',',$code).'}';
		}
		else
		{
			foreach($obj as $val)
				$code[]=unknownlib_array_to_json($val);
			$code='['.implode(',',$code).']';
		}
		return $code;
	}
	else
		return '"'.unknownlib_string_to_json($obj).'"';
}

/// \warning only file can by send here, then all string except the string end with /
function unknownlib_create_base_dir_if_not_exists($file)
{
	$base_dir=dirname($file);
	if(file_exists($base_dir))
	{
		if(!is_dir($base_dir))
			unknownlib_die_perso('try create folder but file with same name exists');
		else
			return true;
	}
	else
		return mkdir($base_dir,0777,true);
}

/** ************************ Mailing function *************************
**********************************************************************/

function unknownlib_send_mail($title,$text,$to='',$type='',$from='')
{
	if($to=='')
	{
		if($type=='')
			$type='text/html';
		if(isset($GLOBALS['unknownlib']['site']['email_admin_name']) && $GLOBALS['unknownlib']['site']['email_admin_name']!='')
			$to=$GLOBALS['unknownlib']['site']['email_admin_name'].' <'.$GLOBALS['unknownlib']['site']['email_admin'].'>';
		else
			$to=$GLOBALS['unknownlib']['site']['email_admin'];
		if(isset($_SERVER['HTTP_HOST']))
			$title='[unknownlib]['.$_SERVER['HTTP_HOST'].'] '.$title;
		else
			$title='[unknownlib] '.$title;
	}
	if($type=='')
		$type='text/plain';
	if($from=='' || count(unknownlib_check_input_error(array('email'=>$from),array('email','email full')))>0)
	{
		if(strlen($GLOBALS['unknownlib']['site']['email_admin_name'])>0)
			$from = $GLOBALS['unknownlib']['site']['email_admin_name'].' <'.$GLOBALS['unknownlib']['site']['email_admin'].'>';
		else
			$from = $GLOBALS['unknownlib']['site']['email_admin'];
	}
	$headers = 'From: '.$from."\r\n";
	$headers .= 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: '.$type.'; charset=UTF-8'."\r\n";
	$return=@mail($to,'=?UTF-8?B?'.base64_encode($title).'?=',$text,$headers);
	return $return;
}

function unknownlib_protect_email($email)
{
	$email=str_replace('@','<span style="font-style:italic;"></span>&#64;<b></b>',$email);
	$email='<script type="text/javascript">'."\n".'<!--'."\n".'document.write("'.addslashes($email).'");'."\n".'-->'."\n".'</script>';
}

/** ************************** Bots function **************************
**********************************************************************/

function unknownlib_is_bot($nav='')
{
	if($nav=='' && isset($_SERVER['HTTP_USER_AGENT']))
	{
		$current_connexion_check=true;
		$nav=$_SERVER['HTTP_USER_AGENT'];
	}
	else
		$current_connexion_check=false;
	if(preg_match('#(bot|google|crawl)#i',$nav))
		return true;
	if($current_connexion_check)
	{
		if(isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL']!='HTTP/1.1')
			return true;
	}
	return false;
}

?>