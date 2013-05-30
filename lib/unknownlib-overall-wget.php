<?php
/** \note Need include before the unknownlib-function.php **/
require_once 'unknownlib-function.php';
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['overall-wget']=true;

/** *************************** Ip ban function *********************** 
\note require table ipban with: id (int, primary auto increment), ip (var char 64), failcount (int), lastfail (int)
**********************************************************************/

function unknownlib_overall_wget_change_db()
{
	$reply_db=unknownlib_mysql_query('SELECT DATABASE()');
	if(!$data_db=mysql_fetch_array($reply_db))
		unknownlib_die_perso('database return error');
	if(!mysql_select_db('other_overwall_wget'))
		unknownlib_die_perso('wget database is not accessible');
	return $data_db[0];
}

function unknownlib_overall_wget_reverse_db($old_db)
{
	if(!mysql_select_db($old_db))
		unknownlib_die_perso('unable to restore the old database');
}

/// \brief add rss to pool
function unknownlib_overall_wget_add_rss($url,$database,$table,$max_entry,$id_to_record,$pre_parser='')
{
	$old_db=unknownlib_overall_wget_change_db();
	unknownlib_mysql_query('INSERT LOW_PRIORITY INTO `rss`(`url`,`database`,`table`,`max_entry`,`id_to_record`,`pre_parser`) VALUES(\''.addslashes($url).'\',\''.addslashes($database).'\',\''.addslashes($table).'\','.addslashes($max_entry).','.addslashes($id_to_record).',\''.addslashes($pre_parser).'\')');
	unknownlib_overall_wget_reverse_db($old_db);
}

/// \brief add thumb to download
function unknownlib_overall_wget_add_thumb($url,$destination_path,$width,$height,$jpeg_quality=95)
{
	$old_db=unknownlib_overall_wget_change_db();
	unknownlib_overall_wget_check_resolution('miwim',$width,$height);
	unknownlib_mysql_query('INSERT LOW_PRIORITY INTO `ascreen`(`url`,`destination_path`,`width`,`height`,`jpeg_quality`) VALUES(\''.addslashes($url).'\',\''.addslashes($destination_path).'\','.addslashes($width).','.addslashes($height).','.addslashes($jpeg_quality).')');
	unknownlib_overall_wget_reverse_db($old_db);
}

/// \brief add rss to pool
function unknownlib_overall_wget_remove_rss($url,$database,$table,$id_to_record)
{
	$old_db=unknownlib_overall_wget_change_db();
	$reply_rss=unknownlib_mysql_query('SELECT * FROM `rss` WHERE `url`=\''.addslashes($url).'\' AND `database`=\''.addslashes($database).'\' AND `table`=\''.addslashes($table).'\' AND `id_to_record`=\''.addslashes($id_to_record).'\'');
	unknownlib_mysql_query('DELETE LOW_PRIORITY FROM `rss` WHERE `url`=\''.addslashes($url).'\' AND `database`=\''.addslashes($database).'\' AND `table`=\''.addslashes($table).'\' AND `id_to_record`=\''.addslashes($id_to_record).'\'');
	while($data_rss=mysql_fetch_array($reply_rss))
	{
		if(!mysql_select_db($data_rss['database']))
			unknownlib_die_perso('unable connect to the new database: '.htmlspecialchars($data_rss['database']));
		else
			unknownlib_mysql_query('DELETE LOW_PRIORITY FROM `'.$data_rss['table'].'` WHERE `id_rss`=\''.addslashes($data_rss['id_to_record']).'\'');
	}
	unknownlib_overall_wget_reverse_db($old_db);
}

/// \brief add thumb to download
function unknownlib_overall_wget_remove_thumb($url,$destination_path)
{
	$old_db=unknownlib_overall_wget_change_db();
	$reply_thumb=unknownlib_mysql_query('SELECT * FROM `ascreen` WHERE `url`=\''.addslashes($url).'\'');
	if(mysql_num_rows($reply_thumb)==1)
	{
		$data_thumb=mysql_fetch_array($reply_thumb);
		$local_thumb_url=$GLOBALS['unknownlib']['overall-wget-temp-dir']['top_base_dir'].'/'.md5($data_thumb['url']).'-'.$data_thumb['width'].'x'.$data_thumb['height'].'.jpg';
		if(file_exists($local_thumb_url) && !unlink($local_thumb_url))
			unknownlib_die_perso('unable to remove the global thumb because is the last');
	}
	if(file_exists($destination_path) && !unlink($destination_path))
		unknownlib_die_perso('unable to remove the local thumb');
	unknownlib_mysql_query('DELETE LOW_PRIORITY FROM `ascreen` WHERE `url`=\''.addslashes($url).'\' AND `destination_path`=\''.addslashes($destination_path).'\'');
	unknownlib_overall_wget_reverse_db($old_db);
}

