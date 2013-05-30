<?php
if(!isset($item['ref']))
{
	$temp_string=$item['title'];
	$temp_string=preg_replace('#^(.*[^a-z])?(E|XL|mini|micro)[- ]?[Ai]TX([^a-z].*)?$#i','$1 $3',$temp_string);
	$temp_string=preg_replace('#^(.*[^a-z])?[Ai]TX([^a-z].*)?$#i','$1 $2',$temp_string);
	$temp_string=preg_replace('#Mini( torre)?( PC)? (Desktop|Dekstop)#i',' ',$temp_string);
	$temp_string=preg_replace('#(Serveur|Tour|(Media|Grand) Tour|Tour|Unité|Structure|Boite|Tours?|Boite externe( MINI)?)( pour| de)? (PC|ordinateur)( (Extra Slim|personal))?#i',' ',$temp_string);
	$temp_string=preg_replace('#(Unité central|(Media|Grand) Tour|Boite externe|Tour complete)( pour| de)?( (PC|ordinateur)( (Extra Slim|personal))?)?#i',' ',$temp_string);
	$temp_string=preg_replace('# Modéle bureau #i',' ',$temp_string);
	$temp_string=preg_replace('# avec .*$#i',' ',$temp_string);
	$temp_string=preg_replace('#(avec )?alimentation( +[0-9]{3,4} ?(W|watts))?#i',' ',$temp_string);
	if(preg_match('#^(.+)\(.*$#i',$temp_string))
		$item['ref']=preg_replace('#^(.+)\(.*$#i','$1',$temp_string);
	else
		$item['ref']=$temp_string;
	$item['ref']=preg_replace('#- +#isU',' ',$item['ref']);
	$item['ref']=preg_replace('# +-#isU',' ',$item['ref']);
}
