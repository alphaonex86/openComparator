<?php
/// \note This file require unknownlib-function.php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['text-operation']=true;

//clean text and return string to "toto toto2 toto3", the word lower than X value are dropped
function unknownlib_text_operation_clean_text($text,$minimum_word_length=4,$minimum_string_length=15,$maximum_string_length=64)
{
	$text=unknownlib_text_operation_lower_case($text);

	$text=str_replace('â','a',str_replace('à','a',$text));
	$text=str_replace('ã','a',str_replace('á','a',str_replace('ã','a',str_replace('ä','a',$text))));

	$text=str_replace('ç','c',$text);

	$text=str_replace('é','e',str_replace('è','e',str_replace('ê','e',str_replace('ë','e',$text))));

	$text=str_replace('ì','i',str_replace('í','i',str_replace('î','i',str_replace('ï','i',$text))));

	$text=str_replace('ñ','n',$text);
	
	$text=str_replace('õ','o',str_replace('ö','o',str_replace('ó','o',str_replace('ô','o',$text))));
	$text=str_replace('ô','o',str_replace('ò','o',$text));
	
	$text=str_replace('û','u',str_replace('ü','u',str_replace('ú','u',str_replace('ù','u',$text))));
	
	$text=str_replace('ý','y',str_replace('ÿ','y',$text));
	
	if(strlen($text)>$maximum_string_length)
		$text=substr($text,0,$maximum_string_length);
	$text=preg_replace('#([0-9]+)(\.|-| )+#','$1 ',$text);
	$text=preg_replace('#[^a-zA-Z0-9_-]+#',' ',$text);
	$text=preg_replace('# +#',' ',$text);
	if($minimum_word_length>2)
	{
		$a=$minimum_word_length-1;
		do
		{
			$text_temp=preg_replace('#\b[a-zA-Z_-]{1,'.$a.'}\b#',' ',$text);
			$text_temp=preg_replace('# +#',' ',$text_temp);
			$text_temp=preg_replace('# +$#','',$text_temp);
			$text_temp=preg_replace('#^ +#','',$text_temp);
			$a--;
		}
		while(strlen($text_temp)<=$minimum_string_length && $a>1);
		if(strlen($text_temp)>$minimum_string_length && $a>1)
			$text=$text_temp;
	}
	$text=preg_replace('# +#',' ',$text);
	$text=preg_replace('# +$#','',$text);
	$text=preg_replace('#^ +#','',$text);
	return $text;
}

function unknownlib_text_operation_do_for_url($text,$minimum_word_length=4,$minimum_string_length=15,$maximum_string_length=64)
{
	$text=unknownlib_text_operation_clean_text($text,$minimum_word_length,$minimum_string_length,$maximum_string_length);
	$text=str_replace(' ','-',$text);
	$text=preg_replace('#-+#','-',$text);
	$text=preg_replace('#^-+#','',$text);
	$text=preg_replace('#-+$#','',$text);
	return $text;
}

function unknownlib_text_operation_lower_case($text)
{
	$text=strtolower($text);
	$text=str_replace('Â','â',$text);
	$text=str_replace('À','à',$text);
	$text=str_replace('Ä','ä',$text);
	$text=str_replace('Ç','ç',$text);
	$text=str_replace('Ê','ê',$text);
	$text=str_replace('È','è',$text);
	$text=str_replace('Ë','ë',$text);
	$text=str_replace('É','é',$text);
	$text=str_replace('Ï','ï',$text);
	$text=str_replace('Ö','ö',$text);
	$text=str_replace('Ô','ô',$text);
	$text=str_replace('Û','û',$text);
	$text=str_replace('Ù','ù',$text);
	$text=str_replace('Ü','ü',$text);
	return $text;
}

function unknownlib_text_operation_lower_case_first_letter_upper($text)
{
	if(strlen($text)<=0)
		return $text;
	else if(strlen($text)==1)
		return strtoupper($text);
	else
		return strtoupper(substr($text,0,1)).unknownlib_text_operation_lower_case(substr($text,1,strlen($text)-1));
}

function unknownlib_text_operation_cut_text($text,$length_text_max)
{
	if(strlen($text)<$length_text_max || $length_text_max==0)
		return $text;
	else
	{
		$offset=0;
		$a=0;
		while(($a < 6) && (($length_text_max-$a) > 0) && substr($text,$length_text_max-$a-1,1)!='&' && substr($text,$length_text_max-$a-1,1)!=';')
			$a++;
		if((($length_text_max-$a) > 0) && substr($text,$length_text_max-$a-1,1)=='&')
			$offset=$a+1;
		$length=($length_text_max-$offset);
		$a=0;
		while(ord(substr($text,$length_text_max-$a-1,1))>=128 && ord(substr($text,$length_text_max-$a-1,1))<=193 && ord(substr($text,$length_text_max-$a,1))<194 && ord(substr($text,$length_text_max-$a,1))>127 && (($length_text_max-$offset-$a) >= 0))
			$a++;

		if(($length_text_max-$offset-$a) >= 0)
		{
			if(ord(substr($text,$length_text_max-$a-1,1))>=194 && ord(substr($text,$length_text_max-$a,1))<194)
				$offset+=$a+1;
			else
				$offset+=$a;
		}
		$length=($length_text_max-$offset);
		return substr($text,0,$length).'...';
	}
}

function unknownlib_text_operation_clean_css($text)
{
	$text=preg_replace('#([;{},:]) +#','$1',$text);
	$text=preg_replace('# +([;{},:])#','$1',$text);
	$text=str_replace(';}','}',$text);
	$text=preg_replace('#([ :]0)px#','$1',$text);
	$text=preg_replace('#[\n\r\t]+#','',$text);
	$text=preg_replace('#/\\*.*\\*/#isU','',$text);
	return $text;
}

