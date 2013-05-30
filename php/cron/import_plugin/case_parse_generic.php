<?php
if(isset($technical_details_to_parse['Width']) && isset($technical_details_to_parse['Depth']) && isset($technical_details_to_parse['Height']) && !isset($item['dimension']))
{
	if(preg_match('#^[0-9]+ ?cm$#',$technical_details_to_parse['Width']) && preg_match('#^[0-9]+ ?cm$#',$technical_details_to_parse['Depth']) && preg_match('#^[0-9]+ ?cm$#',$technical_details_to_parse['Height']))
	{
		$Width=preg_replace('#^([0-9]+) ?cm$#','$1',$technical_details_to_parse['Width']);
		$Depth=preg_replace('#^([0-9]+) ?cm$#','$1',$technical_details_to_parse['Depth']);
		$Height=preg_replace('#^([0-9]+) ?cm$#','$1',$technical_details_to_parse['Height']);
		$item['dimension']=$Width.'x'.$Depth.'x'.$Height.'cm';
	}
}

foreach($technical_details_to_parse as $index => $val)
{
	switch($index)
	{
		case 'Format de la tour':
		case 'Formato de la carcasa':
		case 'Factor de forma':
			if(!isset($item['format']))
			{
				if(preg_match('#Tour moyenne#i',$val))
					$item['format']='Tour moyenne';
				elseif(preg_match('#Grande tour#i',$val))
					$item['format']='Grande tour';
				elseif(preg_match('#Micro tour#i',$val))
					$item['format']='Micro tour';
				elseif(preg_match('#^Tour$#i',$val))
					$item['format']='Tour media';
			}
		break;
		case 'Material de la carcasa':
		case 'Matiére de la tour':
			if(!isset($item['matiere']))
			{
				if(preg_match('#Acier#i',$val) && preg_match('#plastique#i',$val))
					$item['matiere']='Acier et plastique';
				elseif(preg_match('#Acier#i',$val))
					$item['matiere']='Acier';
			}
		case 'Color':
		case 'Couleur':
			if(!isset($item['color']))
			{
				if(preg_match('#Blanc#i',$val))
					$item['color']='Blanc';
				elseif(preg_match('#Noir#i',$val))
					$item['color']='Noir';
			}
		break;
		case 'Dimensiones':
		case 'Dimensions':
			if(!isset($item['dimension']))
			{
				$temp_dimension=$val;
				$temp_dimension=str_replace(',','.',$temp_dimension);
				if(preg_match('#^[0-9]+(\.[0-9])? ?x ?[0-9]+(\.[0-9])? ?x ?[0-9]+(\.[0-9])? ?cm$#i',$temp_dimension))
					$item['dimension']=preg_replace('#^([0-9]+(\.[0-9])?) ?x ?([0-9]+(\.[0-9])?) ?x ?([0-9]+(\.[0-9])?) ?cm$#i','$1x$3x$5cm',$temp_dimension);
			}
		break;
		case 'Peso':
		case 'Poids':
			if(!isset($item['weight']))
			{
				$temp_weight=$val;
				$temp_weight=str_replace(',','.',$temp_weight);
				if(preg_match('#^[0-9]+(\.[0-9])? ?kg$#i',$temp_weight))
					$item['weight']=preg_replace('#^([0-9]+(\.[0-9])?) ?kg$#i','$1',$temp_weight);
			}
		break;
		case 'Norma de alimentación':
		case 'Casillas frontales de 3.5':
		case 'Casillas internas de 3.5':
		case 'Casillas frontales de 5.25':
		case 'Casillas  internas de 3.5':
		case 'Cantidad de compartimentos internos':
		case 'Cantidad de compartimentos frontales':
		case 'Anchura':
		case 'Profundidad':
		case 'Altura':
		case 'Total compartimentos de expansión (libres)':
		case 'Interfaces':
		case 'Dispositivo de alimentación':
		case 'Cantidad instalada':
		case 'Cantidad máxima soportada':
		case 'Potencia suministrada':
		case 'Tamaño máximo de placa base':
		case 'Placas base soportadas':
		case 'Sistema de refrigeración':
		case 'Propiedades de la caja del sistema':
		case 'Total ranuras de expansión (libres)':
		case 'Cumplimiento de normas':
		case 'Ventiladores':
		case 'Conformidad con las especificaciones':
		case 'Cantidad de compartimentos de intercambio rápido (hot-swap)':
		case 'Interfaz de unidad conectable de servidor':
		case 'Localización':
		case 'Servicio y mantenimiento':
		case 'Detalles de Servicio y Mantenimiento':
		case 'Potencia de alimentación':
		case 'Otras características':
		case 'Voltaje necesario':
		case 'Accesorios incluidos':
		case 'Compartimentos de expansión':
		case 'Ranura(s) de expansión':
		case 'Propiedades de fuente de alimentación':
		break;
		default:
			echo 'Unparsed val:'.$index.'<br />'."\n";
			break;
	}
}

