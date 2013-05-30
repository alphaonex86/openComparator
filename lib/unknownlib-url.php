<?php
/// \note This file require unknownlib-function.php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['url']=true;

/** ************************* Url completition ************************
**********************************************************************/

function unknownlib_url_add_www_if_needed($host)
{
	if(substr_count($host,'.')==1)
		return 'www.'.$host;
	else
		return $host;
}

function unknownlib_url_current_protocol()
{
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')
		return 'https';
	else
		return 'http';
}

function unknownlib_url_current_port()
{
	if(!isset($_SERVER['SERVER_PORT']))
		return '';
	$current_protocol=unknownlib_url_current_protocol();
	if(($current_protocol=='https' && $_SERVER['SERVER_PORT']==443) || ($current_protocol=='http' && $_SERVER['SERVER_PORT']==80))
		return '';
	else
		return ':'.$_SERVER['SERVER_PORT'];
}

/** ************************** Url general op *************************
**********************************************************************/

/// \note return url domain or empty if not able to do
function unknownlib_url_get_domain($url)
{
	if(preg_match('#^https?://([a-z0-9\.\-_]{5,})(:[0-9]{1,5})?(/|/[a-z0-9\.\-_/]+)?$#',$url))
		return preg_replace('#^https?://([a-z0-9\.\-_]{5,})(:[0-9]{1,5})?(/|/[a-z0-9\.\-_/]+)?$#','$1',$url);
	else
		return '';
}

/// \note this function require unknownlib-text-operation.php
function unknownlib_url_remake_for_url($text)
{
	$url=unknownlib_text_operation_clean_text($text);
	$url=str_replace(' ','-',$url);
	$url=preg_replace('#-+#','-',$url);
	$url=preg_replace('#-+$#','',$url);
	$url=preg_replace('#^-+#','',$url);
	return $url;
}

function get_file_name($url)
{
	if(preg_match('#^.*/[^/]+$#',$url))
		return preg_replace('#^.*/([^/]+)$#','$1',$url);
	else
		return $url;
}

/** ************************ Url alias for SEO ************************
\note This file require the col in the mysql/other table: url_alias_for_seo (var char 64) and id
**********************************************************************/

/// \param $text_duplicate should be only letter
/// \note need unknownlib-text-operation.php for this function
function unknownlib_url_load_alias_for_mysql($table,$col,$text_duplicate='number',$where_clause='',$minimum_word_length=4,$minimum_string_length=15,$maximum_string_length=64,$exclude_word=array(),$mysql_link=-1)
{
	//load the original value
	if($where_clause!='')
		$reply=unknownlib_mysql_query('SELECT `id`,`'.$col.'` FROM `'.$table.'` WHERE '.$where_clause,$mysql_link);
	else
		$reply=unknownlib_mysql_query('SELECT `id`,`'.$col.'` FROM `'.$table.'`',$mysql_link);
	while($data=mysql_fetch_array($reply))
		unknownlib_url_load_alias_of_item_mysql($table,$data['id'],$data[$col],$text_duplicate,$where_clause,$minimum_word_length,$minimum_string_length,$maximum_string_length,$exclude_word,$mysql_link);
}

/// \note need unknownlib-text-operation.php for this function
/// \note this do type for toto-tata or if colision toto-tata-number-2
function unknownlib_url_load_alias_of_item_mysql($table,$id,$text,$text_duplicate='number',$where_clause='',$minimum_word_length=4,$minimum_string_length=15,$maximum_string_length=64,$exclude_word=array(),$mysql_link=-1)
{
	if(!function_exists('unknownlib_text_operation_do_for_url'))
		unknownlib_die_perso('unknownlib_text_operation_do_for_url() is not set!, Missing unknownlib-text-operation.php');
	//clean it
	$text=unknownlib_text_operation_do_for_url($text,$minimum_word_length,$minimum_string_length,$maximum_string_length);
	//echo 'url calculated 1) '.$text;
	//check if duplicate
	if($where_clause!='')
		$mysql_query='SELECT `url_alias_for_seo` FROM `'.$table.'` WHERE `url_alias_for_seo`=\''.addslashes($text).'\' AND `id`!='.$id.' AND ('.$where_clause.')';
	else
		$mysql_query='SELECT `url_alias_for_seo` FROM `'.$table.'` WHERE `url_alias_for_seo`=\''.addslashes($text).'\' AND `id`!='.$id;
	$reply2=unknownlib_mysql_query($mysql_query,$mysql_link);
	while(mysql_num_rows($reply2)>0 || in_array($text,$exclude_word))
	{
		//echo 'found with this query: '.$mysql_query;
		if(preg_match('#'.preg_quote($text_duplicate).'-[0-9]+$#i',$text))
		{
			//increment the actual number
			$number=preg_replace('#^.*'.preg_quote($text_duplicate).'-([0-9]+)$#i','$1',$text);
			$number++;
			$text=preg_replace('#^(.*'.preg_quote($text_duplicate).')-([0-9]+)$#i','$1-'.$number,$text);
		}
		else //set the actual number to 2
			$text=$text.'-'.$text_duplicate.'-2';
		//echo 'url calculated 2) '.$text;
		if($where_clause!='')
			$mysql_query='SELECT `url_alias_for_seo` FROM `'.$table.'` WHERE `url_alias_for_seo`=\''.addslashes($text).'\' AND `id`!='.$id.' AND ('.$where_clause.')';
		else
			$mysql_query='SELECT `url_alias_for_seo` FROM `'.$table.'` WHERE `url_alias_for_seo`=\''.addslashes($text).'\' AND `id`!='.$id;
		$reply2=unknownlib_mysql_query($mysql_query,$mysql_link);
	}
	unknownlib_mysql_query('UPDATE LOW_PRIORITY `'.$table.'` SET `url_alias_for_seo`=\''.addslashes($text).'\' WHERE `id`='.$id,$mysql_link);
	return $text;
}

/** \brief should return unique element or empty array if not found
Where close should be with quote **/
function unknownlib_url_load_item_by_alias_mysql($table,$text,$where_clause='',$mysql_link=-1)
{
	if($where_clause=='')
		$reply=unknownlib_mysql_query('SELECT * FROM `'.$table.'` WHERE `url_alias_for_seo`=\''.addslashes($text).'\'',$mysql_link);
	else
		$reply=unknownlib_mysql_query('SELECT * FROM `'.$table.'` WHERE `url_alias_for_seo`=\''.addslashes($text).'\' AND '.$where_clause,$mysql_link);
	if(mysql_num_rows($reply))
	{
		$data=mysql_fetch_array($reply);
		return $data;
	}
	else
		return array();
}

?>