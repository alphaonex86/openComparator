<?php
/// \note This file require unknownlib-function.php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['translation']=true;

/// \brief at the module inclusion check the variable global
if(!isset($GLOBALS['unknownlib']['translation']['full_language']))
{
	$GLOBALS['unknownlib']['translation']['full_language']=array('en'=>'english');
}
if(!isset($GLOBALS['unknownlib']['translation']['translation_folder']))
{
	$GLOBALS['unknownlib']['translation']['internal']['enabled']=false;
}
elseif(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$GLOBALS['unknownlib']['translation']['translation_folder']))
{
	$GLOBALS['unknownlib']['translation']['internal']['enabled']=false;
}
else
{
	$GLOBALS['unknownlib']['translation']['internal']['enabled']=true;
	$GLOBALS['unknownlib']['translation']['translation_folder_final']=unknownlib_clean_path($_SERVER['DOCUMENT_ROOT'].'/'.$GLOBALS['unknownlib']['translation']['translation_folder']);
}

//reverse the url translation
function unknownlib_translation_reverse_static_url_translation($lang='',$url='')
{
	//if lang is not set load form the default variable
	if($lang=='')
		$lang=$GLOBALS['unknownlib']['site']['current_language'];
	//if url is as default load it from current
	if($url=='')
		$url=$_SERVER['REQUEST_URI'];
	//skip if the translation is turned off
	if($GLOBALS['unknownlib']['translation']['internal']['enabled']==false || $lang=='english')
		return $url;
	//check if the right translation have been loaded
	if(!isset($GLOBALS['unknownlib']['translation']['content'][$lang]['url']))
		return false;
	//look the the request uri is set
	if(!isset($url))
		return false;
	//try load the static page
	$val=array_keys($GLOBALS['unknownlib']['translation']['content'][$lang]['url'],$url);
	if(isset($val[0]))
		return $val[0];
	else
		return false;
}

//load in multi lang for the intersite link
function unknownlib_translation_load_translation($type,$lang='')
{
	//skip if the translation is turned off
	if($GLOBALS['unknownlib']['translation']['internal']['enabled']==false)
		return;
	//if lang is not set load form the default variable
	if($lang=='')
		$lang=$GLOBALS['unknownlib']['site']['current_language'];
	//check if is not loaded
	if(isset($GLOBALS['unknownlib']['translation']['lang_type']))
		if(in_array($lang.'-'.$type,$GLOBALS['unknownlib']['translation']['lang_type']))
			return;
	//set as loaded
	if(!isset($GLOBALS['unknownlib']['translation']['lang_type']))
		$GLOBALS['unknownlib']['translation']['lang_type']=array();
	$GLOBALS['unknownlib']['translation']['lang_type'][]=$lang.'-'.$type;
	//if language is not in language list
	if(!in_array($lang,$GLOBALS['unknownlib']['translation']['full_language']))
	{
		$GLOBALS['unknownlib']['translation']['content'][$lang][$type]=array();
		return;
	}
	//check if the translation file exists
	$translation_file=unknownlib_clean_path($GLOBALS['unknownlib']['translation']['translation_folder_final'].'/'.$lang.'/'.$type.'.php');
	if(!file_exists($translation_file))
	{
		$translation_file=unknownlib_clean_path($GLOBALS['unknownlib']['translation']['translation_folder_final'].'/english/'.$type.'.php');
		if(!file_exists($translation_file))
		{
			$GLOBALS['unknownlib']['translation']['content'][$lang][$type]=array();
			return;
		}
	}
	//load the translation
	$translation=array();
	include_once $translation_file;
	//should be for example: $GLOBALS['unknownlib']['translation']['content']['english']['common']
	$GLOBALS['unknownlib']['translation']['content'][$lang][$type]=$translation;
}

function unknownlib_translation_translate($type,$text,$lang='')
{
	//if lang is not set load form the default variable
	if($lang=='')
		$lang=$GLOBALS['unknownlib']['site']['current_language'];
	//skip if the translation is turned off or the language is english
	if($GLOBALS['unknownlib']['translation']['internal']['enabled']==false)
		return $text;
	//check if the load_translation() have been call
	if(!isset($GLOBALS['unknownlib']['translation']['content']))
		return $text;
	//check if the right translation have been loaded
	if(!isset($GLOBALS['unknownlib']['translation']['content'][$lang][$type]))
		return $text;
	//load the right string, or return untranslated string if not found
	if(isset($GLOBALS['unknownlib']['translation']['content'][$lang][$type][$text]))
		return $GLOBALS['unknownlib']['translation']['content'][$lang][$type][$text];
	else
		return $text;
}

function unknownlib_translation_is_found($type,$text,$lang='')
{
	//if lang is not set load form the default variable
	if($lang=='')
		$lang=$GLOBALS['unknownlib']['site']['current_language'];
	if(isset($GLOBALS['unknownlib']['translation']['content'][$lang][$type][$text]) || $lang=='english')
		return true;
	else
		return false;
}

/// \return Return the path or '' if not found
function unknownlib_translation_file_template($root_file,$lang='')
{
	//if lang is not set load form the default variable
	if($lang=='')
		$lang=$GLOBALS['unknownlib']['site']['current_language'];
	//skip if the translation is turned off
	if($GLOBALS['unknownlib']['translation']['internal']['enabled']==false)
		return '';
	//check if the current language is set
	if(!isset($GLOBALS['unknownlib']['site']['current_language']))
		return '';
	//try resolv the path
	$path=unknownlib_clean_path($GLOBALS['unknownlib']['translation']['translation_folder_final'].'/'.$lang.'/translated-root/'.$root_file);
	if(file_exists($path))
		return $path;
	else
	{
		$path=unknownlib_clean_path($GLOBALS['unknownlib']['translation']['translation_folder_final'].'/english/translated-root/'.$root_file);
		if(file_exists($path))
			return $path;
		else
			return '';
	}
}

?>