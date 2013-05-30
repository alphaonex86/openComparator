<?php
foreach($technical_details_to_parse as $index => $val)
{
	switch($index)
	{
		case 'Tipo de interfaz':
		case 'Type d\'interface':
		case 'Bus':
		case 'Host Interface':
		case 'Ranuras compatibles':
			$temp_bus=$val;
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
				if(preg_match('# x ?[0-9]{1,2}$#i',$temp_bus))
					$speed=preg_replace('#^.* x ?([0-9]{1,2})$#i','$1x',$temp_bus);
				elseif(preg_match('# [0-9]{1,2} ?x#i',$temp_bus))
					$speed=preg_replace('#^.* ([0-9]{1,2}) ?x.*?#i','$1x',$temp_bus);
				$item['bus']=$norme.' '.$speed;
			}
		break;
		case 'Procesador  gráfico/ fabricante':
		case 'Procesador gráfico':
		case 'Processeur graphique':
		case 'Chipset Model':
			if(preg_match('#Radeon HD [0-9]{4}#isU',$val))
				$item['familly']=preg_replace('#^.*Radeon HD ([0-9]{4}).*$#isU','Radeon HD $1',$val);
			if(preg_match('#Radeon [0-9]{4}#isU',$val))
				$item['familly']=preg_replace('#^.*Radeon ([0-9]{4}).*$#isU','Radeon $1',$val);
			if(preg_match('#GeForce G([a-z]{1,2}) [0-9]{3,4}#isU',$val))
				$item['familly']=preg_replace('#^.*GeForce (G([a-z]{1,2}) [0-9]{3,4}).*$#isU','GeForce $1',$val);
			elseif(preg_match('#GeForce [0-9]{3,4} G([a-z]{1,2})#isU',$val))
				$item['familly']=preg_replace('#^.*GeForce ([0-9]{3,4}) (G([a-z]{1,2})).*$#isU','GeForce $2 $1',$val);
			elseif(preg_match('#GeForce [0-9]{3,4}[^0-9]*^#isU',$val))
				$item['familly']=preg_replace('#^.*#GeForce ([0-9]{3,4})[^0-9]*^#.*$#isU','GeForce $1',$val);
		break;
		case 'Velocidad del reloj':
		case 'Frecuencia del procesador':
		case 'Fréquence':
		case 'Processor Speed':
			if(preg_match('#([0-9]+) ?MHz#',$val))
				$item['frequency_gpu']=preg_replace('#^[^0-9]*([0-9]+) ?MHz[^0-9]*$#','$1',$val);
		break;
		case 'Memoria de vídeo instalada':
		case 'Memoria de vídeo':
		case 'Mémoire vidéo':
		case 'Standard Memory':
			if(preg_match('#[0-9]+(\.[0-9]+)? ?G[Bo]([^a-z0-9].*)?$#isU',$val))
				$item['memory']=preg_replace('#^.*([0-9]+(\.[0-9]+)?) ?G[Bo]([^a-z0-9].*)?$#isU','$1',$val)*1024;
			if(preg_match('#[0-9]+(\.[0-9]+)? ?M[Bo]([^a-z0-9].*)?$#isU',$val))
				$item['memory']=preg_replace('#^.*([0-9]+(\.[0-9]+)?) ?M[Bo]([^a-z0-9].*)?$#isU','$1',$val)*1;
		break;
		case 'Memoria':
		case 'Mémoire':
			if(preg_match('#[0-9]+ ?G[Bo]([^a-z0-9].*)?$#isU',$val))
				$item['memory']=preg_replace('#^.*([0-9]+) ?G[Bo]([^a-z0-9].*)?$#isU','$1',$val)*1024;
			if(preg_match('#[0-9]+ ?M[Bo]([^a-z0-9].*)?$#isU',$val))
				$item['memory']=preg_replace('#^.*([0-9]+) ?M[Bo]([^a-z0-9].*)?$#isU','$1',$val)*1;
			if(preg_match('#G?DDR[1-5]#i',$val))
				$item['memory_type']=preg_replace('#^.*(G?DDR[1-5]).*$#i','$1',$val);
		break;
		case 'Tecnología':
		case 'Technologie':
			if(preg_match('#G?DDR[1-5]#i',$val))
				$item['memory_type']=preg_replace('#^.*(G?DDR[1-5]).*$#i','$1',$val);
		break;
		case 'Frecuencia de la memoria':
		case 'Velocidad del reloj de la memoria':
		case 'Fréquence mémoire':
		case 'Memory Speed':
			if(preg_match('#^\(([1-3]?[0-9]{3}) ?MHz$#i',$val))
				$item['frequency_memory']=preg_replace('#^\(([1-3]?[0-9]{3}) ?MHz$#i','$1',$val);
			elseif(preg_match('#^(.*[^0-9])?([1-3]?[0-9]{3}) ?MHz.*$#i',$val))
				$item['frequency_memory']=preg_replace('#^(.*[^0-9])?([1-3]?[0-9]{3}) ?MHz.*$#i','$2',$val);
			if(preg_match('#DirectX [0-9]+$#isU',$val))
				$item['DirectX']=preg_replace('#^[^0-9]*DirectX ([0-9]+)[^0-9]*$#isU','$1',$val);
		break;
		case 'DirectX 3D Hardware':
		case 'DirectX':
			if(preg_match('#^[^0-9]*[0-9]+[^0-9]*$#isU',$val))
				$item['DirectX']=preg_replace('#^[^0-9]*([0-9]+)[^0-9]*$#isU','$1',$val);
		break;
		case 'OpenGL':
			if(preg_match('#^[^0-9]*[0-9]+(\.[0-9]+)?[^0-9]*$#isU',$val))
				$item['OpenGL']=preg_replace('#^[^0-9]*([0-9]+(\.[0-9]+)?)[^0-9]*$#isU','$1',$val);
		break;
		case 'Apoyado por API':
		case 'API':
		case 'API Supported':
			if(preg_match('#^.*OpenGL [0-9]+\.[0-9]+([^0-9].*)?$#i',$val))
				$item['OpenGL']=preg_replace('#^.*OpenGL ([0-9]+\.[0-9])+([^0-9].*)?$#i','$1',$val);
			if(preg_match('#^.*DirectX [0-9]+\.[0-9]+([^0-9].*)?$#i',$val))
				$item['DirectX']=preg_replace('#^.*DirectX ([0-9]+\.[0-9])+([^0-9].*)?$#i','$1',$val);
		break;
		case 'RAMDAC':
		case 'Interfaz de cambio Memoria/Procesador':
		case 'Tipo de dispositivo':
		case 'Tipo incluido':
		case 'Profundidad':
		case 'Altura':
		case 'Frecuencia de reloj del RAMDAC':
		case 'Características':
		case 'Resolución máxima (externa)':
		case 'Estándar de vídeo digital':
		case 'Compatible con HDCP':
		case 'Interfaces':
		case 'Cables incluidos':
		case 'Compatible with Windows 7':
		case 'Software incluido':
		case 'Salidas':
		case 'Detalles de resolución máxima':
		case 'N° máximo de monitores soportados':
		case 'Accesorios incluidos':
		case 'Cumplimiento de normas':
		case 'Tipo de paquete':
		case 'Suministro de alimentación recomendado':
		case 'Dispositivos periféricas / interfaz':
		case 'Sistema operativo requerido':
		case 'Motor de rendido':
		case 'Detalles de los requisitos del sistema':
		case 'Funciones de Windows 7 compatibles':
		case 'Servicio y mantenimiento':
		case 'Detalles de Servicio y Mantenimiento':
		case 'Estándares de compresión de vídeo':
		case 'Certificado para Windows Vista':
		case 'Conector/es de salida':
		case 'Resolución máxima colores (externa)':
		case 'Interfaz de TV':
		case 'Compatibilidad':
		case 'Gráficos de pantalla admitidos':
		case 'Anchura':
			break;
		default:
			echo 'Unparsed val:'.$index.'<br />'."\n";
			break;
	}
}