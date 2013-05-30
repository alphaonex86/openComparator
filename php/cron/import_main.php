<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta name="Language" content="en" />
<meta http-equiv="content-language" content="english" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Product importer</title>
</head>
<body>
<?php
//include here the core function only function
require 'unknownlib-function.php';

$start_time=unknownlib_get_microtime();

//include here all the config file
require '../../config/general.php';
require '../../config/mysql.php';
unknownlib_check_unknownlib_config();
//include all the unknownlib modules needed and the the linked global variable
require_once '../../lib/unknownlib-cache.php';
require_once '../../lib/unknownlib-mysql.php';
require_once '../../lib/unknownlib-comparator.php';
require_once '../../lib/unknownlib-text-operation.php';
require_once '../../lib/unknownlib-url.php';
require_once '../../lib/unknownlib-images.php';
unknownlib_mysql_connect_or_quit();

$_SERVER['DOCUMENT_ROOT']='../../';

function type_to_sub_cat($type)
{
	$table_product=type_to_real_type($type);
	if($type=='')
		return '';
	$translated_sub_cat=$GLOBALS['unknownlib']['site']['sub_cat_translation'][$table_product];
	$cat=$GLOBALS['unknownlib']['site']['reverse_link'][$translated_sub_cat];
	$sub_cat=$GLOBALS['unknownlib']['site']['categories'][$cat]['sub_cat'][$translated_sub_cat];
	return $sub_cat;
}

function type_to_real_type($type)
{
	switch($type)
	{
		case 'cpu':
			$table_product='processor';
		break;
		case 'gpu':
			$table_product='graphiccard';
		break;
		case 'motherboard':
			$table_product='motherboard';
		break;
		case 'memory':
			$table_product='memory';
		break;
		case 'case':
			$table_product='cases';
		break;
		case 'power_supply':
			$table_product='powersupply';
		break;
		default:
			echo '<span style="color:#f00">Error, type unknow: '.$type.'</span><br />'."\n";
			return '';
	}
	return $table_product;
}

$unique_identifier_product_to_remove=array();
$reply = unknownlib_mysql_query('SELECT * FROM `product_base_information`');
while($data = mysql_fetch_array($reply))
	$unique_identifier_product_to_remove[$data['unique_identifier']]=true;

