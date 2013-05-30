<?php
if(!isset($item['memory_type']) && preg_match('#(DDR) ?([1-5])#i',$item['full_description_manual']))
	$item['memory_type']=preg_replace('#^.*(DDR) ?([1-5]).*$#isU','$1$2',$item['full_description_manual']);

if(!isset($item['frequency']))
{
	if(preg_match('#^(.*[^0-9])?[0-9]{3,4} ?Mhz([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)preg_replace('#^(.*[^0-9])?([0-9]{3,4}) ?Mhz([^0-9].*)?$#i','$2',$item['full_description_manual']);
	elseif(!isset($item['frequency']) && preg_match('#DDR3[- ]+[0-9]{3,5}([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)preg_replace('#^.*DDR3[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$item['full_description_manual']);
	elseif(!isset($item['frequency']) && preg_match('#DDR2[- ]+[0-9]{3,5}([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)preg_replace('#^.*DDR2[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$item['full_description_manual']);
	elseif(!isset($item['frequency']) && preg_match('#DDR1[- ]+[0-9]{3,5}([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)preg_replace('#^.*DDR1[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$item['full_description_manual']);
	elseif(!isset($item['frequency']) && preg_match('#DDR[- ]*[0-9]{3,5}([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)preg_replace('#^.*DDR[- ]*([0-9]{3,5})([^0-9].*)?$#isU','$1',$item['full_description_manual']);
	elseif(!isset($item['frequency']) && preg_match('#PC3[- ]+[0-9]{3,5}([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)(preg_replace('#^.*PC3[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$item['full_description_manual'])/8);
	elseif(!isset($item['frequency']) && preg_match('#PC2[- ]+[0-9]{3,5}([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)(preg_replace('#^.*PC2[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$item['full_description_manual'])/8);
	elseif(!isset($item['frequency']) && preg_match('#PC1[- ]+[0-9]{3,5}([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)(preg_replace('#^.*PC1[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$item['full_description_manual'])/8);
	elseif(!isset($item['frequency']) && preg_match('#PC[- ]*[0-9]{3,5}([^0-9].*)?$#i',$item['full_description_manual']))
		$item['frequency']=(int)(preg_replace('#^.*PC[- ]*([0-9]{3,5})([^0-9].*)?$#isU','$1',$item['full_description_manual'])/8);
	if(isset($item['frequency']))
		if($item['frequency']==662)
			$item['frequency']=667;
}

if(!isset($item['kit']) && !isset($item['size']) && preg_match('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?G(B|o|ob)([^a-z0-9].*)?$#i',$item['full_description_manual']))
{
	$item['kit']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?G(B|o|ob)([^a-z0-9].*)?$#i','$2',$item['full_description_manual'])*1;
	$item['size']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?G(B|o|ob)([^a-z0-9].*)?$#i','$3',$item['full_description_manual'])*1024;
}
elseif(!isset($item['kit']) && !isset($item['size']) && preg_match('#^(.*[^0-9])?[0-9]+ ?x ?[0-9]+ ?MG(B|o|ob)([^a-z0-9].*)?$#i',$item['full_description_manual']))
{
	$item['kit']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?MG(B|o|ob)([^a-z0-9].*)?$#i','$2',$item['full_description_manual'])*1;
	$item['size']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?MG(B|o|ob)([^a-z0-9].*)?$#i','$3',$item['full_description_manual'])*1;
}
elseif(!isset($item['size']) && preg_match('#[0-9]+ ?G[Bo]([^a-z0-9].*)?$#i',$item['full_description_manual']))
	$item['size']=preg_replace('#^.*([0-9]+) ?G[Bo]([^a-z0-9].*)?$#isU','$1',$item['full_description_manual'])*1024;
elseif(!isset($item['size']) && preg_match('#[0-9]+ ?M[Bo]([^a-z0-9].*)?$#i',$item['full_description_manual']))
	$item['size']=preg_replace('#^.*([0-9]+) ?M[Bo]([^a-z0-9].*)?$#isU','$1',$item['full_description_manual'])*1;

if(!isset($item['format']) && preg_match('#portable#i',$item['full_description_manual']))
	$item['format']='So-DIMM';

if(!isset($item['ecc']) && preg_match('#no[a-z]?[ \-]?ecc#i',$item['full_description_manual']))
	$item['ecc']=false;
elseif(!isset($item['ecc']) && preg_match('#ecc#i',$item['full_description_manual']))
	$item['ecc']=true;

if(!isset($item['cas']) && preg_match('#CL[0-9]{1,2}([^0-9].*)?$#i',$item['full_description_manual']))
	$item['cas']=preg_replace('#^.*CL([0-9]{1,2})([^0-9].*)?$#i','$1',$item['full_description_manual'])*1;

if(!isset($item['voltage']) && preg_match('#^(.*[^0-9])[1-2][\.,][0-9]{1,2} ?V([^a-z].*)$#i',$item['full_description_manual']))
	$item['voltage']=preg_replace('#^(.*[^0-9])([1-2])[\.,]([0-9]){1,2} ?V([^a-z].*)$#i','$2.$3',$item['full_description_manual'])*1;

if(!isset($item['ref']))
{
	$temp_string=$item['title'];
	$temp_string=preg_replace('#(mémoire|memory)(( +portable)? +pour)? +(mac|pc)( standar)?#i','',$temp_string);
	$temp_string=preg_replace('#dual[\- ]?channel#i',' ',$temp_string);
	$temp_string=preg_replace('#tri(ple)?[\- ]?channel#i',' ',$temp_string);
	$temp_string=preg_replace('#portable#i',' ',$temp_string);
	$temp_string=preg_replace('#mémoire?[\- ]pc#i',' ',$temp_string);
	$temp_string=preg_replace('#mémoire?[\- ]portable#i',' ',$temp_string);
	$temp_string=preg_replace('#mémoire? pour (ordinateur portable|ordinateur|pc)#i',' ',$temp_string);
	$temp_string=preg_replace('#(mémoire|memory)#i',' ',$temp_string);
	if(preg_match('#^(.+)(\(| [0-9]+ ?[GM][Bo]| PCI[- ]?E(xpress)?|(G?DDR) ?([1-5])|[0-9]+ ?x ?[0-9]+ ?[gm][ob]|").*$#isU',$temp_string))
		$item['ref']=preg_replace('#^(.+)(\(| [0-9]+ ?[GM][Bo]| PCI[- ]?E(xpress)?|(G?DDR) ?([1-5])|[0-9]+ ?x ?[0-9]+ ?[gm][ob]|").*$#isU','$1',$temp_string);
	else
		$item['ref']=$temp_string;
	$item['ref']=preg_replace('#- +#isU',' ',$item['ref']);
	$item['ref']=preg_replace('# +-#isU',' ',$item['ref']);
	$item['ref']=preg_replace('#([^0-9])[0-9]+ ?x([^a-z])#i','$1$2',$item['ref']);
	if(isset($item['size']))
	{
		$item['ref'].=' - ';
		if(isset($item['kit']) && $item['kit']>1)
			$item['ref'].=$item['kit'].'x';
		$item['ref'].=$item['size'].'MB';
		if(isset($item['memory_type']))
		{
			$item['ref'].=' '.$item['memory_type'];
			if(isset($item['frequency']))
				$item['ref'].='-'.$item['frequency'];
		}
		elseif(isset($item['frequency']))
			$item['ref'].='-'.$item['frequency'].'Mhz';
	}
	if(isset($item['cas']))
		$item['ref'].=' CL'.$item['cas'];
	if(isset($item['format']) && $item['format']!='DIMM')
		$item['ref'].=' '.$item['format'];
	if(preg_match('#Retail#',$item['title']))
		$item['ref'].=' Retail';
}

if(!isset($item['format']) && isset($item['format_temp']))
	$item['format']=$item['format_temp'];