/// \note This fonction require unknownlib-rss.php
function unknownlib_overall_wget_load_rss($host='',$login='',$pass='',$id='',$mysql_link=-1)
{
	if($host=='')
		$host=$GLOBALS['unknownlib']['mysql']['host'];
	if($login=='')
		$login=$GLOBALS['unknownlib']['mysql']['login'];
	if($pass=='')
		$pass=$GLOBALS['unknownlib']['mysql']['pass'];
	$last_rss='';
	if($id=='')
		$reply_rss=unknownlib_mysql_query('SELECT * FROM `rss` ORDER BY `url`',$mysql_link);
	else
		$reply_rss=unknownlib_mysql_query('SELECT * FROM `rss` ORDER BY `url` WHERE `id`='.addslashes($id),$mysql_link);
	while($data_rss=mysql_fetch_array($reply_rss))
	{
		echo $data_rss['url']."\n";
		$date_offset=0;
		if($data_rss['last_id_parsed']=='')
			$first_import='1';
		else
			$first_import='0';
		if($last_rss!=$data_rss['url'])
		{
			$content=unknownlib_urlopen($data_rss['url']);
			switch($data_rss['pre_parser'])
			{
				case 'sponsor-vador':
					$content=unknownlib_trade_sponsor_pre_rss('vador',$content);
				break;
			}
			if($content=='')
			{
				unknownlib_send_mail('Unable to get rss','url not parsed:'.$data_rss['url']);
				continue;
			}
			echo 'parse xml'."\n";
			$content_parsed=unknownlib_rss_parse_xml($content);
			$last_rss=$data_rss['url'];
		}
		else
			echo '$last_rss!=$data_rss[\'url\']'."\n";
		echo 'Have '.count($content_parsed).' entries'."\n";
		if(count($content_parsed)>0 && isset($content_parsed[0]['guid']))
		{
			if($data_rss['last_id_parsed']!=$content_parsed[0]['guid'])
				unknownlib_mysql_query('UPDATE LOW_PRIORITY `rss` SET `last_id_parsed`=\''.addslashes($content_parsed[0]['guid']).'\' WHERE `id`='.addslashes($data_rss['id']),$mysql_link);
		}
		else
			unknownlib_mysql_query('UPDATE LOW_PRIORITY `rss` SET `last_id_parsed`=\'-1\' WHERE `id`='.addslashes($data_rss['id']),$mysql_link);
		$content_filtred=array();
		//filter array
		foreach($content_parsed as $key)
		{
			if(isset($key['guid']) && $key['guid']==$data_rss['last_id_parsed'])
				break;
			else
				$content_filtred[]=$key;
		}
		echo 'Have '.count($content_filtred).' new entries'."\n";
		//insert into mysql
		$link_remote=unknownlib_mysql_connect($GLOBALS['unknownlib']['mysql']['host'],$GLOBALS['unknownlib']['mysql']['login'],$GLOBALS['unknownlib']['mysql']['pass'],$data_rss['database']);
		if($link_remote!==false)
		{
			if(count($content_filtred)>0)
			{
				foreach($content_filtred as $line)
				{
					if(isset($line['guid'])  && $line['guid']==$data_rss['last_id_parsed'] && $data_rss['last_id_parsed']!='-1')
						break;
					unknownlib_mysql_query('INSERT LOW_PRIORITY `'.addslashes($data_rss['table']).'`(`id_rss`,`date`,`content`,`first_import`) VALUES('.addslashes($data_rss['id_to_record']).','.(time()-$date_offset).',\''.addslashes(serialize($line)).'\','.$first_import.')',$link_remote);
					$date_offset--;
				}
			}
			$reply_number_entry=unknownlib_mysql_query('SELECT COUNT(`date`) as `number_rows` FROM  `'.addslashes($data_rss['table']).'` WHERE `id_rss`='.addslashes($data_rss['id_to_record']),$link_remote);
			$data_number_entry=mysql_fetch_array($reply_number_entry);
			if($data_rss['max_entry']>0)
			{
				$number_to_remove=$data_number_entry['number_rows']-$data_rss['max_entry'];
				if($number_to_remove<0)
					$number_to_remove=0;
				if($number_to_remove>0)
				{
					echo 'Delete overflow of '.$number_to_remove.' entries'."\n";
					unknownlib_mysql_query('DELETE LOW_PRIORITY FROM `'.addslashes($data_rss['table']).'` WHERE `id_rss`='.addslashes($data_rss['id_to_record']).' ORDER BY `date` ASC LIMIT '.$number_to_remove,$link_remote);
				}
			}
			mysql_close($link_remote);
		}
	}
}

