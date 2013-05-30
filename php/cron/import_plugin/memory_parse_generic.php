<?php
foreach($technical_details_to_parse as $index => $val)
{
	switch($index)
	{
		case 'Capacidad de almacenamiento':
		case 'Capacité de barrette':
		case 'Capacité':
		case 'Number of Modules':
			if(!isset($item['kit']) && !isset($item['size']) && preg_match('#^(.*[^0-9])?[0-9]+ ?x ?[0-9]+ ?G[Bo]#i',$val))
			{
				$item['kit']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?G[Bo]([^a-z0-9].*)?$#i','$2',$val)*1;
				$item['size']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?G[Bo]([^a-z0-9].*)?$#i','$3',$val)*1024;
			}
			elseif(!isset($item['kit']) && !isset($item['size']) && preg_match('#^(.*[^0-9])?[0-9]+ ?x ?[0-9]+ ?M[Bo]([^a-z0-9].*)?$#i',$val))
			{
				$item['kit']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?M[Bo]([^a-z0-9].*)?$#i','$2',$val)*1;
				$item['size']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?([0-9]+) ?M[Bo]([^a-z0-9].*)?$#i','$3',$val)*1;
			}
			elseif(!isset($item['size']) && preg_match('#[0-9]+ ?G[Bo]([^a-z0-9].*)?$#i',$val))
				$item['size']=preg_replace('#^.*([0-9]+) ?G[Bo]([^a-z0-9].*)?$#isU','$1',$val)*1024;
			elseif(!isset($item['size']) && preg_match('#[0-9]+ ?M[Bo]([^a-z0-9].*)?$#i',$val))
				$item['size']=preg_replace('#^.*([0-9]+) ?M[Bo]([^a-z0-9].*)?$#isU','$1',$val)*1;
		break;
		case 'Tipo':
		case 'Tecnología':
		case 'Factor de forma':
		case 'Tipo de módulo':
		case 'Type':
		case 'Memory Technology':
		case 'Technologie':
		case 'Format':
		case 'Type de module':
		case 'Pins ':
		case 'Pins':
		case 'Form Factor':
			if(!isset($item['memory_type']) && preg_match('#(DDR) ?([1-5])#i',$val))
				$item['memory_type']=preg_replace('#^.*(DDR) ?([1-5]).*$#i','$1$2',$val);
			if(!isset($item['memory_type']) && preg_match('#(DDR) ?([1-5])#i',$val))
				$item['memory_type']=preg_replace('#^.*(DDR) ?([1-5]).*$#i','$1$2',$val);
			if(!isset($item['format']) && preg_match('#(so[\- ]?dimm|dimm[\- ]?so)#i',$val))
				$item['format']='So-DIMM';
			elseif(!isset($item['format']) && preg_match('#(fb[\- ]?dimm|dimm[\- ]?fb)#i',$val))
				$item['format']='FB-DIMM';
			elseif(!isset($item['format']) && preg_match('#dimm#isU',$val))
				$item['format']='DIMM';
		break;
		case 'Velocidad de memoria':
		case 'Velocidad del reloj':
		case 'Capacidad':
		case 'Vitesse mémoire':
		case 'Fréquence':
		case 'Memory Speed':
			if(!isset($item['frequency']))
			{
				if(preg_match('#^(.*[^0-9])?[0-9]{3,4} ?Mhz([^0-9].*)?$#i',$val))
					$item['frequency']=(int)preg_replace('#^(.*[^0-9])?([0-9]{3,4}) ?Mhz([^0-9].*)?$#i','$2',$val);
				elseif(preg_match('#DDR3[- ]+[0-9]{3,5}([^0-9].*)?$#i',$val))
					$item['frequency']=(int)preg_replace('#^.*DDR3[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$val);
				elseif(preg_match('#DDR2[- ]+[0-9]{3,5}([^0-9].*)?$#i',$val))
					$item['frequency']=(int)preg_replace('#^.*DDR2[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$val);
				elseif(preg_match('#DDR1[- ]+[0-9]{3,5}([^0-9].*)?$#i',$val))
					$item['frequency']=(int)preg_replace('#^.*DDR1[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$val);
				elseif(preg_match('#DDR[- ]*[0-9]{3,5}([^0-9].*)?$#i',$val))
					$item['frequency']=(int)preg_replace('#^.*DDR[- ]*([0-9]{3,5})([^0-9].*)?$#isU','$1',$val);
				elseif(preg_match('#PC3[- ]+[0-9]{3,5}([^0-9].*)?$#i',$val))
					$item['frequency']=(int)(preg_replace('#^.*PC3[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$val)/8);
				elseif(preg_match('#PC2[- ]+[0-9]{3,5}([^0-9].*)?$#i',$val))
					$item['frequency']=(int)(preg_replace('#^.*PC2[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$val)/8);
				elseif(preg_match('#PC1[- ]+[0-9]{3,5}([^0-9].*)?$#i',$val))
					$item['frequency']=(int)(preg_replace('#^.*PC1[- ]+([0-9]{3,5})([^0-9].*)?$#isU','$1',$val)/8);
				elseif(preg_match('#PC[- ]*[0-9]{3,5}([^0-9].*)?$#i',$val))
					$item['frequency']=(int)(preg_replace('#^.*PC[- ]*([0-9]{3,5})([^0-9].*)?$#isU','$1',$val)/8);
				if(isset($item['frequency']))
					if($item['frequency']==662)
						$item['frequency']=667;
			}
		break;
		case 'Tiempos de latencia':
		case 'Tasa de transferencia de datos':
		case 'Latencia CAS':
		case 'Latence CAS':
		case 'CAS Latency':
			if(!isset($item['cas']) && preg_match('#CL[0-9]{1,2}([^0-9].*)?$#i',$val))
				$item['cas']=preg_replace('#^.*CL([0-9]{1,2})([^0-9].*)?$#i','$1',$val)*1;
		break;
		case 'Intégrité':
			if(!isset($item['ecc']) && preg_match('#no[a-z]?[ \-]?ecc#i',$val))
				$item['ecc']=false;
			elseif(!isset($item['ecc']) && preg_match('#ecc#i',$val))
				$item['ecc']=true;
		break;
		case 'Tension':
			if(!isset($item['voltage']) && preg_match('#^(.*[^0-9])?[1-2][\.,][0-9]{1,2} ?V([^a-z].*)?$#i',$val))
				$item['voltage']=preg_replace('#^(.*[^0-9])?([1-2])[\.,]([0-9]{1,2}) ?V([^a-z].*)?$#i','$2.$3',$val)*1;
		break;
		case 'Kit':
			if(!isset($item['kit']) && preg_match('#^(.*[^0-9])?[0-9]+ ?x ?memoria#i',$val))
				$item['kit']=preg_replace('#^(.*[^0-9])?([0-9]+) ?x ?memoria#i','$2',$val)*1;
		break;
		default:
			echo 'Unparsed val:'.$index.'<br />'."\n";
		break;
	}
}
