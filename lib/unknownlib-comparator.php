<?php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['comparator']=true;

/// \param $max_product the max product used
/// \param $cat filter to this main cat if is not egal to ''
/// \param $sub_cat filter to this sub cat if is not egal to ''
function unknownlib_comparator_get_top_product($max_product,$cat='',$sub_cat='',$id_shop='')
{
	$md5=md5($cat.$sub_cat.$id_shop);
	if(isset($GLOBALS['unknownlib']['comparator']['top_product'][$max_product][$md5]))
		return $GLOBALS['unknownlib']['comparator']['top_product'][$max_product][$md5];
	$return_array=array();
	//load the top click by product
	$tab_out = array();
	//SELECT SUM(`count`) AS `count_tot`,`unique_identifier_product` FROM `output_product` GROUP BY `unique_identifier_product` ORDER BY `count_tot` DESC 
	if($id_shop!='')
		$reply_out = unknownlib_mysql_query('SELECT SUM(`count`) AS `count_tot`,`unique_identifier_product` FROM `output_product` WHERE `id_shop`='.$id_shop.' AND `last_time_not_used`=0 AND `timestamp`>'.(time()-3600*24*31).' GROUP BY `unique_identifier_product` ORDER BY `count_tot` DESC LIMIT 0,'.$max_product);
	elseif($cat!='')
		$reply_out = unknownlib_mysql_query('SELECT SUM(`count`) AS `count_tot`,`unique_identifier_product` FROM `output_product` WHERE `categorie`=\''.addslashes($cat).'\' AND `last_time_not_used`=0 AND `timestamp`>'.(time()-3600*24*31).' GROUP BY `unique_identifier_product` ORDER BY `count_tot` DESC LIMIT 0,'.$max_product);
	elseif($sub_cat!='')
		$reply_out = unknownlib_mysql_query('SELECT SUM(`count`) AS `count_tot`,`unique_identifier_product` FROM `output_product` WHERE `sub_categorie`=\''.addslashes($sub_cat).'\' AND `last_time_not_used`=0 AND `timestamp`>'.(time()-3600*24*31).' GROUP BY `unique_identifier_product` ORDER BY `count_tot` DESC LIMIT 0,'.$max_product);
	else
		$reply_out = unknownlib_mysql_query('SELECT SUM(`count`) AS `count_tot`,`unique_identifier_product` FROM `output_product` WHERE `last_time_not_used`=0 AND `timestamp`>'.(time()-3600*24*31).' GROUP BY `unique_identifier_product` ORDER BY `count_tot` DESC LIMIT 0,'.$max_product);
	while($data_out = mysql_fetch_array($reply_out))
		$tab_out[$data_out['unique_identifier_product']]=$data_out['count_tot'];
	//arsort($tab_out);
	foreach($tab_out as $unique_identifier => $number_click)
	{
		//get the specific data product
		if($GLOBALS['unknownlib']['site']['show_without_thumb'])
			$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `unique_identifier`=\''.addslashes($unique_identifier).'\' AND `last_time_not_used`=0');
		else
			$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `unique_identifier`=\''.addslashes($unique_identifier).'\' AND `last_time_not_used`=0 AND `have_thumb`=1');
		if($data_product = mysql_fetch_array($reply_product))
		{
			//get the better price
			$reply_prices = unknownlib_mysql_query('SELECT MIN(price) AS price_min, COUNT(price) AS price_count FROM `prices` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
			while($data_prices = mysql_fetch_array($reply_prices))
			{
				if($data_prices['price_count']>0)
				{
					$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
					if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
						$data_product['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
					else
						$data_product['thumb_overwrite']='';
					$return_array[]=array_merge($data_product,array('price'=>$data_prices['price_min'],'nbr_shop'=>$data_prices['price_count']));
				}
			}
		}
	}
	//put random product
	if(count($return_array)<$max_product)
		$return_array=array_merge($return_array,unknownlib_comparator_get_new_product($max_product-count($return_array),$cat,$sub_cat));

	$GLOBALS['unknownlib']['comparator']['top_product'][$max_product][$md5]=$return_array;
	return $return_array;
}