function unknownlib_overall_wget_load_thumb($mysql_link=-1)
{
	if(!isset($GLOBALS['unknownlib']['overall-wget-temp-dir']['top_base_dir']))
		unknownlib_die_perso('unable to found the right variable');
	$reply_thumb=unknownlib_mysql_query('SELECT * FROM `ascreen` WHERE `is_downloaded`=0 ORDER BY `url`',$mysql_link);
	while($data_thumb=mysql_fetch_array($reply_thumb))
	{
		$local_thumb_url=$GLOBALS['unknownlib']['overall-wget-temp-dir']['top_base_dir'].'/'.md5($data_thumb['url']).'-'.$data_thumb['width'].'x'.$data_thumb['height'].'.jpg';
		if(!file_exists($local_thumb_url))
		{
			if(!unknownlib_overall_wget_load_thumb_service_miwim($data_thumb['url'],$data_thumb['width'],$data_thumb['height'],$local_thumb_url))
				unknownlib_die_perso('thumbs.miwim.fr down');
			elseif(filesize($local_thumb_url)<=0)
				unknownlib_die_perso('file size is null');
			elseif(md5_file($local_thumb_url)=='06b7db6841f52158cb62c8e8273f99de')
			{
				if(!unlink($local_thumb_url))
					unknownlib_die_perso('unable to remove the thumb');
			}
		}
		if(file_exists($local_thumb_url) && !file_exists($data_thumb['destination_path']))
		{
			if(!copy($local_thumb_url,$data_thumb['destination_path']))
				unknownlib_die_perso('unable to copy the thumb');
		}
		if(file_exists($data_thumb['destination_path']))
			unknownlib_mysql_query('UPDATE `ascreen` SET `is_downloaded`=1 WHERE `id`='.addslashes($data_thumb['id']),$mysql_link);
	}
}

function unknownlib_overall_wget_load_thumb_service($url,$width,$height,$destination)
{
	unknownlib_overall_wget_load_thumb_service_miwim($url,$width,$height,$destination);
}

function unknownlib_overall_wget_load_thumb_service_miwim($url,$width,$height,$destination)
{
	unknownlib_overall_wget_check_resolution('miwim',$width,$height);
	return copy('http://thumbs.miwim.fr/img.php?url='.htmlspecialchars($url).'&size='.$width.'x'.$height.'&remplace=http://www.barnix.net/img/nepastoucher.jpg',$destination);
}

function unknownlib_overall_wget_check_resolution($name,$width,$height)
{
	switch($name)
	{
		case 'miwim':
			if(($width==80 && $height==60))
				return true;
			elseif(($width==100 && $height==75))
				return true;
			elseif(($width==120 && $height==90))
				return true;
			elseif(($width==160 && $height==120))
				return true;
			elseif(($width==180 && $height==135))
				return true;
			elseif(($width==240 && $height==180))
				return true;
			elseif(($width==320 && $height==240))
				return true;
			elseif(($width==560 && $height==420))
				return true;
			elseif(($width==640 && $height==480))
				return true;
			elseif(($width==800 && $height==600))
				return true;
		break;
	}
	unknownlib_die_perso('this resolution is not compatible with this screenshot service ('.htmlspecialchars($name).')');
}

?>