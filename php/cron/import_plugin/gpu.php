<?php
if(!isset($item['bus']))
{
	$temp_bus=$item['full_description_manual'];
	$temp_bus=preg_replace('#PCIe#i','PCI Express',$temp_bus);
	$temp_bus=preg_replace('#PCI[ \-]?Express#i','PCI Express',$temp_bus);
	$temp_bus=preg_replace('#(PCI Express [1-2])\.[0-1]#i','$1',$temp_bus);
	if(preg_match('#(PCI Express|AGP)#isU',$temp_bus))
	{
		if(preg_match('#PCI Express 2#isU',$temp_bus))
		{
			$norme='PCI Express 2';
			$speed='16x';
		}
		elseif(preg_match('#PCI Express#isU',$temp_bus))
		{
			$norme='PCI Express';
			$speed='16x';
		}
		else
		{
			$norme='AGP';
			$speed='8x';
		}
		if(preg_match('#(AGP|PCI Express)( [0-9\.]+)? x ?[0-9]{1,2}$#i',$temp_bus))
			$speed=preg_replace('#^.*(AGP|PCI Express)( [0-9\.]+)? x ?([0-9]{1,2})$#i','$3x',$temp_bus);
		elseif(preg_match('#(AGP|PCI Express)( [0-9\.]+)? [0-9]{1,2} ?x#i',$temp_bus))
			$speed=preg_replace('#^.*(AGP|PCI Express)( [0-9\.]+)? ([0-9]{1,2}) ?x.*?#i','$3x',$temp_bus);
		$item['bus']=$norme.' '.$speed;
	}
	if(preg_match('#PCI Express [1-2](\.[0-9]) [0-9]x#',$item['full_description_manual']))
		$item['bus']=preg_replace('#^.*(PCI Express [1-2])(\.[0-9]) ([0-9]x).*$#','$1 $3',$item['full_description_manual']);
}

if(!isset($item['familly']))
{
	if(preg_match('#Radeon( HD)? [0-9]{4}#isU',$item['full_description_manual']))
		$item['familly']=preg_replace('#^.*Radeon( HD)? ([0-9]{4}).*$#isU','Radeon HD $2',$item['full_description_manual']);
	elseif(preg_match('#GeForce G([a-z]{1,2}) [0-9]{3,4}#isU',$item['full_description_manual']))
		$item['familly']=preg_replace('#^.*GeForce (G([a-z]{1,2}) [0-9]{3,4}).*$#isU','GeForce $1',$item['full_description_manual']);
	elseif(preg_match('#GeForce [0-9]{3,4} G([a-z]{1,2})#isU',$item['full_description_manual']))
		$item['familly']=preg_replace('#^.*GeForce ([0-9]{3,4}) (G([a-z]{1,2})).*$#isU','GeForce $2 $1',$item['full_description_manual']);
	elseif(preg_match('#GeForce [0-9]{3,4}[^0-9]*^#isU',$item['full_description_manual']))
		$item['familly']=preg_replace('#^.*#GeForce ([0-9]{3,4})[^0-9]*^#.*$#isU','GeForce $1',$item['full_description_manual']);
}


if(!isset($item['memory']))
{
	if(preg_match('#[0-9]+(\.[0-9]+)? ?G[Bo][^a-z0-9]#i',$item['full_description_manual']))
		$item['memory']=preg_replace('#^.*([0-9]+(\.[0-9]+)?) ?G[Bo][^a-z0-9].*$#isU','$1',$item['full_description_manual'])*1024;
	elseif(preg_match('#[0-9]+(\.[0-9]+)? ?M[Bo][^a-z0-9]#i',$item['full_description_manual']))
		$item['memory']=preg_replace('#^.*([0-9]+(\.[0-9]+)?) ?M[Bo][^a-z0-9].*$#isU','$1',$item['full_description_manual'])*1;
}

if(!isset($item['memory_type']))
	if(preg_match('#(G?DDR) ?([1-5])#i',$item['full_description_manual']))
		$item['memory_type']=preg_replace('#^.*(G?DDR) ?([1-5]).*$#isU','$1$2',$item['full_description_manual']);

if(!isset($item['ref']))
{
	$temp_string=$item['title'];
	$temp_string=preg_replace('#silent#isU','Silent',$temp_string);
	$temp_string=preg_replace('#HD ?([0-9]{4})#i','HD $1',$temp_string);
	$temp_string=preg_replace('#Radeon ([a-z]+) HD ([0-9]{4})#i','Radeon HD $2 $1',$temp_string);
	if(preg_match('#^(.+)(- | -|\(| [0-9]+ ?[GM][Bo][^a-z0-9]| PCI[- ]?E(xpress)?|(G?DDR) ?([1-5]))#isU',$temp_string))
		$item['ref']=preg_replace('#^(.+)(- | -|\(| [0-9]+ ?[GM][Bo][^a-z0-9]| PCI[- ]?E(xpress)?|(G?DDR) ?([1-5])).*$#isU','$1',$temp_string);
	else
		$item['ref']=$temp_string;
	if(isset($item['memory']))
	{
		$item['ref'].=' - '.$item['memory'].'MB';
		if(isset($item['memory_type']))
			$item['ref'].=' '.$item['memory_type'];
	}
}