/// \param $max_product the max product used
/// \param $cat filter to this main cat if is not egal to ''
/// \param $sub_cat filter to this sub cat if is not egal to ''
function unknownlib_comparator_get_boosted_product($max_product,$cat='',$sub_cat='',$id_shop='')
{
	$md5=md5($cat.$sub_cat.$id_shop);
	if(isset($GLOBALS['unknownlib']['comparator']['boosted_product'][$max_product][$md5]))
		return $GLOBALS['unknownlib']['comparator']['boosted_product'][$max_product][$md5];
	$extra_mysql_filter='';
	if($cat!='')
	{
		if(isset($GLOBALS['unknownlib']['site']['categories'][$cat]['sub_cat']))
		{
			$sub_cat_temp='';
			$extra_mysql_filter=' AND `table_product` IN(';
			foreach($GLOBALS['unknownlib']['site']['categories'][$cat]['sub_cat'] as $sub_cat_current=>$content)
				if($sub_cat_temp=='')
					$sub_cat_temp='\''.addslashes($sub_cat_current).'\'';
				else
					$sub_cat_temp.=',\''.addslashes($sub_cat_current).'\'';
			$extra_mysql_filter.=$sub_cat_temp;
			$extra_mysql_filter.=')';
		}
	}
	elseif($sub_cat!='')
		$extra_mysql_filter=' AND `table_product`=\''.addslashes($sub_cat).'\'';
	$return_array=array();
	//get the specific data product
	if($GLOBALS['unknownlib']['site']['show_without_thumb'])
		$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `boosted`=1'.$extra_mysql_filter.' AND `last_time_not_used`=0 ORDER BY date DESC LIMIT 0,'.$max_product);
	else
		$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `boosted`=1'.$extra_mysql_filter.' AND `last_time_not_used`=0 AND `have_thumb`=1 ORDER BY date DESC LIMIT 0,'.$max_product);
	while($data_product = mysql_fetch_array($reply_product))
	{
		$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
		if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
			$data_product['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
		else
			$data_product['thumb_overwrite']='';
		//get the better price
		if($id_shop=='')
			$reply_prices = unknownlib_mysql_query('SELECT MIN(price) AS price_min, COUNT(price) AS price_count FROM `prices` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\'');
		else
			$reply_prices = unknownlib_mysql_query('SELECT MIN(price) AS price_min, COUNT(price) AS price_count FROM `prices` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\' AND `id_shop`='.$id_shop);
		while($data_prices = mysql_fetch_array($reply_prices))
		{
			if($data_prices['price_count']>0)
				$return_array[]=array_merge($data_product,array('price'=>$data_prices['price_min'],'nbr_shop'=>$data_prices['price_count']));
		}
	}
	if(count($return_array)<=0)
		$return_array=unknownlib_comparator_get_top_product($max_product,$cat,$sub_cat,$id_shop);
	$GLOBALS['unknownlib']['comparator']['boosted_product'][$max_product][$md5]=$return_array;
	return $return_array;
}

/// \param $max_product the max product used
/// \param $cat filter to this main cat if is not egal to ''
/// \param $sub_cat filter to this sub cat if is not egal to ''
function unknownlib_comparator_get_new_product($max_product,$cat='',$sub_cat='')
{
	$md5=md5($cat.$sub_cat);
	if(isset($GLOBALS['unknownlib']['comparator']['new_product'][$max_product][$md5]))
		return $GLOBALS['unknownlib']['comparator']['new_product'][$max_product][$md5];
	$extra_mysql_filter='';
	if($cat!='')
	{
		if(isset($GLOBALS['unknownlib']['site']['categories'][$cat]['sub_cat']))
		{
			$sub_cat_temp='';
			$extra_mysql_filter='`table_product` IN(';
			foreach($GLOBALS['unknownlib']['site']['categories'][$cat]['sub_cat'] as $sub_cat_current=>$content)
				if($sub_cat_temp=='')
					$sub_cat_temp='\''.addslashes($sub_cat_current).'\'';
				else
					$sub_cat_temp.=',\''.addslashes($sub_cat_current).'\'';
			$extra_mysql_filter.=$sub_cat_temp;
			$extra_mysql_filter.=') AND';
		}
	}
	elseif($sub_cat!='')
		$extra_mysql_filter='`table_product`=\''.addslashes($sub_cat).'\' AND';
	$return_array=array();
	//get the specific data product
	if($GLOBALS['unknownlib']['site']['show_without_thumb'])
		$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information`  WHERE '.$extra_mysql_filter.' `last_time_not_used`=0 ORDER BY date DESC LIMIT 0,'.$max_product);
	else
		$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information`  WHERE '.$extra_mysql_filter.' `last_time_not_used`=0 AND `have_thumb`=1 ORDER BY date DESC LIMIT 0,'.$max_product);
	while($data_product = mysql_fetch_array($reply_product))
	{
		$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
		if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
			$data_product['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
		else
			$data_product['thumb_overwrite']='';
		//get the better price
		$reply_prices = unknownlib_mysql_query('SELECT MIN(price) AS price_min, COUNT(price) AS price_count FROM `prices` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\'');
		while($data_prices = mysql_fetch_array($reply_prices))
		{
			if($data_prices['price_count']>0)
				$return_array[]=array_merge($data_product,array('price'=>$data_prices['price_min'],'nbr_shop'=>$data_prices['price_count']));
		}
	}
	$GLOBALS['unknownlib']['comparator']['new_product'][$max_product][$md5]=$return_array;
	return $return_array;
}

function unknownlib_comparator_get_thumb_multi($table_product,$url_alias_for_seo,$thumb_overwrite,$title='',$is_image_description=false)
{
	$title=unknownlib_text_htmlentities_drop($title);
	if($thumb_overwrite!='')
		$file_thumb=unknownlib_clean_path('/'.$table_product.'/thumb_overwrite/'.$thumb_overwrite);
	else
		$file_thumb=unknownlib_clean_path('/'.$table_product.'/'.$url_alias_for_seo);
	if($is_image_description)
		$image_microdata=' itemprop="image"';
	else
		$image_microdata='';
	if(file_exists($_SERVER['DOCUMENT_ROOT'].$file_thumb.'.jpg'))
		return '<img src="'.$file_thumb.'.jpg" alt="'.$title.'" title="'.$title.'" height="130px" width="130px" class="thumb_big"'.$image_microdata.' />';
	elseif(file_exists($_SERVER['DOCUMENT_ROOT'].$file_thumb.'-mini.jpg'))
		return '<img src="'.$file_thumb.'-mini.jpg" alt="'.$title.'" title="'.$title.'" height="64px" width="64px" class="thumb_small"'.$image_microdata.' />';
	else
		return '<img src="/images/no-photo.png" alt="" height="130px" width="130px" class="thumb_big" />';
}

function unknownlib_comparator_get_thumb_mini($table_product,$url_alias_for_seo,$thumb_overwrite)
{
	if($thumb_overwrite!='')
		$file_thumb=unknownlib_clean_path('/'.$table_product.'/thumb_overwrite/'.$thumb_overwrite);
	else
		$file_thumb=unknownlib_clean_path('/'.$table_product.'/'.$url_alias_for_seo);
	if(file_exists($_SERVER['DOCUMENT_ROOT'].$file_thumb.'-mini.jpg'))
		return $file_thumb.'-mini.jpg';
	else
		return '/images/no-photo-mini.png';
}

function unknownlib_comparator_get_multi_product_informations($cat,$sub_cat)
{
	$final_array=array();
	//check the var value
	if(!isset($GLOBALS['unknownlib']['site']['categories'][$_GET['cat']]))
		unknownlib_tryhack('value of cat is not in array');
	if(!isset($GLOBALS['unknownlib']['site']['categories'][$_GET['cat']]['sub_cat'][$_GET['sub_cat']]))
		unknownlib_tryhack('value of sub_cat is not in array');
	//get the specific data product
	if($GLOBALS['unknownlib']['site']['show_without_thumb'])
		$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `table_product`=\''.addslashes($sub_cat).'\' AND `last_time_not_used`=0 ORDER BY `mark` ASC,`title` ASC');
	else
		$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `table_product`=\''.addslashes($sub_cat).'\' AND `last_time_not_used`=0 AND `have_thumb`=1 ORDER BY `mark` ASC,`title` ASC');
	while($data_product = mysql_fetch_array($reply_product))
	{
		$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
		if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
			$data_product['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
		else
			$data_product['thumb_overwrite']='';
		$sub_array=unknownlib_comparator_get_single_product_informations($data_product['id']);
		if(count($sub_array)>0 && $sub_array['price'])
			$final_array[]=$sub_array;
	}
	return $final_array;
}

function unknownlib_comparator_get_single_product_informations($id)
{
	//get the specific data product
	$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `id`=\''.addslashes($id).'\'');
	while($data_product = mysql_fetch_array($reply_product))
	{
		$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
		if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
			$data_product['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
		else
			$data_product['thumb_overwrite']='';
		$reply_extra_informations = unknownlib_mysql_query('SELECT * FROM `information_extra_'.addslashes($data_product['table_product']).'` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\'');
		if($data_extra_informations = mysql_fetch_array($reply_extra_informations))
		{
			$sub_array=$data_product;
/*			$sub_array['title']		= $data_product['title'];
			if($data_product['mark']!='')
				$sub_array['mark']	= $data_product['mark'];
			$sub_array['url_alias_for_seo']	= $data_product['url_alias_for_seo'];
			if($data_product['url_thumb']!='')
				if(file_exists(unknownlib_clean_path($_SERVER['DOCUMENT_ROOT'].'/'.$data_product['table_product'].'/'.$data_product['url_thumb'].'.png')))
					$sub_array['url_thumb']	= $data_product['url_thumb'];*/
			$reply_note = unknownlib_mysql_query('SELECT AVG( note ) AS note_avg, COUNT( note ) AS note_count FROM `comment_product` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\'');
			if($data_note = mysql_fetch_array($reply_note))
				if($data_note['note_count']>0)
				{
					$sub_array['note_count']= (int)$data_note['note_count'];
					$sub_array['note']	= (int)$data_note['note_avg'];
				}
			foreach($data_extra_informations as $key=>$value)
			{
				if($key!='id_product' && $key!='id' && !is_int($key))
					$sub_array[$key]=$data_extra_informations[$key];
			}
			$price_array			= unknownlib_comparator_get_product_price_and_shop($data_product['unique_identifier']);
			if(isset($price_array['price']) && isset($price_array['nbr_shop']))
			{
				$sub_array['price']=$price_array['price'];
				$sub_array['nbr_shop']=$price_array['nbr_shop'];
			}
			return $sub_array;
		}
	}
	return array();
}

function unknownlib_comparator_get_single_product_informations_seo($seo)
{
	//get the specific data product
	$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `url_alias_for_seo`=\''.addslashes($seo).'\'');
	while($data_product = mysql_fetch_array($reply_product))
	{
		$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
		if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
			$data_product['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
		else
			$data_product['thumb_overwrite']='';
		$reply_extra_informations = unknownlib_mysql_query('SELECT * FROM `information_extra_'.addslashes($data_product['table_product']).'` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\'');
		if($data_extra_informations = mysql_fetch_array($reply_extra_informations))
		{
			$sub_array=$data_product;
/*			$sub_array['title']		= $data_product['title'];
			$sub_array['id']		= $data_product['id'];
			if($data_product['mark']!='')
				$sub_array['mark']	= $data_product['mark'];
			$sub_array['url_alias_for_seo']	= $data_product['url_alias_for_seo'];
			$sub_array['thumb_overwrite']	= $data_product['thumb_overwrite'];*/
//			$sub_array['url_thumb']	= unknownlib_comparator_get_thumb('/'.$data_product['table_product'].'/'.$data_product['url_alias_for_seo']);
			$reply_note = unknownlib_mysql_query('SELECT AVG( note ) AS note_avg, COUNT( note ) AS note_count FROM `comment_product` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\'');
			if($data_note = mysql_fetch_array($reply_note))
				if($data_note['note_count']>0)
				{
					$sub_array['note_count']= (int)$data_note['note_count'];
					$sub_array['note']	= (int)$data_note['note_avg'];
				}
			foreach($data_extra_informations as $key=>$value)
			{
				if($key!='id_product' && $key!='id' && !is_int($key))
					$sub_array[$key]=$data_extra_informations[$key];
			}
			$price_array			= unknownlib_comparator_get_product_price_and_shop($data_product['unique_identifier']);
			if(isset($price_array['price']) && isset($price_array['nbr_shop']))
			{
				$sub_array['price']=$price_array['price'];
				$sub_array['nbr_shop']=$price_array['nbr_shop'];
			}
			return $sub_array;
		}
	}
	return array();
}

function unknownlib_comparator_get_product_price_and_shop($unique_identifier_product)
{
	//get the better price
	$reply_prices = unknownlib_mysql_query('SELECT MIN(price) AS price_min, COUNT(price) AS price_count FROM `prices` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
	if($data_prices = mysql_fetch_array($reply_prices))
		if($data_prices['price_count']>0)
			return array('price'=>$data_prices['price_min'],'nbr_shop'=>$data_prices['price_count']);
	return array();
}

function unknownlib_comparator_get_prices($unique_identifier_product)
{
	$shop=array();
	$reply_price = unknownlib_mysql_query('SELECT * FROM `prices` WHERE `unique_identifier_product`=\''.addslashes($unique_identifier_product).'\' ORDER BY `price` ASC LIMIT 0 , 30');
	while($data_price = mysql_fetch_array($reply_price))
	{
		$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` WHERE `id`='.$data_price['id_shop']);
		if($data_shop = mysql_fetch_array($reply_shop))
		{
			$current_shop=array('price'=>$data_price['price'],'url'=>$data_price['url'],'price_id'=>$data_price['id'],'price_port'=>$data_price['price_port'],'delivery'=>$data_price['delivery'],'name'=>$data_shop['name'],'url_alias_for_seo'=>$data_shop['url_alias_for_seo']);
			$reply_note = unknownlib_mysql_query('SELECT AVG( note ) AS note_avg, COUNT( note ) AS note_count FROM `comment_shop` WHERE `id_shop`='.$data_shop['id']);
			if($data_note = mysql_fetch_array($reply_note))
				if($data_note['note_count']>0)
				{
					$current_shop['note_count']	= (int)$data_note['note_count'];
					$current_shop['note']		= (int)$data_note['note_avg'];
				}
			$shop[]=$current_shop;
		}
	}
	return $shop;
}

function unknownlib_comparator_get_shops($id='')
{
	$shop=array();
	if($id!='')
		$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` WHERE `id`='.addslashes($id).' ORDER BY `name` ASC');
	else
		$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` ORDER BY `name` ASC');
	while($data_shop = mysql_fetch_array($reply_shop))
	{
		$current_shop=array('name'=>$data_shop['name'],'url_alias_for_seo'=>$data_shop['url_alias_for_seo'],'site'=>$data_shop['site'],'payment'=>$data_shop['payment'],'delivery_zones'=>$data_shop['delivery_zones'],'insurance_safety'=>$data_shop['insurance_safety']);
		$reply_note = unknownlib_mysql_query('SELECT AVG( note ) AS note_avg, COUNT( note ) AS note_count FROM `comment_shop` WHERE `id_shop`='.$data_shop['id']);
		if($data_note = mysql_fetch_array($reply_note))
			if($data_note['note_count']>0)
			{
				$current_shop['note_count']	= (int)$data_note['note_count'];
				$current_shop['note']		= (int)$data_note['note_avg'];
			}
		$reply_note = unknownlib_mysql_query('SELECT COUNT( id ) AS price_count FROM `prices` WHERE `id_shop`=\''.addslashes($data_shop['id']).'\'');
		if($data_note = mysql_fetch_array($reply_note))
			$current_shop['price_count']	= (int)$data_note['price_count'];
		else
			$current_shop['price_count']	= 0;
		if($id!='')
			return $current_shop;
		$shop[]=$current_shop;
	}
	return $shop;
}

function unknownlib_comparator_get_shops_seo($seo='')
{
	$shop=array();
	if($seo!='')
		$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` WHERE `url_alias_for_seo`=\''.addslashes($seo).'\' ORDER BY `name` ASC');
	else
		$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` ORDER BY `name` ASC');
	while($data_shop = mysql_fetch_array($reply_shop))
	{
		$current_shop=array('id'=>$data_shop['id'],'name'=>$data_shop['name'],'url_alias_for_seo'=>$data_shop['url_alias_for_seo'],'site'=>$data_shop['site'],'payment'=>$data_shop['payment'],'delivery_zones'=>$data_shop['delivery_zones'],'insurance_safety'=>$data_shop['insurance_safety']);
		$reply_note = unknownlib_mysql_query('SELECT AVG( note ) AS note_avg, COUNT( note ) AS note_count FROM `comment_shop` WHERE `id_shop`='.$data_shop['id']);
		if($data_note = mysql_fetch_array($reply_note))
			if($data_note['note_count']>0)
			{
				$current_shop['note_count']	= (int)$data_note['note_count'];
				$current_shop['note']		= (int)$data_note['note_avg'];
			}
		$reply_note = unknownlib_mysql_query('SELECT COUNT( id ) AS price_count FROM `prices` WHERE `id_shop`='.$data_shop['id']);
		if($data_note = mysql_fetch_array($reply_note))
			$current_shop['price_count']	= (int)$data_note['price_count'];
		else
			$current_shop['price_count']	= 0;
		if($seo!='')
			return $current_shop;
		$shop[]=$current_shop;
	}
	return $shop;
}

function unknownlib_comparator_get_comment_product($unique_identifier)
{
	$comments = array();
	$reply_comment = unknownlib_mysql_query('SELECT * FROM `comment_product` WHERE `unique_identifier_product`=\''.addslashes($unique_identifier).'\' ORDER BY `date` DESC');
	while($data_comment = mysql_fetch_array($reply_comment))
	{
		$reply_account = unknownlib_mysql_query('SELECT * FROM `account` WHERE `id`='.$data_comment['id_account']);
		if($data_account = mysql_fetch_array($reply_account))
		{
			$comments[] = array('login'=>$data_account['login'],'note'=>$data_comment['note'],'comment'=>$data_comment['comment'],'date'=>date('d/m/Y',$data_comment['date']));
		}
	}
	return $comments;
}

function unknownlib_comparator_get_comment_product_seo($seo)
{
	$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `url_alias_for_seo`=\''.addslashes($seo).'\'');
	while($data_product = mysql_fetch_array($reply_product))
	{
		$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
		if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
			$data_product['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
		else
			$data_product['thumb_overwrite']='';
		return unknownlib_comparator_get_comment_product($data_product['unique_identifier']);
	}
}

function unknownlib_comparator_get_comment_shop($id)
{
	$comments = array();
	$reply_comment = unknownlib_mysql_query('SELECT * FROM `comment_shop` WHERE `id_shop`='.$id.' ORDER BY `date` DESC');
	while($data_comment = mysql_fetch_array($reply_comment))
	{
		$reply_account = unknownlib_mysql_query('SELECT * FROM `account` WHERE `id`='.$data_comment['id_account']);
		if($data_account = mysql_fetch_array($reply_account))
		{
			$comments[] = array('login'=>$data_account['login'],'note'=>$data_comment['note'],'comment'=>$data_comment['comment'],'date'=>date('d/m/Y',$data_comment['date']));
		}
	}
	return $comments;
}

function unknownlib_comparator_get_comment_shop_seo($seo)
{
	$reply_shop = unknownlib_mysql_query('SELECT * FROM `shop` WHERE `url_alias_for_seo`=\''.addslashes($seo).'\'');
	while($data_shop = mysql_fetch_array($reply_shop))
		return unknownlib_comparator_get_comment_shop($data_shop['id']);
}

?>