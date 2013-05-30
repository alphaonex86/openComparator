<?php
/// \note This file require unknownlib-function.php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['rss']=true;

function unknownlib_rss_parse_xml($content,$lastIdParsed='',$max_rss_item=120)
{
	$content = preg_replace('#<!--.*-->#sU', '', $content);
	preg_match_all('#<item( rdf[^>]+)?>(.+)</item>#sU',$content,$items);
	$temp_array=array();
	foreach($items[2] as $temp_item)
	{
		if(count($temp_array)>$max_rss_item)
			break;
		$ary[] = 'UTF-8';
		$ary[] = 'ISO-8859-1';
		$ary[] = 'ISO-8859-15';
		$ary[] = 'ASCII';
		$ary[] = 'JIS';
		$ary[] = 'EUC-JP';
		$mb_detect_encoding=mb_detect_encoding($temp_item,$ary,true);
		$temp_item=mb_convert_encoding($temp_item,'UTF-8',$mb_detect_encoding);
		$temp_item=str_replace('','\'',$temp_item);
		$temp_item=preg_replace('/&#[0-9]{1,4};/',' ',$temp_item);
		$balise_loaded=array('guid'=>array('guid','id'),'link'=>array('link','lien'),'title'=>array('title','titre'),'description'=>array('description'));
		$sub_items_array=array();
		foreach($balise_loaded as $key => $sub_keys)
		{
			foreach($sub_keys as $current_sub_keys)
			{
				preg_match_all('#<'.str_replace('#','\\#',preg_quote($current_sub_keys)).'( .+)?>(<!\[CDATA\[)?(.+)(\]\]>)?</'.str_replace('#','\\#',preg_quote($current_sub_keys)).'>#isU',$temp_item,$sub_items);
				$temp_item = preg_replace('#<'.str_replace('#','\\#',preg_quote($current_sub_keys)).'( .+)?>(<!\[CDATA\[)?(.+)(\]\]>)?</'.str_replace('#','\\#',preg_quote($current_sub_keys)).'>#isU', '', $temp_item);
				if(isset($sub_items[3][0]))
					$sub_items_array[$key]=$sub_items[3][0];
			}
		}
		$a=0;
		while(preg_match('#<(([a-z_0-9]+)( .+)?)>#isU',$temp_item))
		{
			preg_match_all('#<(([a-z_0-9]+)( .+)?)>#isU',$temp_item,$temp_balise);
			if(preg_match_all('#<'.str_replace('#','\\#',preg_quote($temp_balise[1][0])).'>(<!\[CDATA\[)?(.+)(\]\]>)?</'.preg_quote($temp_balise[2][0]).'>#isU',$temp_item,$sub_items)===FALSE)
				echo 'Wrong regex: #<'.str_replace('#','\\#',preg_quote($temp_balise[1][0])).'>(<!\[CDATA\[)?(.+)(\]\]>)?</'.preg_quote($temp_balise[2][0]).'>#isU'."\n";
			else
			{
				$temp_item = preg_replace('#<'.str_replace('#','\\#',preg_quote($temp_balise[1][0])).'>(<!\[CDATA\[)?(.+)(\]\]>)?</'.str_replace('#','\\#',preg_quote($temp_balise[2][0])).'>#isU', '', $temp_item);
				$temp_item = preg_replace('#<'.str_replace('#','\\#',preg_quote($temp_balise[1][0])).'>#isU', '', $temp_item);
				foreach($sub_items[2] as $value)
				{
					$sub_items_array[$temp_balise[1][0]]=$value;
					if(!isset($sub_items_array[$temp_balise[2][0]]))
						$sub_items_array[$temp_balise[2][0]]=$value;
				}
			}
			$a++;
			if($a>999)
				break;
		}
		if($a>999)
			echo 'infinity loop'."\n";
		$add_to_array=true;
		$need_break=false;
		if(!isset($sub_items_array['guid']) && isset($sub_items_array['link']))
			$sub_items_array['guid']=$sub_items_array['link'];
		foreach($sub_items_array as $key => $val)
		{
			$sub_items_array[$key]=htmlspecialchars_decode($sub_items_array[$key]);
			$sub_items_array[$key] = preg_replace('#^<!\[CDATA\[#', '', $sub_items_array[$key]);
			$sub_items_array[$key] = preg_replace('#\]\]>$#', '', $sub_items_array[$key]);
			if($key=='guid' && $lastIdParsed!='' && $val==$lastIdParsed)
			{
				$add_to_array=false;
				$need_break=true;
			}
			if($key=='link' && strlen($val)>256)
				$add_to_array=false;
		}
		if($add_to_array)
			$temp_array[]=$sub_items_array;
		if($need_break)
			break;
	}
	return $temp_array;
}
?>