<?php
/// \note This file require unknownlib-function.php and config file loaded
/// \note This file have optional dependencie at the run time: unknownlib-translation.php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['cache']=true;

/// \brief at the module inclusion check the variable global
if((!isset($GLOBALS['unknownlib']['cache']['enabled']) || $GLOBALS['unknownlib']['cache']['enabled']==true) && !isset($GLOBALS['unknownlib']['cache']['rules_folder']))
{
	$GLOBALS['unknownlib']['cache']['enabled']=false;
	$GLOBALS['unknownlib']['cache']['rules_folder_final']='';
}
if(!isset($GLOBALS['unknownlib']['cache']['enabled']))
{
	$GLOBALS['unknownlib']['cache']['enabled']=false;
}
if(!isset($GLOBALS['unknownlib']['cache']['rules_folder_final']) && isset($GLOBALS['unknownlib']['cache']['enabled']) && $GLOBALS['unknownlib']['cache']['enabled'] && isset($GLOBALS['unknownlib']['cache']['rules_folder']) && $GLOBALS['unknownlib']['cache']['rules_folder']!='')
{
	$GLOBALS['unknownlib']['cache']['rules_folder_final']=unknownlib_clean_path($GLOBALS['unknownlib']['cache']['rules_folder'].'/');
	if(!is_dir($GLOBALS['unknownlib']['cache']['rules_folder_final']))
	{
		$GLOBALS['unknownlib']['cache']['rules_folder_final']='';
	}
}

/** ************************* Cache operations ***********************
\note cache management
**********************************************************************/

/// \note should be relative to the root like: /toto.php
function unknownlib_cache_php_file($file_source)
{
	//load the full path
	$final_no_cached=unknownlib_clean_path($_SERVER['DOCUMENT_ROOT'].'/'.$file_source);
	//load file rule name
	if(isset($GLOBALS['unknownlib']['cache']['rules_folder_final']) && $GLOBALS['unknownlib']['cache']['rules_folder_final']!='')
		$final_cache_rules_file=unknownlib_clean_path($GLOBALS['unknownlib']['cache']['rules_folder_final'].'/'.$file_source);
	else
		$final_cache_rules_file='';
	//if the original file not exists quit
	if(!file_exists($final_no_cached))
		return '';
	//with rules, then it's to rules to manage the cache
	elseif($final_cache_rules_file!='' && file_exists($final_cache_rules_file))
		$content=unknownlib_cache_get_generated_content($final_cache_rules_file,$final_no_cached);
	//without rules, never cache
	else
		$content = unknownlib_cache_get_generated_content($final_no_cached);
	if(isset($GLOBALS['unknownlib']['cache']['content_is_in_error']))
		unset($GLOBALS['unknownlib']['cache']['content_is_in_error']);
	return $content;
}

/// \brief get the generated content
/// \warning $file_source_or_rule and $final_no_cached should be file not dir
/// \note $final_no_cached should be relative to the root like: /toto.php
function unknownlib_cache_get_generated_content($file_source_or_rule,$file_without_cache='')
{
	if(isset($GLOBALS['unknownlib']['cache']['file_included'][$file_source_or_rule]))
		if($GLOBALS['unknownlib']['cache']['file_included'][$file_source_or_rule]>5)
			unknownlib_die_perso('Infinity loop detected for the file: '.$file_source_or_rule.' included more than 5x.');
	if(!isset($GLOBALS['unknownlib']['cache']['file_included'][$file_source_or_rule]))
		$GLOBALS['unknownlib']['cache']['file_included'][$file_source_or_rule]=1;
	else
		$GLOBALS['unknownlib']['cache']['file_included'][$file_source_or_rule]++;
	if($file_source_or_rule=='')
		unknownlib_tryhack('empty $file_source_or_rule');
	//without cache
	ob_start();
	require $file_source_or_rule;
	$content=ob_get_contents();
	ob_end_clean();// or ob_clean();
	return $content;
}

?>