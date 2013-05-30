<?php
$regex_socketA='((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))? socket';
$regex_socketB='socket ((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?';
$regex_tdp='[^a-zA-Z0-9]1?[0-9]+ ?W';

foreach($technical_details_to_parse as $index => $val)
{
	switch($index)
	{
		case 'Vitesse d\'orloge':
		case 'Fréquence':
		case 'Fréquence (MHz)':
		case 'Clock Speed':
		case 'Velocidad reloj':
		case 'Frecuencia (MHz)':
			if(preg_match('#^[1-3][\.,][0-9] ?GHz$#',$val))
			{
				$temp_freq=preg_replace('#^([1-3][\.,][0-9]) ?GHz$#','$1',$val);
				$temp_freq=str_replace(',','.',$temp_freq);
				$item['frequency']=$temp_freq*1000;
			}
		break;
		case 'Socket':
		case 'Zócalo de procesador compatible':
		case 'Soporte':
			if(preg_match('#('.$regex_socketA.'|'.$regex_socketB.')#isU',$val))
			{
				if(preg_match('#'.$regex_socketB.'#isU',$val))
				{
					$item['socket']=preg_replace('#^.*socket (((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?)([^a-zA-Z0-9].*)?$#is','$4',$val);
					if(preg_match('#^([0-9]{2})$#isU',$item['socket']))
					{
						unset($item['socket']);
						if(preg_match('#'.$regex_socketA.'#isU',$val))
							$item['socket']=preg_replace('#^(.*[^a-zA-Z0-9])?(((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?) socket.*$#is','$5',$val);
					}
				}
				else
				{
					$item['socket']=preg_replace('#^(.*[^a-zA-Z0-9])?(((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?) socket.*$#is','$5',$val);
					if(preg_match('#^(M[ob]|un)$#isU',$item['socket']))
					{
						unset($item['socket']);
						if(preg_match('#'.$regex_socketB.'#isU',$val))
							$item['socket']=preg_replace('#^.*'.$regex_socketB.'.*$#is','$3',$val);
					}
				}
				if(isset($item['socket']))
					$item['socket']=preg_replace('#(LGA|BGA)#isU','',$item['socket']);
			}
		break;
		case 'Proceso de fabricación':
		case 'Fineza de grabado':
		case 'Processus de fabrication':
		case 'Finesse de gravure':
		case 'Process Technology':
			if(preg_match('#^(.*[^0-9])?[0-9]{1,3} ?nm.*$#isU',$val))
				$item['nm']=preg_replace('#^(.*[^0-9])?([0-9]{1,3}) ?nm.*$#isU','$2',$val)*1;
		break;
		case 'Potencia de diseño térmico':
		case 'TPD':
		case 'TDP':
		case 'Thermal Design Power':
			if(preg_match('#^[0-9]{1,3} ?W$#isU',$val))
				$item['TDP']=preg_replace('#^([0-9]{1,3}) ?W$#isU','$1',$val)*1;
		break;
		case 'Tecnología multipolar':
		case 'Technologie multi-coeur':
		case 'Processor Core':
			if(preg_match('#(mono|1)[\- ]?core#isU',$val) || preg_match('#[^a-z0-9]X1[^a-z0-9]#isU',$val))
				$item['nbr_core']=1;
			elseif(preg_match('#(dual|2)[\- ]?core#isU',$val) || preg_match('#[^a-z0-9]X2[^a-z0-9]#isU',$val) ||
				preg_match('#Core ?2? ?Duo#isU',$val) )
				$item['nbr_core']=2;
			elseif(preg_match('#(tri|3)[\- ]?core#isU',$val) || preg_match('#[^a-z0-9]X3[^a-z0-9]#isU',$val))
				$item['nbr_core']=3;
			elseif(preg_match('#(quad|4)[\- ]?core#isU',$val) || preg_match('#[^a-z0-9]X4[^a-z0-9]#isU',$val))
				$item['nbr_core']=4;
			elseif(preg_match('#(hexa|6)[\- ]?core#isU',$val) || preg_match('#[^a-z0-9]X6[^a-z0-9]#isU',$val))
				$item['nbr_core']=6;
			elseif(preg_match('#^[0-9]+ .*$#isU',$val))
				$item['nbr_core']=preg_replace('#^([0-9]+) .*$#isU','$1',$val);
			if(!isset($item['nbr_core']))
				echo '<span style="color:#f00">nbr_core not detected: </span><br />'."\n";
		break;
		case 'Cache installé':
		case 'Tamaño instalado':
			$temp=$val;
			if(!isset($item['nbr_core']) && preg_match('#^(.*[^0-9])?[0-9]+ x #isU',$temp))
				$item['nbr_core']=preg_replace('#^(.*[^0-9])?([0-9]+) x .*$#isU','$2',$temp);
			$temp=preg_replace('# [0-9]+ x #',' ',$temp);
			if(preg_match('#L2 - +[0-9]{3,5} ?K[Bo]#isU',$temp))
				$item['L2']=preg_replace('#^.*L2 - +([0-9]{3,5}) ?K[Bo].*$#','$1',$temp);
			if(preg_match('#L2 - +[0-9]{1,5} ?M[Bo]#isU',$temp))
				$item['L2']=preg_replace('#^.*L2 - +([0-9]{1,3}) ?M[Bo].*$#','$1',$temp)*1024;
			if(preg_match('#L3 - +[0-9]{3,5} ?K[Bo]#isU',$temp))
				$item['L3']=preg_replace('#^.*L3 - +([0-9]{3,5}) ?K[Bo].*$#','$1',$temp);
			if(preg_match('#L3 - +[0-9]{1,5} ?M[Bo]#isU',$temp))
				$item['L3']=preg_replace('#^.*L3 - +([0-9]{1,3}) ?M[Bo].*$#','$1',$temp)*1024;
		break;
		case 'L2 Cache':
		case 'Cache L2 installé':
		case 'Tamaño Cache L2':
			$temp=$val;
			if(!isset($item['nbr_core']) && preg_match('#^(.*[^0-9])?[0-9]+ x #isU',$temp))
				$item['nbr_core']=preg_replace('#^(.*[^0-9])?([0-9]+) x .*$#isU','$2',$temp);
			$temp=preg_replace('#^[0-9]+ x #','',$temp);
			if(preg_match('#^[0-9]{3,5} ?K[Bo]([^a-z0-9].*)?$#isU',$temp))
				$item['L2']=preg_replace('#^([0-9]{3,5}) ?K[Bo]([^a-z0-9].*)?$#','$1',$temp);
			if(preg_match('#^[0-9]{1,5} ?M[Bo]([^a-z0-9].*)?$#isU',$temp))
				$item['L2']=preg_replace('#^([0-9]{1,5}) ?M[Bo]([^a-z0-9].*)?$#','$1',$temp)*1024;
		break;
		case 'L3 Cache':
		case 'Cache L3 installé':
		case 'Tamaño Cache L3':
			$temp=$val;
			if(!isset($item['nbr_core']) && preg_match('#^(.*[^0-9])?[0-9]+ x #isU',$temp))
				$item['nbr_core']=preg_replace('#^(.*[^0-9])?([0-9]+) x .*$#isU','$2',$temp);
			$temp=preg_replace('#^[0-9]+ x #','',$temp);
			if(preg_match('#^[0-9]{3,5} ?K[Bo]$#isU',$temp))
				$item['L3']=preg_replace('#^([0-9]{3,5}) ?K[Bo]$#','$1',$temp);
			if(preg_match('#^[0-9]{1,5} ?MB$#isU',$temp))
				$item['L3']=preg_replace('#^([0-9]{1,5}) ?M[Bo]$#','$1',$temp)*1024;
		break;
		case 'Type de boite':
		case 'Tipo de paquete':
			if(preg_match('#boite|caja|PIB#i',$val))
				$item['boxed_version']=true;
		break;
		case 'Tamaño Cache L1':
		case 'Arquitectura':
		case 'MMX':
		case 'SSE':
		case 'SSE 2':
		case 'Tipo de producto':
		case 'Computación de 64 bits':
		case 'Características arquitectura':
		case 'Ranuras compatibles':
		case 'Accesorios incluidos':
		case 'Servicio y mantenimiento':
		case 'Frecuencia básica':
		case 'Frecuencia Max Dynamic':
		case 'Características':
		case 'Tipo / factor de forma':
		case 'Cantidad de procesadores':
		case 'Tamaño Nivel 1 Caché':
		case 'Detalles de Servicio y Mantenimiento':
		case 'Velocidad del bus':
		case 'Tipo':
		case 'Bus procesador':
		case 'Especificación térmica':
		case '3D Now!':
		case 'Frecuencia Cache L2':
		case 'Cumplimiento de normas':
		case 'Voltaje del núcleo':
			break;
		default:
			echo 'Unparsed val:'.$index.'<br />'."\n";
			break;
	}
}

