<?php
foreach($technical_details_to_parse as $index => $val)
{
	switch($index)
	{
		case 'Norma de alimentación':
		case 'Potencia suministrada':
		case 'Potencia':
		case 'Norme d\'alimentation':
		case 'Puissance':
			if(!isset($item['power']))
			{
				if(preg_match('#[^a-zA-Z0-9]1?[0-9]+ ?(W|watts)#i',$val))
					$item['power']=preg_replace('#^.*[^a-zA-Z0-9](1?[0-9]+) ?(W|watts).*$#isU','$1',$val);
			}
		break;
		case 'Cumplimiento de normas':
		case 'Certification':
			if(!isset($item['certification']))
			{
				if(preg_match('#80 ?(PLUS|\+) Gold#i',$val))
					$item['certification']='80+ Gold';
				elseif(preg_match('#80 ?(PLUS|\+) platinum#i',$val))
					$item['certification']='80+ Platinum';
				elseif(preg_match('#80 ?(PLUS|\+) Silver#i',$val))
					$item['certification']='80+ Silver';
				elseif(preg_match('#80 ?(PLUS|\+) Bronze#i',$val))
					$item['certification']='80+ Bronze';
				elseif(preg_match('#80 ?(PLUS|\+)#i',$val))
					$item['certification']='80+';
			}
		break;
		case 'Connexion de sortie':
		case 'Type de dipositif':
		case 'Conformité avec les spécifications':
		case 'Largeur':
		case 'Hauteur':
		case 'Profondeur':
		case 'Poids':
		case 'Tension d\'entrée':
		case 'Fréquence requise':
		case 'Connecteur d\'entrée':
		case 'Tension de sortie':
		case 'Courrant de sortie':
		case 'Systéme de refrondissement':
		case 'Conexión(ones) de salida':
		case 'Tipo de dispositivo':
		case 'Conformidad con las especificaciones':
		case 'Anchura':
		case 'Profundidad':
		case 'Altura':
		case 'Peso':
		case 'Localización':
		case 'Voltaje de entrada':
		case 'Frecuencia requerida':
		case 'Conector(es) de entrada':
		case 'Voltaje de salida':
		case 'Datos de los conectores de salida de corriente':
		case 'Corriente de salida':
		case 'Sistema de refrigeración':
		case 'Características':
		case 'Margen de voltaje de entrada':
		case 'Accesorios incluidos':
		case 'MTBF (tiempo medio entre errores)':
		case 'MTBF':
		case 'Temperatura mínima de funcionamiento':
		case 'Temperatura máxima de funcionamiento':
		case 'Ámbito de humedad de funcionamiento':
		case 'Potencia de alimentación':
		case 'Servicio y mantenimiento':
		case 'Detalles de Servicio y Mantenimiento':
		case 'Anchura de embalaje':
		case 'Profundidad de embalaje':
		case 'Altura de embalaje':
		case 'Peso de embalaje':
		case 'Corriente eléctrica máxima':
		case 'Supresión de sobrevoltaje':
		case 'Color incluido':
		case 'Cables incluidos':
		case 'Certificación ENERGY STAR':
		case 'Conector/es de salida':
		case 'Emisión de sonido':
		case 'De acuerdo con EPA Energy Star':
		case 'Anchura':
		break;
		default:
			echo 'Unparsed val:'.$index.'<br />'."\n";
			break;
	}
}

