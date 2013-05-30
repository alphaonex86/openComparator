<?php
//administrator informations
$GLOBALS['unknownlib']['site']['email_admin_name']='Administrator';
$GLOBALS['unknownlib']['site']['email_admin']='admin@yoursite.com';
$GLOBALS['unknownlib']['site']['external_feed_url']='http://feeds.feedburner.com/pcworld-fr/tout.xml?format=xml';
$GLOBALS['unknownlib']['site']['min_note_count']=3;
$GLOBALS['unknownlib']['site']['do_cache']=false;
$GLOBALS['unknownlib']['site']['show_without_thumb']=false;
$GLOBALS['unknownlib']['site']['price_unit']='BTC';

$GLOBALS['unknownlib']['site']['external_image_download']=true;
$GLOBALS['unknownlib']['site']['external_call']=false;
$GLOBALS['unknownlib']['site']['save_debug_file']=true;
//categories information
$GLOBALS['unknownlib']['site']['reverse_link']=array(
'processeurs'=>'composants',
'carte-graphiques'=>'composants',
'carte-meres'=>'composants',
'memoire'=>'composants',
'alimentations'=>'composants',
'tours'=>'composants',
);
$GLOBALS['unknownlib']['site']['sub_cat_translation']=array(
'processor'=>'processeurs',
'graphiccard'=>'carte-graphiques',
'motherboard'=>'carte-meres',
'memory'=>'memoire',
'powersupply'=>'alimentations',
'cases'=>'tours',
);
$GLOBALS['unknownlib']['site']['static_convert_rates_to_BTC']=array(
'EUR'=>0.0102,
);
$GLOBALS['unknownlib']['site']['categories']=array(
	'composants'=>array(
		'title'=>'Composants',
		'page_title'=>'Composants d\'ordinateur',
		'page_sub_title'=>'Composants d\'ordinateur, les prix les plus bas',
		'page_desc'=>'Vous trouverez içi les composants pour votre pc selon tout les besoins',
		'page_keywords'=>'Composants d\'ordinateur,Composants',
		'thumb'=>'/images/cat_main/components.png',
		'sub_cat'=>array(
			'processeurs'=>array(
				'page_title'=>'Processeur: acheter au meilleur prix',
				'page_desc'=>'Le processeur est le cerveux de votre ordinateur',
				'page_keywords'=>'processeurs',
				'title'=>'Processeurs',
				'on_main_page'=>true,
				'english_name'=>'processor',
				'spec'=>array(
					'socket'=>array(
						'title'=>'Socket',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'frequency'=>array(
						'title'=>'Fréquence',
						'filter_on_interface_sort_by'=>'DESC',
						'unit'=>'MHz',
					),
					'nm'=>array(
						'title'=>'Gravure',
						'filter_on_interface_sort_by'=>'ASC',
						'unit'=>'nm',
					),
					'nbr_core'=>array(
						'title'=>'Nombre de coeur',
						'filter_on_interface_sort_by'=>'DESC',
						'unit'=>'',
					),
					'TDP'=>array(
						'title'=>'TDP',
						'unit'=>'W',
					),
					'L2'=>array(
						'title'=>'Cache L2',
						'unit'=>'KB',
					),
					'L3'=>array(
						'title'=>'Cache L3',
						'unit'=>'KB',
					),
				),
			),
			'carte-graphiques'=>array(
				'page_title'=>'Carte graphiques: acheter au meilleur prix',
				'page_desc'=>'Vous aimer miner? Ou jouer? Acheter vous une bonne carte graphique',
				'page_keywords'=>'3D,carte graphique',
				'title'=>'Carte graphiques',
				'on_main_page'=>true,
				'english_name'=>'graphiccard',
				'spec'=>array(
					'familly'=>array(
						'title'=>'Famille',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'memory_type'=>array(
						'title'=>'Type de mémoire',
					),
					'memory'=>array(
						'title'=>'Mémoire',
						'filter_on_interface_sort_by'=>'ASC',
						'unit'=>'MB',
					),
					'bus'=>array(
						'title'=>'Type d\'interface',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'cooling_type'=>array(
						'title'=>'Type de refroidissement',
					),
					'frequency_gpu'=>array(
						'title'=>'Fréquence GPU',
						'unit'=>'Mhz',
					),
					'frequency_memory'=>array(
						'title'=>'Fréquence mémoire',
						'unit'=>'Mhz',
					),
					'output_video'=>array(
						'title'=>'Sortie vidéo',
					),
				),
			),
			'carte-meres'=>array(
				'page_title'=>'Carte mère: acheter au meilleur prix',
				'page_desc'=>'La placa madre es el vínculo de todos los composants de un ordenador.',
				'page_keywords'=>'placas base',
				'title'=>'Carte mère',
				'on_main_page'=>true,
				'english_name'=>'motherboard',
				'spec'=>array(
					'socket'=>array(
						'title'=>'Socket',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'memory_type'=>array(
						'title'=>'Type de mémoire',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'format'=>array(
						'title'=>'Format',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'UEFI'=>array(
						'title'=>'UEFI',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'northbridge'=>array(
						'title'=>'Chipset northbridge',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'port_memoire'=>array(
						'title'=>'Port mémoire',
					),
					'PCIe_x16'=>array(
						'title'=>'Port PCI Express x16',
					),
					'PCIe_x4'=>array(
						'title'=>'Port PCI Express x4',
					),
					'PCIe_x1'=>array(
						'title'=>'Port PCI Express x1',
					),
					'PCI'=>array(
						'title'=>'Port PCI',
					),
					'IDE'=>array(
						'title'=>'Port IDE',
					),
					'SATA'=>array(
						'title'=>'Port SATA',
					),
					'eSATA'=>array(
						'title'=>'Port eSATA',
					),
					'SCSI'=>array(
						'title'=>'Port SCSI/SAS',
					),
					'RAID'=>array(
						'title'=>'Controleur RAID',
					),
					'USB'=>array(
						'title'=>'Port USB',
					),
					'firewire'=>array(
						'title'=>'Port Firewire',
					),
					'sound'=>array(
						'title'=>'Sortie audio',
					),
					'sound_codec'=>array(
						'title'=>'Codec de audio',
					),
					'network'=>array(
						'title'=>'Controleur réseau',
					),
				),
			),
			'memoire'=>array(
				'page_title'=>'Mémoire: acheter au meilleur prix',
				'page_desc'=>'Votre ordinateur ralenti? Rajoutez lui de la mémoire',
				'page_keywords'=>'mémoire',
				'title'=>'Mémoire',
				'on_main_page'=>true,
				'english_name'=>'memory',
				'spec'=>array(
					'memory_type'=>array(
						'title'=>'Type de mémoire',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'frequency'=>array(
						'title'=>'Fréquence',
						'filter_on_interface_sort_by'=>'ASC',
						'unit'=>'Mhz',
					),
					'size'=>array(
						'title'=>'Capacité par unité',
						'filter_on_interface_sort_by'=>'ASC',
						'unit'=>'Mo',
					),
					'ecc'=>array(
						'title'=>'Ecc',
						'unit'=>'',
					),
					'format'=>array(
						'title'=>'Format',
					),
					'kit'=>array(
						'title'=>'Kit',
						'unit'=>'',
					),
					'cas'=>array(
						'title'=>'Cas',
					),
					'voltage'=>array(
						'title'=>'Tension',
					),
				),
			),
			'alimentations'=>array(
				'page_title'=>'Alimentations: acheter au meilleur prix',
				'page_desc'=>'Une alimentation stable est une bonne garantie de durée de vie de votre pc',
				'page_keywords'=>'alimentations',
				'title'=>'Alimentations',
				'on_main_page'=>false,
				'english_name'=>'powersupply',
				'spec'=>array(
					'power'=>array(
						'title'=>'Puissance',
						'filter_on_interface_sort_by'=>'ASC',
						'unit'=>'W',
					),
					'modulaire'=>array(
						'title'=>'Modulaire',
					),
					'certification'=>array(
						'title'=>'Certification',
					),
				),
			),
			'tours'=>array(
				'page_title'=>'Tours: acheter au meilleur prix',
				'page_desc'=>'Ayer une belle tour pour les soirées lan avec les copains',
				'page_keywords'=>'tours',
				'title'=>'Tours',
				'on_main_page'=>false,
				'english_name'=>'cases',
				'spec'=>array(
					'format'=>array(
						'title'=>'Format',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'matiere'=>array(
						'title'=>'Matiére',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'color'=>array(
						'title'=>'Couleur',
						'filter_on_interface_sort_by'=>'ASC',
					),
					'dimension'=>array(
						'title'=>'Dimensions',
					),
					'weight'=>array(
						'title'=>'Poids',
						'unit'=>'kg',
					),
				),
			),
		)
	),
);
?>
