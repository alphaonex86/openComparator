<?php
/** \note need unknownlib-function.php for error management */
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['mysql']=true;
if(!isset($GLOBALS['unknownlib']['mysql']['prefix']))
	$GLOBALS['unknownlib']['mysql']['prefix']='';

/// \brief connect to mysql data base
function unknownlib_mysql_connect($mysql_host='',$mysql_login='',$mysql_pass='',$mysql_db='')
{
	if($mysql_host=='' || $mysql_login=='' || $mysql_db=='')
	{
		if(isset($GLOBALS['unknownlib']['mysql']['host']) && isset($GLOBALS['unknownlib']['mysql']['login']) && isset($GLOBALS['unknownlib']['mysql']['pass']) && isset($GLOBALS['unknownlib']['mysql']['db']))
		{
			$link=mysql_connect($GLOBALS['unknownlib']['mysql']['host'],$GLOBALS['unknownlib']['mysql']['login'],$GLOBALS['unknownlib']['mysql']['pass'],true);
			$mysql_db=$GLOBALS['unknownlib']['mysql']['db'];
		}
		else
			return false;
	}
	else
		$link=mysql_connect($mysql_host,$mysql_login,$mysql_pass,true);
	if(!$link)
		return false;
	if(!mysql_select_db($mysql_db,$link))
		return false;
	return $link;
}

/// \brief do mysql via unknownlib
function unknownlib_mysql_query($mysql_query,$mysql_link=-1)
{
	if($mysql_link==-1)
		$return_var = mysql_query($mysql_query) or unknownlib_die_perso('Bug detect on mysql: '.mysql_error());
	else
		$return_var = mysql_query($mysql_query,$mysql_link) or unknownlib_die_perso('Bug detect on mysql: '.mysql_error($mysql_link));
	return $return_var;
}

/// \brief try connection or quit
function unknownlib_mysql_connect_or_quit($mysql_host='',$mysql_login='',$mysql_pass='',$mysql_db='',$send_email=false)
{
	$link=unknownlib_mysql_connect($mysql_host,$mysql_login,$mysql_pass,$mysql_db);
	if(!$link)
	{
		if($send_email)
			unknownlib_die_perso('Unable to connect to mysql: '.$GLOBALS['unknownlib']['mysql']['error']);
		else
			die('Mysql error at connect, quit.');
	}
	return $link;
}

/// \brief disconnect to mysql
function unknownlib_mysql_close($link)
{
	if($link!=false)
		mysql_close($link);
	else
		mysql_close();
}

/// \brief return loaded object or empty array if not found
function unknownlib_mysql_load_object_by_id($table,$id)
{
	if(!preg_match('#^[1-9][0-9]*$#',$id))
		unknownlib_die_perso('the id passed in arguement is not a unsigned int');
	$reply = unknownlib_mysql_query('SELECT * FROM `'.addslashes($table).'` WHERE id='.$id);
	if($data = mysql_fetch_array($reply))
		return $data;
	else
		return array();
}

/** \note This function require logs_mail: title (text), text (text), to (text), type (text), from (text), reason (text), other_informations (text) */
function unknownlib_send_mail_and_log_it($title,$text,$to='',$type='',$from='',$reason='',$other_informations='',$mysql_link=-1)
{
	if($to=='')
		$to_mysql='[administrator]';
	else
		$to_mysql=$to;
	if($type=='')
	{
		if($to=='')
			$type_mysql='text/html';
		else
			$type_mysql='text/plain';
	}
	else
		$type_mysql=$type;
	if($from=='')
		$from_mysql='[administrator]';
	else
		$from_mysql=$from;
	unknownlib_mysql_query('INSERT DELAYED INTO `logs_mail`(`title`,`text`,`to`,`type`,`from`,`reason`,`other_informations`,`time`) VALUES(\''.addslashes($title).'\',\''.addslashes($text).'\',\''.addslashes($to_mysql).'\',\''.addslashes($type_mysql).'\',\''.addslashes($from_mysql).'\',\''.addslashes($reason).'\',\''.addslashes($other_informations).'\','.time().')',$mysql_link);
	return unknownlib_send_mail($title,$text,$to,$type,$from);
}

function unknownlib_mysql_clean_data_return($data,$remove_var=array())
{
	$new_data=array();
	foreach($data as $key => $val)
		if(!preg_match('#^[0-9]+$#',$key) && !in_array($key,$remove_var))
			$new_data[$key]=$val;
	return $new_data;
}