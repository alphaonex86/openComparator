<?php
$regex_socketA='((LGA|BGA) ?)?([a-zA-Z0-9]{3,4})( ?(LGA|BGA))? socket';
$regex_socketAC='[^0-9a-z]((LGA|BGA) ?)?([a-zA-Z0-9]{3,4})( ?(LGA|BGA))? (chipset|socket)';
$regex_socketB='socket ((LGA|BGA) ?)?([a-zA-Z0-9]{3,4})( ?(LGA|BGA))?';

if(!isset($item['socket']) && preg_match('#^((.*[^0-9a-z])?'.$regex_socketA.'.*|.*'.$regex_socketB.'([^0-9a-z].*)?)$#isU',$item['full_description_manual']))
{
	if(preg_match('#^.*'.$regex_socketB.'([^0-9a-z].*)?$#isU',$item['full_description_manual']))
	{
		$item['socket']=preg_replace('#^.*('.$regex_socketB.')([^0-9a-z].*)?$#isU','$1',$item['full_description_manual']);
		if(preg_match('#^([0-9]{2})$#isU',$item['socket']))
		{
			unset($item['socket']);
			if(preg_match('#'.$regex_socketA.'#is',$item['full_description_manual']))
				$item['socket']=preg_replace('#^(.*[^a-zA-Z0-9])?(((LGA|BGA) ?)?([a-zA-Z0-9]{3,4})( ?(LGA|BGA))?) socket.*$#is','$5',$item['full_description_manual']);
		}
	}
	elseif(preg_match('#^(.*[^0-9a-z])?'.$regex_socketA.'.*$#isU',$item['full_description_manual']))
	{
		$item['socket']=preg_replace('#^.*('.$regex_socketA.').*$#isU','$1',$item['full_description_manual']);
		if(preg_match('#^(M[ob]|un)$#isU',$item['socket']))
		{
			unset($item['socket']);
			if(preg_match('#'.$regex_socketB.'#isU',$item['full_description_manual']))
				$item['socket']=preg_replace('#^.*'.$regex_socketB.'.*$#is','$3',$item['full_description_manual']);
		}
	}
	if(isset($item['socket']))
	{
		$item['socket']=preg_replace('#socket#isU','',$item['socket']);
		$item['socket']=preg_replace('#(LGA|BGA)#isU','',$item['socket']);
		$item['socket']=preg_replace('#^ +#isU','',$item['socket']);
		$item['socket']=preg_replace('# +$#isU','',$item['socket']);
	}
}

if(!isset($item['memory_type']) && preg_match('#(G?DDR) ?([1-5])#i',$item['full_description_manual']))
	$item['memory_type']=preg_replace('#^.*(G?DDR) ?([1-5]).*$#isU','$1$2',$item['full_description_manual']);

if(!isset($item['northbridge']) && preg_match('#Chipset intel [a-z]{1,2}[0-9]{2,3}#i',$item['full_description_manual']))
	$item['northbridge']=preg_replace('#^.*Chipset intel ([a-z]{1,2}[0-9]{2,3}).*$#isU','Intel $1',$item['full_description_manual']);
if(!isset($item['northbridge']) && preg_match('#Chipset amd [a-z]{1,2}[0-9]{2,3}#i',$item['full_description_manual']))
	$item['northbridge']=preg_replace('#^.*Chipset amd ([a-z]{1,2}[0-9]{2,3}).*$#isU','AMD $1',$item['full_description_manual']);
if(!isset($item['northbridge']) && preg_match('#Chipset (nvidia|nforce) [a-z]{0,2}[0-9]{2,3}#i',$item['full_description_manual']))
	$item['northbridge']=preg_replace('#^.*Chipset (nvidia|nforce) ([a-z]{0,2}[0-9]{2,3}).*$#isU','nForce $1',$item['full_description_manual']);

if(!isset($item['northbridge']) && preg_match('#Chipset [a-z]{1,2}[0-9]{2,3}#i',$item['full_description_manual']) && isset($item['socket']))
{
	switch($item['socket'])
	{
		case '1156':
		case '1155':
		case '1366':
		case '775':
			$item['northbridge']=preg_replace('#^.*Chipset ([a-z]{1,2}[0-9]{2,3}).*$#isU','Intel $1',$item['full_description_manual']);
		break;
		case 'AM2':
		case 'AM2+':
		case 'AM3':
		case 'AM3+':
			$item['northbridge']=preg_replace('#^.*Chipset ([a-z]{1,2}[0-9]{2,3}).*$#isU','AMD $1',$item['full_description_manual']);
		break;
	}
}

if(!isset($item['format']))
{
	if(preg_match('#E[- ]?ATX#isU',$item['full_description_manual']))
		$item['format']='E-ATX';
	elseif(preg_match('#XL[- ]?ATX#isU',$item['full_description_manual']))
		$item['format']='XL-ATX';
	elseif(preg_match('#(.*[^a-z0-9])?SSI([^a-z0-9].*)?$#isU',$item['full_description_manual']))
		$item['format']='SSI';
	elseif(preg_match('#mini[- ]?ITX#isU',$item['full_description_manual']))
		$item['format']='Mini-ITX';
	elseif(preg_match('#Micro[- ]?ATX#isU',$item['full_description_manual']))
		$item['format']='Micro-ATX';
	elseif(preg_match('#ATX#isU',$item['full_description_manual']))
		$item['format']='ATX';
}

if(!isset($item['UEFI']))
	$item['UEFI']='no';

if(!isset($item['ref']))
{
	$temp_string=$item['title'];
	$temp_string=preg_replace('#^(.*[^a-z])?(E|XL|mini|micro)[- ]?ATX([^a-z].*)?$#i','$1 $2',$temp_string);
	$temp_string=preg_replace('#Carte m.res? micro#i',' ',$temp_string);
	$temp_string=preg_replace('#Carte m.res?#i',' ',$temp_string);
	$temp_string=preg_replace('#^(.*[^a-z])?SSI([^a-z].*)?$#i','$1 $2',$temp_string);
	$temp_string=preg_replace('#^(.*[^a-z])?ATX([^a-z].*)?$#i','$1 $2',$temp_string);
	$temp_string=preg_replace('#Desktop Board#i',' ',$temp_string);
	$temp_string=preg_replace('#Procedador (AMD|Intel) .*$#i',' ',$temp_string);
	if(preg_match('#^(.+)(\(| [0-9]+ ?[GM][Bo][^a-z0-9]| PCI[- ]?E(xpress)?|Processeur|(G?DDR) ?([1-5])|Procesador|Chipset|socket| - |'.$regex_socketAC.')#isU',$temp_string))
		$item['ref']=preg_replace('#^(.+)(\(| [0-9]+ ?[GM][Bo][^a-z0-9]| PCI[- ]?E(xpress)?|Processeur|(G?DDR) ?([1-5])|Procesador|Chipset|socket| - |'.$regex_socketAC.').*$#isU','$1',$temp_string);
	else
		$item['ref']=$temp_string;
	$item['ref']=preg_replace('#- +#i',' ',$item['ref']);
	$item['ref']=preg_replace('# +-#i',' ',$item['ref']);
}
