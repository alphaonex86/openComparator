<?php
$regex_frenquency='[0-9]+([\.,][0-9]+)? ?GHz';
$regex_socketA='((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))? socket';
$regex_socketB='socket ((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?';
$regex_socketC='(LGA|BGA)[0-9]+';
$regex_socketD='[0-9]+(LGA|BGA)';
$regex_tdp='[^a-zA-Z0-9]1?[0-9]+ ?W';

if(!isset($item['frequency']))
{
	if(preg_match('#'.$regex_frenquency.'#isU',$item['full_description_manual']))
	{
		$temp_freq=$item['full_description_manual'];
		$temp_freq=str_replace(',','.',$temp_freq);
		$item['frequency']=preg_replace('#^.*([0-9]+(\.[0-9]+)?) ?GHz.*$#isU','$1',$temp_freq)*1000;
		if($item['frequency']>4500)
			echo 'Too hight frequency: '.$temp_freq.'<br />';
	}
	if(isset($item['frequency']))
	{
		if($item['frequency']>1000*1000)
			$item['frequency']/=1000;
		elseif($item['frequency']>100*1000)
			$item['frequency']/=100;
		elseif($item['frequency']>10*1000)
			$item['frequency']/=10;
	}
}
if(!isset($item['socket']))
{
	if(preg_match('#('.$regex_socketA.'|'.$regex_socketB.'|'.$regex_socketC.'|'.$regex_socketD.')#isU',$item['full_description_manual']))
	{
		if(preg_match('#'.$regex_socketB.'#isU',$item['full_description_manual']))
		{
			$item['socket']=preg_replace('#^.*socket (((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?)([^a-zA-Z0-9].*)?$#is','$4',$item['full_description_manual']);
			if(preg_match('#^([0-9]{2})$#isU',$item['socket']))
			{
				unset($item['socket']);
				if(preg_match('#'.$regex_socketA.'#isU',$item['full_description_manual']))
					$item['socket']=preg_replace('#^(.*[^a-zA-Z0-9])?(((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?) socket.*$#is','$5',$item['full_description_manual']);
			}
		}
		elseif(preg_match('#'.$regex_socketA.'#isU',$item['full_description_manual']))
		{
			$item['socket']=preg_replace('#^(.*[^a-zA-Z0-9])?(((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?) socket.*$#is','$5',$item['full_description_manual']);
			if(preg_match('#^(M[ob]|un)$#isU',$item['socket']))
			{
				unset($item['socket']);
				if(preg_match('#'.$regex_socketB.'#isU',$item['full_description_manual']))
					$item['socket']=preg_replace('#^.*'.$regex_socketB.'.*$#is','$3',$item['full_description_manual']);
			}
		}
		elseif(preg_match('#'.$regex_socketC.'#isU',$item['full_description_manual']))
		{
			$item['socket']=preg_replace('#^(.*[^a-z])?((LGA|BGA)[0-9]+)([^0-9].*)?$#is','$2',$item['full_description_manual']);
			if(preg_match('#^(M[ob]|un)$#isU',$item['socket']))
			{
				unset($item['socket']);
				if(preg_match('#'.$regex_socketB.'#isU',$item['full_description_manual']))
					$item['socket']=preg_replace('#^.*('.$regex_socketC.').*$#is','$1',$item['full_description_manual']);
			}
		}
		elseif(preg_match('#'.$regex_socketD.'#isU',$item['full_description_manual']))
		{
			$item['socket']=preg_replace('#^(.*[^0-9])?([0-9]+(LGA|BGA))([^a-z].*)?$#is','$2',$item['full_description_manual']);
			if(preg_match('#^(M[ob]|un)$#isU',$item['socket']))
			{
				unset($item['socket']);
				if(preg_match('#'.$regex_socketB.'#isU',$item['full_description_manual']))
					$item['socket']=preg_replace('#^.*('.$regex_socketD.').*$#is','$1',$item['full_description_manual']);
			}
		}
	}
	if(isset($item['socket']))
		$item['socket']=preg_replace('#(LGA|BGA)#isU','',$item['socket']);
}
if(!isset($item['nm']))
{
	if(preg_match('#[^a-zA-Z0-9]1?[0-9]+ ?nm#isU',$item['full_description_manual']))
		$item['nm']=preg_replace('#^.*[^a-zA-Z0-9](1?[0-9]+) ?nm.*$#isU','$1',$item['full_description_manual']);
}
if(!isset($item['TDP']))
{
	if(preg_match('#'.$regex_tdp.'#',$item['full_description_manual']))
		$item['TDP']=preg_replace('#^.*[^a-zA-Z0-9](1?[0-9]+) ?W.*$#sU','$1',$item['full_description_manual']);
}
if(!isset($item['nbr_core']))
{
	if(preg_match('#mono[\- ]?core#isU',$item['full_description_manual']) || preg_match('#[^a-z0-9]X1[^a-z0-9]#isU',$item['full_description_manual']))
		$item['nbr_core']=1;
	if(preg_match('#dual[\- ]?core#isU',$item['full_description_manual']) || preg_match('#[^a-z0-9]X2[^a-z0-9]#isU',$item['full_description_manual']) ||
		preg_match('#Core ?2? ?Duo#isU',$item['full_description_manual']) )
		$item['nbr_core']=2;
	if(preg_match('#tri[\- ]?core#isU',$item['full_description_manual']) || preg_match('#[^a-z0-9]X3[^a-z0-9]#isU',$item['full_description_manual']))
		$item['nbr_core']=3;
	if(preg_match('#quad[\- ]?core#isU',$item['full_description_manual']) || preg_match('#[^a-z0-9]X4[^a-z0-9]#isU',$item['full_description_manual']))
		$item['nbr_core']=4;
	if(preg_match('#hexa[\- ]?core#isU',$item['full_description_manual']) || preg_match('#[^a-z0-9]X6[^a-z0-9]#isU',$item['full_description_manual']))
		$item['nbr_core']=6;
}
if(!isset($item['L3']))
{
	if(preg_match('#[0-9] ?M[Bo] L3#isU',$item['full_description_manual']))
		$item['L3']=preg_replace('#^.*([0-9]) ?M[Bo] L3.*$#isU','$1',$item['full_description_manual'])*1024;
	elseif(preg_match('#[0-9] ?K[Bo] L3#isU',$item['full_description_manual']))
		$item['L3']=preg_replace('#^.*([0-9]) ?K[Bo] L3.*$#isU','$1',$item['full_description_manual'])*1;
	elseif(preg_match('#L3 [0-9] ?M[Bo]([^a-z0-9].*)?$#isU',$item['full_description_manual']))
		$item['L3']=preg_replace('#^.*L3 ([0-9]) ?M[Bo]([^a-z0-9].*)?$#isU','$1',$item['full_description_manual'])*1024;
	elseif(preg_match('#L3 [0-9] ?K[Bo]([^a-z0-9].*)?$#isU',$item['full_description_manual']))
		$item['L3']=preg_replace('#^.*L3 ([0-9]) ?K[Bo]([^a-z0-9].*)?$#isU','$1',$item['full_description_manual'])*1;
}
if(!isset($item['L2']))
{
	if(preg_match('#[0-9] ?M[Bo] L2#isU',$item['full_description_manual']))
		$item['L2']=preg_replace('#^.*([0-9]) ?M[Bo] L2.*$#isU','$1',$item['full_description_manual'])*1024;
	elseif(preg_match('#[0-9] ?K[Bo] L2#isU',$item['full_description_manual']))
		$item['L2']=preg_replace('#^.*([0-9]) ?K[Bo] L2.*$#isU','$1',$item['full_description_manual'])*1;
	elseif(preg_match('#L2 [0-9] ?M[Bo]([^a-z0-9].*)?$#isU',$item['full_description_manual']))
		$item['L2']=preg_replace('#^.*L2 ([0-9]) ?M[Bo]([^a-z0-9].*)?$#isU','$1',$item['full_description_manual'])*1024;
	elseif(preg_match('#L2 [0-9] ?K[Bo]([^a-z0-9].*)?$#isU',$item['full_description_manual']))
		$item['L2']=preg_replace('#^.*L2 ([0-9]) ?K[Bo]([^a-z0-9].*)?$#isU','$1',$item['full_description_manual'])*1;
}
if(!isset($item['ref']))
{
	$temp_string=$item['title'];
	$temp_string=preg_replace('# ?Processeur#i','',$temp_string);
	$temp_string=preg_replace('#AMD|Intel|Sandy Bridge#i','',$temp_string);
	$temp_string=preg_replace('#^(.*[^0-9]+)?1 ?x ?#i','',$temp_string);
	if(preg_match('#Black Edition#i',$temp_string))
	{
		$temp_string=preg_replace('#Black Edition#i','',$temp_string);
		$black_edition=true;
	}
	else
		$black_edition=false;
	if(preg_match('#Extreme Edition#i',$temp_string))
	{
		$temp_string=preg_replace('#Extreme Edition#i','',$temp_string);
		$extrem_edition=true;
	}
	else
		$extrem_edition=false;
	if(preg_match('#^(.+)('.$regex_socketA.'|'.$regex_socketB.'|'.$regex_socketC.'|'.$regex_socketD.'|'.$regex_tdp.'|Processor|Cache|\(|'.$regex_frenquency.'|/)#isU',$temp_string))
		$item['ref']=preg_replace('#^(.+)('.$regex_socketA.'|'.$regex_socketB.'|'.$regex_socketC.'|'.$regex_socketD.'|'.$regex_tdp.'|Processor|Cache|\(|'.$regex_frenquency.'|/).*$#isU','$1',$temp_string);
	else
		$item['ref']=$temp_string;
	if($black_edition)
		$item['ref'].=' Black Edition ';
	if($extrem_edition)
		$item['ref'].=' Extreme Edition ';
	$item['ref']=preg_replace('#- +#i',' ',$item['ref']);
	$item['ref']=preg_replace('# +-#i',' ',$item['ref']);
	$item['ref']=preg_replace('# +#',' ',$item['ref']);
	$item['ref']=preg_replace('#(mono|dual|tri|double|quad|hexa)[\- ]?core#isU','',$item['ref']);
	$item['ref']=preg_replace('#(i[357])-([0-9]+)#','$1 $2',$item['ref']);
}
if(!isset($item['boxed_version']))
{
	if(!preg_match('#(boxed version|box|caja)\)#isU',$item['full_description_manual']))
		$item['boxed_version']=false;
	else
		$item['boxed_version']=true;
}