function unknownlib_text_operation_clean_js($text)
{
	$text=preg_replace("#[\n\r\t]+#",'',$text);
	$text=preg_replace("#/\*.*\*/#U",'',$text);
	return $text;
}

function unknownlib_text_operation_clean_html($text)
{
	$text=preg_replace('# +#',' ',$text);
	$text=preg_replace("#[\n\r\t]+#",'',$text);
	$text=str_replace('<script type="text/javascript"><!--','<script type="text/javascript">'."\n".'<!--'."\n",$text);
	$text=str_replace('--></script>',"\n".'-->'."\n".'</script>',$text);
	return $text;
}

function unknownlib_text_htmlentities_drop($text)
{
	$text=str_replace('&','&amp;',$text);
	$text=str_replace('<','&lt;',$text);
	$text=str_replace('>','&gt;',$text);
	$text=str_replace('"','&quot;',$text);
	$text=str_replace('\'','&#039;',$text);
	return $text;
}

function unknownlib_text_array_of_keyword($text)
{
	return array_unique(explode(' ',unknownlib_text_operation_clean_text($text,3,0,65535)));
}

function unknownlib_text_switch_word($text,$array,$replace_plural=true)
{
	if($replace_plural)
		foreach($array as $syno_word=>$syno_new_word)
		{
			$text=preg_replace('#\b'.$syno_word.'\b#isU','%56432116584984984%',$text);
			$text=preg_replace('#\b'.$syno_new_word.'\b#isU',$syno_word,$text);
			$text=str_replace('%56432116584984984%',$syno_new_word,$text);
			$text=preg_replace('#\b'.$syno_word.'s\b#isU','%56432116584984984s%',$text);
			$text=preg_replace('#\b'.$syno_new_word.'s\b#isU',$syno_word.'s',$text);
			$text=str_replace('%56432116584984984s%',$syno_new_word.'s',$text);
		}
	else
		foreach($array as $syno_word=>$syno_new_word)
		{
			$text=preg_replace('#\b'.$syno_word.'\b#isU','%56432116584984984%',$text);
			$text=preg_replace('#\b'.$syno_new_word.'\b#isU',$syno_word,$text);
			$text=str_replace('%56432116584984984%',$syno_new_word,$text);
		}
	return $text;
}

function unknownlib_text_replace_word($text,$array,$replace_plural=true)
{
	if($replace_plural)
	{
		$a=0;
		foreach($array as $syno_word=>$syno_new_word)
		{
			$syno_word=str_replace('#','\\#',$syno_word);
			$syno_word=str_replace('e','[eéèêë]',$syno_word);
			$syno_word=str_replace('a','[aàâä]',$syno_word);
			$syno_word=str_replace('u','[uùûü]',$syno_word);
			$syno_word=str_replace('i','[iîï]',$syno_word);
			$syno_word=str_replace('o','[oôö]',$syno_word);
			$syno_word=str_replace('y','[yÿ]',$syno_word);
			$text=preg_replace('#\b'.$syno_word.'\b#isU','%56432116584984984'.str_pad($a,10,'0', STR_PAD_LEFT).'%',$text);
			$text=preg_replace('#\b'.$syno_word.'s\b#isU','%56432116584984984'.str_pad($a,10,'0', STR_PAD_LEFT).'s%',$text);
			$a++;
		}
		$a=0;
		foreach($array as $syno_word=>$syno_new_word)
		{
			$text=str_replace('%56432116584984984'.str_pad($a,10,'0', STR_PAD_LEFT).'%',$syno_new_word,$text);
			$text=str_replace('%56432116584984984'.str_pad($a,10,'0', STR_PAD_LEFT).'s%',$syno_new_word.'s',$text);
			$a++;
		}
	}
	else
	{
		$a=0;
		foreach($array as $syno_word=>$syno_new_word)
		{
			$syno_word=str_replace('#','\\#',$syno_word);
			$syno_word=str_replace('e','[eéèêë]',$syno_word);
			$syno_word=str_replace('a','[aàâä]',$syno_word);
			$syno_word=str_replace('u','[uùûü]',$syno_word);
			$syno_word=str_replace('i','[iîï]',$syno_word);
			$syno_word=str_replace('o','[oôö]',$syno_word);
			$syno_word=str_replace('y','[yÿ]',$syno_word);
			$text=preg_replace('#\b'.$syno_word.'\b#isU','%56432116584984984'.str_pad($a,10,'0', STR_PAD_LEFT).'%',$text);
			$a++;
		}
		$a=0;
		foreach($array as $syno_word=>$syno_new_word)
		{
			$text=str_replace('%56432116584984984'.str_pad($a,10,'0', STR_PAD_LEFT).'%',$syno_new_word,$text);
			$a++;
		}
	}
	return $text;
}

function unknownlib_text_auto_encoding_to_utf8($text)
{
	$ary[] = 'UTF-8';
	$ary[] = 'ISO-8859-1';
	$ary[] = 'ISO-8859-15';
	$ary[] = 'ASCII';
	$ary[] = 'JIS';
	$ary[] = 'EUC-JP';
	$mb_detect_encoding=mb_detect_encoding($text,$ary,true);
	$text=mb_convert_encoding($text,'UTF-8',$mb_detect_encoding);
	$text=str_replace('','\'',$text);
	$text=preg_replace('/&#[0-9]{1,4};/',' ',$text);
	return $text;
}


?>