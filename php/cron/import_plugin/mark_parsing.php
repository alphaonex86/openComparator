<?php
$item['mark']=unknownlib_text_operation_lower_case_first_letter_upper($item['mark']);

$mark=array('AMD','Intel','Asrock','MSI','ATI','Gainward','Inno 3D','Leadtek','Palit','PNY','VTX3D','ZOTAC','Corsair','Crucial','GeIL','Kingston','OCZ','Veritech','Advance',
	'Aerocool','Antec','Cooler Master','Enermax','Helios','IN WIN','LanCool','MaxInPower','Thermaltake','LEPA','Shuttle','Zalman');
$mark_extra_name=array('SAPPHIRE TECHNOLOGY'=>'Sapphire','A-?DATA'=>'A-DATA','Asus(tek)?'=>'Asus','Lian[\- ]Li'=>'Lian Li','(Fortron|FSP)'=>'Fortron','LC[\- ]Power'=>'LC Power','GIGA-?BYTE'=>'Gigabyte');

foreach($mark as $name)
	if(preg_match('#^'.preg_quote($name).'$#i',$item['mark']))
	{
		$item['title']=preg_replace('#'.preg_quote($name).'#isU',' ',$item['title']);
		$item['mark']=$name;
		break;
	}
foreach($mark_extra_name as $name => $final_name)
	if(preg_match('#'.$name.'#i',$item['mark']))
	{
		$item['title']=preg_replace('#'.$name.'#isU',' ',$item['title']);
		$item['mark']=$final_name;
		break;
	}