$reply_shop_list = unknownlib_mysql_query('SELECT * FROM `shop`');
while($data_shop_list = mysql_fetch_array($reply_shop_list))
{
	if(file_exists('shop_plugin/'.$data_shop_list['url_alias_for_seo'].'.php'))
	{
		if(isset($content))
			unset($content);
		if(isset($content_temp))
			unset($content_temp);
		echo '<hr /><h1>Load the plugin: '.$data_shop_list['url_alias_for_seo'].'</h1><hr />';
		$content_temp=unknownlib_urlopen($data_shop_list['url_product_pool']);
		$content_temp=unknownlib_text_auto_encoding_to_utf8($content_temp);
		require 'shop_plugin/'.$data_shop_list['url_alias_for_seo'].'.php';
		unset($content_temp);
		$unique_identifier_prices=array();
		$reply_prices = unknownlib_mysql_query('SELECT * FROM `prices` WHERE `id_shop`='.$data_shop_list['id']);
		while($data_price = mysql_fetch_array($reply_prices))
			$unique_identifier_prices[$data_price['unique_identifier_product']]=true;
		$new_content=array();
		if(isset($content))
		{
			foreach($content as $item)
			{
				if(isset($item['title']) && isset($item['description']) && strlen($item['title'])>=3 && !preg_match('#[<>]#',$item['title']))
				{
					if(strlen($item['mark'])>32)
					{
						echo 'product_manufacturer too big';
						continue;
					}
					$item['title']=' '.$item['title'].' ';
					$item['mark']=preg_replace('#SAPPHIRE TECHNOLOGY( PURE)?#i','Sapphire',$item['mark']);
					if(preg_match('#Gigabyte#isU',$item['mark']))
						$item['mark']='Gigabyte';
					if(preg_match('#(Micro-StarInternational|MSI)#isU',$item['mark']))
						$item['mark']='MSI';
					if(preg_match('#Kingston#isU',$item['mark']))
						$item['mark']='Kingston';
					if(preg_match('#DiamondMultimedia#isU',$item['mark']))
						$item['mark']='Diamond';
					if(preg_match('#PNY#isU',$item['mark']))
						$item['mark']='PNY';
					if(preg_match('#Visiontek#isU',$item['mark']))
						$item['mark']='Visiontek';
					if(preg_match('#EVGA#isU',$item['mark']))
						$item['mark']='EVGA';
					if(preg_match('#Asus#isU',$item['mark']))
						$item['mark']='Asus';
					if(preg_match('#XFX#isU',$item['mark']))
						$item['mark']='XFX';
					$item['title']=preg_replace('#SAPPHIRE TECHNOLOGY( PURE)?#i','Sapphire',$item['title']);
					$item['title']=html_entity_decode($item['title']);
					$item['mark']=unknownlib_text_operation_lower_case_first_letter_upper($item['mark']);
					if($item['mark']!='')
						$item['title']=preg_replace('#'.preg_quote($item['mark']).'#i',' ',$item['title']);
					$temp_sub_cat=type_to_sub_cat($item['type']);
					$sub_cat_title=preg_replace('#s$#','',$temp_sub_cat['title']);
					unset($temp_sub_cat);
					$item['title']=preg_replace('#'.preg_quote($sub_cat_title).'s?#i',' ',$item['title']);
					
					if(isset($item['type']))
					{
						$item['full_description_manual']=' '.$item['title'].' - '.$item['description'].' ';
						if(file_exists('shop_plugin/'.$data_shop_list['url_alias_for_seo'].'/additional_parsing/'.$item['type'].'.php'))
							include 'shop_plugin/'.$data_shop_list['url_alias_for_seo'].'/additional_parsing/'.$item['type'].'.php';
						require 'import_plugin/'.$item['type'].'.php';
						$item['ref']=preg_replace('#^ *- *#',' ',$item['ref']);
						$item['ref']=preg_replace('#^ *[1-8] *x *#',' ',$item['ref']);
						$item['ref']=preg_replace('# +#',' ',$item['ref']);
						$item['ref']=preg_replace('# ?[/\-] ?$#','',$item['ref']);
						$item['ref']=preg_replace('#^ *#','',$item['ref']);
						$item['ref']=preg_replace('#[\. ]*$#','',$item['ref']);
						$item['ref']=unknownlib_text_auto_encoding_to_utf8($item['ref']);
						$new_content[]=$item;
					}
				}
			}
			unset($content);
		}
		echo 'number of product: '.count($new_content).'<br />'."\n";
		$a_number=0;
		foreach($new_content as $item)
		{
			if(isset($item['product_code']) && isset($item['price']) && isset($item['ref']) && $item['ref']!='' && isset($item['type']) && isset($item['url']) && $item['url']!='' && isset($item['mark']))
			{
				$sub_cat_title=$GLOBALS['unknownlib']['site']['sub_cat_translation'][type_to_real_type($item['type'])];
				require 'import_plugin/mark_parsing.php';
				$item['unique_identifier']=$item['mark'].'-'.$item['product_code'];
				$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `unique_identifier`=\''.addslashes($item['unique_identifier']).'\'');
				if($data_product = mysql_fetch_array($reply_product))
				{
					if($data_product['last_time_not_used']>0)
					{
						unknownlib_mysql_query('UPDATE `output_product` SET `last_time_not_used`=0 WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
						unknownlib_mysql_query('UPDATE `product_base_information` SET `last_time_not_used`=0 WHERE `unique_identifier`=\''.addslashes($data_product['unique_identifier']).'\'');
					}
					echo 'Parse item ('.$a_number.') type: '.$item['type'].'<br />'."\n";$a_number++;
					$table_product=$data_product['table_product'];
					$seo=$data_product['url_alias_for_seo'];
					//if($data_product['title']=='' && isset($item['ref']) && $item['ref']!='')
					//	unknownlib_mysql_query('UPDATE `product_base_information` SET `title`=\''.addslashes($item['ref']).'\' WHERE `unique_identifier`=\''.addslashes($item['unique_identifier']).'\'');
					$id_product=$data_product['id'];
					$unique_identifier_product=$data_product['unique_identifier'];
				}
				else
				{
					switch($item['type'])
					{
						case 'cpu':
							$table_product='processor';
						break;
						case 'gpu':
							$table_product='graphiccard';
						break;
						case 'motherboard':
							$table_product='motherboard';
						break;
						case 'memory':
							$table_product='memory';
						break;
						case 'case':
							$table_product='cases';
						break;
						case 'power_supply':
							$table_product='powersupply';
						break;
						default:
							echo '<span style="color:#f00">Error, type unknow: '.$item['type'].'</span><br />'."\n";
							continue;
					}
					echo 'SELECT * FROM `product_base_information` WHERE `title`=\''.addslashes($item['ref']).'\'<br />'."\n";
					$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `title`=\''.addslashes($item['ref']).'\'');
					if($data_product = mysql_fetch_array($reply_product))
					{
						$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `title`=\''.addslashes($item['ref']).'\' AND `unique_identifier`=\''.addslashes($item['unique_identifier']).'\'');
						if($data_product = mysql_fetch_array($reply_product))
							unknownlib_mysql_query('INSERT INTO `product_base_information`(`table_product`,`title`,`mark`,`unique_identifier`,`product_code`) VALUES (\''.$table_product.'\',\''.addslashes($item['ref']).'\',\''.$item['mark'].'\',\''.addslashes($item['unique_identifier']).'\',\''.addslashes($item['product_code']).'\')');
						$GLOBALS['unknownlib']['debug_temp']='other insert done';
						echo 'other insert done<br />'."\n";
						continue;
					}
					else
						unknownlib_mysql_query('INSERT INTO `product_base_information`(`table_product`,`title`,`mark`,`date`,`unique_identifier`,`product_code`) VALUES (\''.$table_product.'\',\''.addslashes($item['ref']).'\',\''.$item['mark'].'\','.time().',\''.addslashes($item['unique_identifier']).'\',\''.addslashes($item['product_code']).'\')');
					$id_product=mysql_insert_id();
					$unique_identifier_product=$item['unique_identifier'];
					$seo=unknownlib_url_load_alias_of_item_mysql('product_base_information',$id_product,$item['mark'].' '.$item['ref'],$table_product,'',1);
				}
				$reply_product_given = unknownlib_mysql_query('SELECT * FROM `product_base_information_given` WHERE `unique_identifier`=\''.addslashes($item['unique_identifier']).'\' AND `shop_id`='.$data_shop_list['id']);
				if($data_product_given = mysql_fetch_array($reply_product_given))
				{
					$title=@unserialize($data_product_given['title']);
					if(!is_array($title))
						$title=array();
					if(!in_array($item['ref'],$title))
					{
						$title[]=$item['ref'];
						unknownlib_mysql_query('UPDATE `product_base_information_given` SET `title`=\''.addslashes(serialize($title)).'\' WHERE `unique_identifier`=\''.$unique_identifier_product.'\' AND `shop_id`='.$data_shop_list['id']);
					}
				}
				else
					unknownlib_mysql_query('INSERT INTO `product_base_information_given`(`title`,`unique_identifier`,`shop_id`) VALUES (\''.addslashes(serialize($item['ref'])).'\',\''.addslashes($item['unique_identifier']).'\','.$data_shop_list['id'].')');

				unset($unique_identifier_product_to_remove[$item['unique_identifier']]);
				if(isset($item['ean']) && $item['ean']!='')
					unknownlib_mysql_query('UPDATE `product_base_information` SET `ean`=\''.addslashes($item['ean']).'\' WHERE `unique_identifier`=\''.addslashes($item['unique_identifier']).'\' AND `ean`=\'\'');
				$reply_price = unknownlib_mysql_query('SELECT * FROM `prices` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
				if($data_price = mysql_fetch_array($reply_price))
				{
					echo '('.$unique_identifier_product.') Price already found -> updating (shop: '.$data_shop_list['id'].')<br />'."\n";
					unset($unique_identifier_prices[$unique_identifier_product]);
					unknownlib_mysql_query('UPDATE `prices` SET `price`='.$item['price'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
					unknownlib_mysql_query('UPDATE `prices` SET `url`=\''.addslashes($item['url']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
					unknownlib_mysql_query('UPDATE `prices` SET `date`='.time().' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
					$destination=$_SERVER['DOCUMENT_ROOT'].'/'.$sub_cat_title.'/'.$seo.'.jpg';
					$destination_mini=$_SERVER['DOCUMENT_ROOT'].'/'.$sub_cat_title.'/'.$seo.'-mini.jpg';
					if(isset($item['thumb']) && !preg_match('#\.gif$#',$item['thumb']))
					{
						$url_thumb_actual=$data_price['url_thumb'];
						if($item['thumb']!=$data_price['url_thumb'])
						{
							if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$sub_cat_title.'/'))
								mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$sub_cat_title.'/');
							unknownlib_mysql_query('UPDATE `prices` SET `url_thumb`=\''.addslashes($item['thumb']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
							if($GLOBALS['unknownlib']['site']['external_image_download'])
							{
								if($have_big_thumbs)
									//never update is change url but have image
									if(!file_exists($destination) || !file_exists($destination_mini))
										//check thumb exists by file exists, because each shop can have thein own thumb
										unknownlib_image_download_scaled_and_cropped_multi($item['thumb'],array(
											array('path_destination'=>$destination,'target_width'=>130,'target_height'=>130),
											array('path_destination'=>$destination_mini,'target_width'=>64,'target_height'=>64),
										),95,false);
								else
								{
									//never update is change url but have image
									if(!file_exists($destination_mini))
									//check thumb exists by file exists, because each shop can have thein own thumb
										unknownlib_image_download_scaled_and_cropped($item['thumb'],$destination_mini,64,64);
								}
							}
						}
					}
					if(file_exists($destination) && file_exists($destination_mini))
						unknownlib_mysql_query('UPDATE `product_base_information` SET `have_thumb`=1 WHERE `unique_identifier`=\''.$unique_identifier_product.'\'');
					if(isset($item['page_technical_details']) && $item['page_technical_details']!='' && $item['page_technical_details']!=$data_price['url_technical_details'] && $GLOBALS['unknownlib']['site']['external_call'])
					{
						echo 'first: '.$item['page_technical_details'].'!='.$data_price['url_technical_details'].'<br />'."\n";
						$individual_data='../../individual_data/'.$data_shop_list['url_alias_for_seo'].'/'.str_replace('/','-',str_replace('\\','-',$item['unique_identifier'])).'.data';
						unset($content_temp);
						if(file_exists($individual_data))
							$content_temp=file_get_contents($individual_data);
						else
						{
							echo '1) open external url: "'.$item['page_technical_details'].'", type: '.$item['type'].'<br />'."\n";
							$content_temp=unknownlib_urlopen($item['page_technical_details']);
							$content_temp=unknownlib_text_auto_encoding_to_utf8($content_temp);
							if(!preg_match('#^ *$#',$content_temp))
							{
								$content_temp=unknownlib_text_auto_encoding_to_utf8($content_temp);
								if(!is_dir('../../individual_data/'))
									mkdir('../../individual_data/');
								if(!is_dir('../../individual_data/'.$data_shop_list['url_alias_for_seo']))
									mkdir('../../individual_data/'.$data_shop_list['url_alias_for_seo']);
								unknownlib_filewrite($individual_data,$content_temp);
							}
							else
								echo 'Empty: '.$item['page_technical_details'].'<br />'."\n";
						}
						if(isset($content_temp))
						{
							unknownlib_mysql_query('UPDATE `prices` SET `url_technical_details`=\''.addslashes($item['page_technical_details']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
							require 'shop_plugin/'.$data_shop_list['url_alias_for_seo'].'/technical_details/'.$item['type'].'.php';
							require 'import_plugin/'.$item['type'].'_parse_generic.php';
							if(isset($technical_details_to_parse))
								echo '<pre>';print_r($technical_details_to_parse);echo '</pre>';
							echo 'item: <pre>';print_r($item);echo '</pre>';
							unset($content_temp);
						}
					}
				}
				else
				{
					echo 'Insert new price<br />'."\n";
					$continue_price_import=true;
					$reply_price = unknownlib_mysql_query('SELECT * FROM `prices` WHERE `url`=\''.addslashes($item['url']).'\'');
					if($data_price = mysql_fetch_array($reply_price))
					{
						if($data_price['id_shop']==$data_shop_list['id'])
							unknownlib_mysql_query('DELETE FROM `prices` WHERE `id`='.$data_price['id']);
						else
							$continue_price_import=false;
					}
					if($continue_price_import)
					{
						unknownlib_mysql_query('INSERT INTO `prices`(`unique_identifier_product`,`id_shop`,`price`,`url`,`date`,`delivery`) VALUES (\''.$unique_identifier_product.'\','.$data_shop_list['id'].','.$item['price'].',\''.addslashes($item['url']).'\','.time().',\'Ver site\')');
						if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$sub_cat_title.'/'))
							mkdir($_SERVER['DOCUMENT_ROOT'].'/'.$sub_cat_title.'/');
						$destination=$_SERVER['DOCUMENT_ROOT'].'/'.$sub_cat_title.'/'.$seo.'.jpg';
						$destination_mini=$_SERVER['DOCUMENT_ROOT'].'/'.$sub_cat_title.'/'.$seo.'-mini.jpg';
						if($GLOBALS['unknownlib']['site']['external_image_download'])
						{
							if(isset($item['thumb']) && $item['thumb']!='' && !preg_match('#\.gif$#',$item['thumb']))
							{
								unknownlib_mysql_query('UPDATE `prices` SET `url_thumb`=\''.addslashes($item['thumb']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
								if($have_big_thumbs)
										//check thumb exists by file exists, because each shop can have thein own thumb
										unknownlib_image_download_scaled_and_cropped_multi($item['thumb'],array(
											array('path_destination'=>$destination,'target_width'=>130,'target_height'=>130),
											array('path_destination'=>$destination_mini,'target_width'=>64,'target_height'=>64),
										),95,false);
								else
								{
									if(!file_exists($destination_mini))
									//check thumb exists by file exists, because each shop can have thein own thumb
										unknownlib_image_download_scaled_and_cropped($item['thumb'],$destination_mini,64,64);
								}
							}
						}
						if(file_exists($destination) && file_exists($destination_mini))
							unknownlib_mysql_query('UPDATE `product_base_information` SET `have_thumb`=1 WHERE `unique_identifier`=\''.$unique_identifier_product.'\'');
						if($GLOBALS['unknownlib']['site']['external_call'])
						{
							if(isset($item['page_technical_details']) && $item['page_technical_details']!='' && $GLOBALS['unknownlib']['site']['external_call'])
							{
								echo 'second: '.$item['page_technical_details'].'!='.$data_price['url_technical_details'].'<br />'."\n";
								$individual_data='../../individual_data/'.$data_shop_list['url_alias_for_seo'].'/'.str_replace('/','-',str_replace('\\','-',$item['unique_identifier'])).'.data';
								unset($content_temp);
								if(file_exists($individual_data))
									$content_temp=file_get_contents($individual_data);
								else
								{
									echo '2) open external url: "'.$item['page_technical_details'].'", type: '.$item['type'].'<br />'."\n";
									$content_temp=unknownlib_urlopen($item['page_technical_details']);
									$content_temp=unknownlib_text_auto_encoding_to_utf8($content_temp);
									if(!preg_match('#^ *$#',$content_temp))
									{
										if(!is_dir('../../individual_data/'))
											mkdir('../../individual_data/');
										if(!is_dir('../../individual_data/'.$data_shop_list['url_alias_for_seo']))
											mkdir('../../individual_data/'.$data_shop_list['url_alias_for_seo']);
										unknownlib_filewrite($individual_data,$content_temp);
									}
									else
										echo 'Empty: '.$item['page_technical_details'].'<br />'."\n";
								}
								if(isset($content_temp))
								{
									unknownlib_mysql_query('UPDATE `prices` SET `url_technical_details`=\''.addslashes($item['page_technical_details']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
									require 'shop_plugin/'.$data_shop_list['url_alias_for_seo'].'/technical_details/'.$item['type'].'.php';
									require 'import_plugin/'.$item['type'].'_parse_generic.php';
									if(isset($technical_details_to_parse))
										echo 'technical_details: <pre>';print_r($technical_details_to_parse);echo '</pre>';
									echo '<pre>';print_r($item);echo '</pre>';
									unset($content_temp);
								}
							}
						}
					}
				}
				if(isset($item['price_port']))
					unknownlib_mysql_query('UPDATE `prices` SET `price_port`='.$item['price_port'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
//				if(isset($item['id_product_on_shop']))
//					unknownlib_mysql_query('UPDATE `prices` SET `id_product_on_shop`=\''.$item['id_product_on_shop'].'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
				if(isset($item['delivery']))
				{
					if(preg_match('#^(in stock|En Stock)$#isU',$item['delivery']))
						unknownlib_mysql_query('UPDATE `prices` SET `delivery`=\'In stock\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
					elseif(preg_match('#^([0-2]?[0-9]|3[0-1])/(1[0-2]|0?[0-9])/((20)?[0-9]{2})$#isU',$item['delivery']))
					{
						$day=(int)preg_replace('#^([0-2]?[0-9]|3[0-1])/(1[0-2]|0?[0-9])/((20)?[0-9]{2})$#isU','$1',$item['delivery']);
						$mouth=(int)preg_replace('#^([0-2]?[0-9]|3[0-1])/(1[0-2]|0?[0-9])/((20)?[0-9]{2})$#isU','$2',$item['delivery']);
						$year=(int)preg_replace('#^([0-2]?[0-9]|3[0-1])/(1[0-2]|0?[0-9])/((20)?[0-9]{2})$#isU','$3',$item['delivery']);
						if($year<=99)
							$year+=2000;
						unknownlib_mysql_query('UPDATE `prices` SET `delivery`=\''.$day.'/'.$mouth.'/'.$year.'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
					}
				}
				switch($item['type'])
				{
					case 'cpu':
					case 'gpu':
					case 'motherboard':
					case 'memory':
					case 'power_supply':
					case 'case':
						require 'import_plugin/'.$item['type'].'_mysql.php';
					break;
					default:
						echo '<span style="color:#f00">Error, type unknow 2: '.$item['type'].'</span><br />'."\n";
						continue;
				}
			}
			else
			{
				echo '<div style="border:1px solid #000">';
				if(!isset($item['product_code']))
					echo '<span style="color:#f00">Error, product_code missing</span><br />'."\n";
				if(!isset($item['price']))
					echo '<span style="color:#f00">Error, price missing</span><br />'."\n";
				if(!isset($item['ref']))
					echo '<span style="color:#f00">Error, ref missing</span><br />'."\n";
				elseif($item['ref']=='')
					echo '<span style="color:#f00">Error, ref empty</span><br />'."\n";
				if(!isset($item['type']))
					echo '<span style="color:#f00">Error, type missing</span><br />'."\n";
				if(!isset($item['url']))
					echo '<span style="color:#f00">Error, url missing</span><br />'."\n";
				elseif($item['url']=='')
					echo '<span style="color:#f00">Error, url empty</span><br />'."\n";
				if(!isset($item['mark']))
					echo '<span style="color:#f00">Error, mark missing</span><br />'."\n";
				echo '<pre>';
				print_r($item);
				echo '</pre></div>';
			}
		}
		foreach($unique_identifier_prices as $unique_identifier_product => $value)
		{
			echo 'Remove price (`unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id'].')<br />'."\n";
			unknownlib_mysql_query('DELETE FROM `prices` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\' AND `id_shop`='.$data_shop_list['id']);
		}
	}
	else
		echo 'no plugin found for: '.$data_shop_list['url_alias_for_seo'].'<br />'."\n";
}

unknownlib_mysql_query('DELETE FROM `output_product` WHERE `timestamp`<'.(time()-3600*24*31));//why with: `unique_identifier_product`=\''.$unique_identifier_product.'\'  ?

foreach($unique_identifier_product_to_remove as $unique_identifier_product => $value)
{
	$reply = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `unique_identifier`=\''.$unique_identifier_product.'\' AND `last_time_not_used`=0');
	if($data = mysql_fetch_array($reply))
	{
		echo 'Disable product `unique_identifier_product`=\''.$unique_identifier_product.'\'<br />'."\n";
		$file_base=$_SERVER['DOCUMENT_ROOT'].'/'.$data['table_product'].'/'.$data['url_alias_for_seo'];
		/*if(file_exists($file_base.'-mini.jpg'))
			unlink($file_base.'-mini.jpg');
		if(file_exists($file_base.'.jpg'))
			unlink($file_base.'.jpg');
		if(file_exists($file_base.'.html'))
			unlink($file_base.'.html');*/
		//unknownlib_mysql_query('DELETE FROM `output_product` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');//will dropped with the time
		//unknownlib_mysql_query('DELETE FROM `information_extra_'.$data['table_product'].'` WHERE `unique_identifier_product`='.$unique_identifier_product); will never dropped
		unknownlib_mysql_query('UPDATE `output_product` SET `last_time_not_used`='.time().' WHERE `unique_identifier_product`=\''.addslashes($unique_identifier_product).'\' AND `last_time_not_used`=0');
		unknownlib_mysql_query('UPDATE `product_base_information` SET `last_time_not_used`='.time().' WHERE `unique_identifier`=\''.addslashes($unique_identifier_product).'\' AND `last_time_not_used`=0');
	}
}

/*$reply = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `last_time_not_used`>0 AND `last_time_not_used`<'.(time()-3600*24*31));
while($data = mysql_fetch_array($reply))
{
	$unique_identifier_product=$data['unique_identifier'];
	echo 'Remove product `unique_identifier_product`=\''.$data['unique_identifier'].'\'<br />'."\n";
	$file_base=$_SERVER['DOCUMENT_ROOT'].'/'.$data['table_product'].'/'.$data['url_alias_for_seo'];
	if(file_exists($file_base.'-mini.jpg'))
		unlink($file_base.'-mini.jpg');
	if(file_exists($file_base.'.jpg'))
		unlink($file_base.'.jpg');
	if(file_exists($file_base.'.html'))
		unlink($file_base.'.html');
	unknownlib_mysql_query('DELETE FROM `output_product` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');//will dropped with the time
	unknownlib_mysql_query('DELETE FROM `information_extra_'.$data['table_product'].'` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
	unknownlib_mysql_query('DELETE FROM `product_base_information` WHERE `unique_identifier`=\''.addslashes($unique_identifier_product).'\'');
}*/

echo 'end<br />'."\n";
?>
</body>
</html>
