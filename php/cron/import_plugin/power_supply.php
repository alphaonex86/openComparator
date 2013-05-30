<?php
if(!isset($item['power']))
{
	if(preg_match('#[^a-z0-9]1?[0-9]{3} ?(W|watts|V[^a-z0-9\+])#',$item['full_description_manual']))
		$item['power']=preg_replace('#^.*[^a-z0-9](1?[0-9]{3}) ?(W|watts|V[^a-z0-9\+]).*$#sU','$1',$item['full_description_manual']);
}

if(!isset($item['certification']))
{
	if(preg_match('#[^0-9]80+ platinum#i',$item['full_description_manual']))
		$item['certification']='80+ Platinum';
	elseif(preg_match('#[^0-9]80+ (gold|or|or)#i',$item['full_description_manual']))
		$item['certification']='80+ Or';
	elseif(preg_match('#[^0-9]80+ (silver|argent)#i',$item['full_description_manual']))
		$item['certification']='80+ Argent';
	elseif(preg_match('#[^0-9]80+ (bronze)#i',$item['full_description_manual']))
		$item['certification']='80+ Bronze';
	elseif(preg_match('#[^0-9]80+#',$item['full_description_manual']))
		$item['certification']='80+';
}

if(!isset($item['ref']))
{
	$temp_string=$item['title'];
	$temp_string=preg_replace('#(Source de )?alimentation?( PC)?#i',' ',$temp_string);
	$temp_string=preg_replace('# modulable #i',' ',$temp_string);
	$temp_string=preg_replace('#([^a-z0-9])Alimentation([^a-z0-9])#isU','$1 $2',$temp_string);
	if(preg_match('#^(.+)([^a-z0-9]1?[0-9]{3} ?(W|watts|V[^a-z0-9\+])|para|\()#isU',$temp_string))
		$item['ref']=preg_replace('#^(.+)([^a-zA-Z0-9]1?[0-9]+ ?(W|watts)|para|\().*$#isU','$1',$temp_string);
	else
		$item['ref']=$temp_string;
	$item['ref']=preg_replace('#- +#isU',' ',$item['ref']);
	$item['ref']=preg_replace('# +-#isU',' ',$item['ref']);
	$item['ref']=preg_replace('#[^a-z0-9]1?[0-9]{3} ?(W|watts|V) *$#isU',' ',$item['ref']);
	if(isset($item['power']))
		$item['ref'].=' '.$item['power'].'W';
}

