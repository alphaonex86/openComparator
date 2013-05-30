<?php
$regex_socketA='((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))? socket';
$regex_socketB='socket ((LGA|BGA) ?)?([a-zA-Z0-9]+)( ?(LGA|BGA))?';

$speed_interface=0;
$number_interface=1;

if(isset($item['sound']))
	echo '$item[\'sound\']: '.$item['sound'].'<br />'."\n";

foreach($technical_details_to_parse as $index => $val)
{
	switch($index)
	{
		case 'Códec de audio':
		case 'Codec de audio':
			if(!isset($item['sound_codec']))
				if(preg_match('#^(codec )?[a-z]+ [a-z0-9]+( [2-9]\.[0-1])?$#isU',$val))
					$item['sound_codec']=preg_replace('#^(codec )?([a-z]+ [a-z0-9]+)( [2-9]\.[0-1])?$#isU','$2',$val);
		break;
		case 'Modo de salida del sonido':
		case 'Sortie son':
		case 'Audio Channels':
			if(!isset($item['sound']))
				if(preg_match('#[2-9]\.[0-1]#isU',$val))
					$item['sound']=preg_replace('#.*([2-9]\.[0-1]).*#isU','$1',$val);
		break;
		case 'Audio':
			if(!isset($item['sound']))
				if(preg_match('#[2-9]\.[0-1]#isU',$val))
					$item['sound']=preg_replace('#.*([2-9]\.[0-1]).*#isU','$1',$val);
			if(!isset($item['sound_codec']))
				if(preg_match('#^(codec )?[a-z]+ [a-z0-9]+( [2-9]\.[0-1])?$#isU',$val))
					$item['sound_codec']=preg_replace('#^(codec )?([a-z]+ [a-z0-9]+)( [2-9]\.[0-1])?$#isU','$2',$val);
		break;
		case 'Factor de forma':
		case 'Formato':
		case 'Format':
			if(preg_match('#E[- ]?ATX#isU',$val))
				$item['format']='E-ATX';
			elseif(preg_match('#XL[- ]?ATX#isU',$val))
				$item['format']='XL-ATX';
			elseif(preg_match('#SSI#isU',$val))
				$item['format']='SSI';
			elseif(preg_match('#mini[- ]?ITX#isU',$val))
				$item['format']='Mini-ITX';
			elseif(preg_match('#Micro[- ]?ATX#isU',$val))
				$item['format']='Micro-ATX';
			elseif(preg_match('#ATX#isU',$val))
				$item['format']='ATX';
		break;
		case 'Socket del procesador':
		case 'Socket du processeur':
		case 'Processor Socket':
		case 'CPU':
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
		case 'Tipo conjunto de chips':
		case 'Chipset':
		case 'Chipset Model':
			if(!isset($item['northbridge']))
			{
				if(preg_match('#^intel [a-z][0-9]{2}( Express)?$#isU',$val))
					$item['northbridge']=preg_match('#^intel ([a-z][0-9]{2}( Express)?)$#isU','Intel $1',$val);
				elseif(preg_match('#^[a-z]{1,2}[0-9]{2}( Express)?$#isU',$val))
					$item['northbridge']=$val;
				elseif(preg_match('#Chipset [a-z]{1,2}[0-9]{2,3}#i',$val) && isset($item['socket']))
				{
					switch($item['socket'])
					{
						case '1156':
						case '1155':
						case '1366':
						case '775':
							$item['northbridge']=preg_replace('#^.*Chipset ([a-z]{1,2}[0-9]{2,3}).*$#isU','Intel $1',$val);
						break;
						case 'AM2':
						case 'AM2+':
						case 'AM3':
						case 'AM3+':
							$item['northbridge']=preg_replace('#^.*Chipset ([a-z]{1,2}[0-9]{2,3}).*$#isU','AMD $1',$val);
						break;
					}
				}
			}
		break;
		case 'Tecnología de RAM admitida':
		case 'Type de mémoire':
		case 'Memory Technology':
			if(preg_match('#G?DDR[1-5]#i',$val))
				$item['memory_type']=preg_replace('#^.*(G?DDR[1-5]).*$#i','$1',$val);
		break;
		case 'Gigabit Ethernet':
			if(preg_match('#^yes$#i',$val))
				$speed_interface=1000;
		break;
		case 'Interfaces de almacenamiento':
		case 'Interfaces':
		case 'Slots de extensión':
			if(preg_match('#(1000 ?Mbps|1 ?Gbps|1000Base-T)#i',$val))
				$speed_interface=1000;
			elseif(preg_match('#(100 ?Mbps|100Base-TX)#i',$val))
				$speed_interface=100;
			elseif(preg_match('#Gigabit Ethernet#i',$val))
				$speed_interface=1000;
			elseif(preg_match('#Fast Ethernet#i',$val))
				$speed_interface=100;
			if(preg_match('#[1-9] x stockage - eSATA#i',$val))
				$item['eSATA']=preg_replace('#^.*([1-9]) x stockage - eSATA.*$#i','$1',$val);
			if(preg_match('#[1-9] x IEEE 1394#i',$val))
				$item['firewire']=preg_replace('#^.*([1-9]) x IEEE 1394.*$#i','$1',$val);
			if(preg_match('#[1-9] x red#i',$val))
				$number_interface=preg_replace('#^.*([1-9]) x red.*$#i','$1',$val);
			if(preg_match('#[1-9] x Hi-Speed USB#i',$val))
				$item['USB']=preg_replace('#^.*([1-9]) x Hi-Speed USB.*$#i','$1',$val);
			if(!isset($item['IDE']))
				if(preg_match('#[1-9] conector IDC#i',$val))
					$item['IDE']=preg_replace('#^.*([1-9]) conector IDC.*$#i','$1',$val);
			if(preg_match('#[^0-9][0-9]{1,2} x Serial ATA#i',$val))
				if(!isset($item['SATA']))
					$item['SATA']=preg_replace('#^.*[^0-9]([0-9]{1,2}) x Serial ATA.*$#i','$1',$val);
				else
					$item['SATA']+=preg_replace('#^.*[^0-9]([0-9]{1,2}) x Serial ATA.*$#i','$1',$val);
			if(preg_match('#[^0-9][0-9]{1,2} x Serial ATA#i',$val))
				if(!isset($item['SATA']))
					$item['SATA']=preg_replace('#^.*[^0-9]([0-9]{1,2}) x Serial ATA.*$#i','$1',$val);
				else
					$item['SATA']+=preg_replace('#^.*[^0-9]([0-9]{1,2}) x Serial ATA.*$#i','$1',$val);
		break;
		case 'Number of SATA Interfaces':
			$item['PCIe_x1']=preg_replace('#^.*([0-9]+).*$#i','$1',$val);
		break;
		case 'USB':
			if(preg_match('#^(.*[^0-9])?[0-9]{1,2} ports USB#i',$val))
				$item['USB']=preg_replace('#^(.*[^0-9])?([0-9]{1,2}) ports USB.*$#i','$2',$val);
		break;
		case 'Interfaces de red':
		case 'Telecom  / Conexión de redes':
		case 'Conexión de redes':
		case 'Interfaces de réseau':
		case 'Connection réseau':
		case 'LAN':
			if(preg_match('#(1000 ?Mbps|1 ?Gbps|1000Base-T)#i',$val))
				$speed_interface=1000;
			elseif(preg_match('#(100 ?Mbps|100Base-TX)#i',$val))
				$speed_interface=100;
			elseif(preg_match('#Gigabit Ethernet#i',$val))
				$speed_interface=1000;
			elseif(preg_match('#Fast Ethernet#i',$val))
				$speed_interface=100;
		break;
		case 'Number of PCI Express x1 Slots':
			$item['PCIe_x1']=preg_replace('#^.*([0-9]+).*$#i','$1',$val);
		break;
		case 'Number of PCI Express x4 Slots':
			$item['PCIe_x4']=preg_replace('#^.*([0-9]+).*$#i','$1',$val);
		break;
		case 'Number of PCI Express x16 Slots':
			$item['PCIe_x16']=preg_replace('#^.*([0-9]+).*$#i','$1',$val);
		break;
		case 'Ranura(s) de expansión':
		case 'PCI':
			if(preg_match('#(.*[^1-8])?[1-8] memoria#i',$val))
				$item['port_memoire']=preg_replace('#^.*(.*[^1-8])?([1-8]) memoria.*$#i','$2',$val);
			if(preg_match('#[1-9] PCI Express( [1-2](\.[0-1])?)? x16#i',$val))
				$item['PCIe_x16']=preg_replace('#^.*([1-9]) PCI Express( [1-2](\.[0-1])?)? x16.*$#i','$1',$val);
			if(preg_match('#[1-9] PCI Express( [1-2](\.[0-1])?)? x4#i',$val))
				$item['PCIe_x4']=preg_replace('#^.*([1-9]) PCI Express( [1-2](\.[0-1])?)? x4.*$#i','$1',$val);
			if(preg_match('#[1-9] PCI Express( [1-2](\.[0-1])?)? x1#i',$val))
				$item['PCIe_x1']=preg_replace('#^.*([1-9]) PCI Express( [1-2](\.[0-1])?)? x1.*$#i','$1',$val);
			if(preg_match('#[1-9] PCI( [^E].*)?$#i',$val))
				$item['PCI']=preg_replace('#^.*([1-9]) PCI( [^E].*)?$#i','$1',$val);
			if(!isset($item['IDE']))
				if(preg_match('#[1-9] conector IDC#i',$val))
					$item['IDE']=preg_replace('#^.*([1-9]) conector IDC.*$#i','$1',$val);
			if(preg_match('#[^0-9][0-9]{1,2} x Serial ATA#i',$val))
				if(!isset($item['SATA']))
					$item['SATA']=preg_replace('#^.*[^0-9]([0-9]{1,2}) x Serial ATA.*$#i','$1',$val);
				else
					$item['SATA']+=preg_replace('#^.*[^0-9]([0-9]{1,2}) x Serial ATA.*$#i','$1',$val);
			if(preg_match('#[^0-9][0-9]{1,2} x Serial ATA#i',$val))
				if(!isset($item['SATA']))
					$item['SATA']=preg_replace('#^.*[^0-9]([0-9]{1,2}) x Serial ATA.*$#i','$1',$val);
				else
					$item['SATA']+=preg_replace('#^.*[^0-9]([0-9]{1,2}) x Serial ATA.*$#i','$1',$val);
		break;
		case 'Audio':
			if(!isset($item['sound']))
				if(preg_match('#^(.*[^2-9])?[2-9] canales de haute définition#isU',$val))
					$item['sound']=preg_match('#^(.*[^2-9])?([2-9]) canales de haute définition#isU','$2',$val);
		break;
		case 'BIOS':
		case 'Caractéristiques du BIOS':
		case 'Type BIOS':
		case 'Características del BIOS':
		case 'Tipo BIOS':
			if(preg_match('#U?EFI #isU',$val))
				$item['UEFI']='yes';
			elseif(!isset($item['UEFI']))
				$item['UEFI']='no';
		break;
				case 'Características de la RAM':
		case 'Audio salida':
		case 'Cumplimiento de normas':
		case 'Tipo de producto':
		case 'Anchura':
		case 'Profundidad':
		case 'Procesadores compatibles':
		case 'Compatibilidad con procesadores de 64 bits':
		case 'Conectores de alimentación':
		case 'Cantidad instalada (máximo soportado)':
		case 'Comprobación de integridad de RAM admitida':
		case 'RAM admitida (registrada o en memoria intermedia)':
		case 'RAM instalada (máx.)':
		case 'Velocidad de la memoria RAM soportada':
		case 'RAM soportada':
		case 'Monitorización de hardware':
		case 'Desactivación / Activación':
		case 'Características de hardware':
		case 'Conectores adicionales (opcional)':
		case 'Accesorios incluidos':
		case 'Cables incluidos':
		case 'Software incluido':
		case 'Cumplimiento de normas':
		case 'Funciones especiales':
		case 'Funciones de overclocking':
		case 'Puertos de E/S trasunknownlib':
		case 'CD de suporte':
		case 'Accesorios':
		case 'Front Side Bus':
		case 'Controlador gráfico':
		case 'Soporte multipolar':
		case 'Velocidad máxima del bus':
		case 'Gestión':
		case 'Tipo':
		case 'Velocidad reloj':
		case 'Funciones RAID':
		case 'Certificado para Windows Vista':
		case 'Supported RAM (Registered or Buffered)':
		case 'Servicio y mantenimiento':
		case 'Detalles de Servicio y Mantenimiento':
		case 'Controlador de almacenamiento':
		case 'Configuración manual':
		case 'Compatible with Windows 7':
		case 'Memoria de vídeo':
		case 'Velocidad bus de datos':
		case 'Conectores de E/S internos':
		case 'Otras características':
		case 'Memoria de vídeo admitida':
		case 'Procesador de señal':
		case 'Compatible con Windows 7':
		case 'Tipo de paquete':
		case 'Almacenamiento':
		case 'Controlador de red':
		case 'Tamaño de RAM máximo asignado':
		case 'Cantidad máxima de procesadores':
		case 'Memoria admitida máxima':
		case 'Peso':
		break;
		default:
			echo 'Unparsed val:'.$index.'<br />'."\n";
			break;
	}
}

if($speed_interface!=0)
{
	if($number_interface==1)
		$item['network']=$speed_interface.' Mbps';
	else
		$item['network']=$number_interface.'x '.$speed_interface.' Mbps';
}
