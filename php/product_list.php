<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
$array_current_cat=$GLOBALS['unknownlib']['site']['categories'][$_GET['cat']]['sub_cat'][$_GET['sub_cat']];
//set the header variable
$GLOBALS['unknownlib']['site']['record_last_page']=true;
$GLOBALS['unknownlib']['site']['extra_header']= '<link rel="stylesheet" type="text/css" href="/css/sub_cat.css" media="all" />
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']	= $array_current_cat['page_title'];
$GLOBALS['unknownlib']['site']['description']	= $array_current_cat['page_desc'];
if(strlen($GLOBALS['unknownlib']['site']['description'])<100)
	$GLOBALS['unknownlib']['site']['description'].=' Liste de produits sur comparatif BTC';
$GLOBALS['unknownlib']['site']['keywords']	= $array_current_cat['page_keywords'];
$GLOBALS['unknownlib']['site']['page_title']	= array('/'=>'Comparateur de prix en BTC','/'.$_GET['cat'].'.html'=>$GLOBALS['unknownlib']['site']['categories'][$_GET['cat']]['title'],''=>$array_current_cat['title']);
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<div class="block_internal">
		<h2>Filtre de cherche</h2>
		<div class="body_block">
			<?php
			if(!isset($array_current_cat['page_title']) || !isset($array_current_cat['page_desc']) || !isset($array_current_cat['page_keywords']) || !isset($array_current_cat['title']) || !isset($array_current_cat['on_main_page']) || !isset($array_current_cat['spec']))
			{ ?>Sub-var missing<?php }
			else
			{
			?>
				<div class="block_filter">Prix entre <input type="text" id="price_min" value="" size="3" onkeyup="a()" /><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?> et <input type="text" id="price_max" value="" size="3" onkeyup="a()" /><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?></div>
				<span id="product_filter"></span>
				<div style="clear:both;"></div>
			<?php
			}
			?>
		</div>
	</div>
	<div class="block_internal">
		<h2>Affichage</h2>
		<div class="body_block" id="filter_listing">
		Organisé par:
		<select id="orderBy" onchange="a()">
			<option value="mark">Marque</option>
			<option value="price">Prix</option>
		</select>
		Visualisation:
		<select id="showBy" onchange="a()">
			<option value="list">Liste</option>
			<option value="preview">Previsualisation</option>
			<option value="image">Image</option>
		</select>
		</div>
	</div>
	<div class="block_internal">
		<h2 id="<?php echo $_GET['sub_cat']; ?>"><?php echo unknownlib_text_htmlentities_drop($array_current_cat['title']); ?> <span id="numberItem"></span></h2>
		<div class="body_block" id="product_listing"><div style="margin:30px 250px;white-space:nowrap;"><img src="/images/loader.gif" alt="" height="24px" width="24px" style="vertical-align:middle;" /> Chargement des produits</div></div>
		<div id="dialog-modal" title="Comparer les produtis" style="display:none;"></div>
	</div>
</div>
<div class="col_right">
	<div class="block_internal">
		<h2>Top produits</h2>
		<div class="body_block" style="text-align:center;">
		<?php
			$products=unknownlib_comparator_get_top_product(2,'',$array_current_cat['english_name']);
			if(count($products)==0)
				echo 'No hay un producto.';
			else
			{
				foreach($products as $current_product)
				{
					$url_fiche='/'.$GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']].'/'.$current_product['url_alias_for_seo'].'.html';
					?>
					<div class="photo thumb_preview">
						<a href="<?php echo $url_fiche; ?>">
						<?php echo unknownlib_comparator_get_thumb_multi($GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']],$current_product['url_alias_for_seo'],$current_product['thumb_overwrite'],$current_product['title']); ?>
						</a>
					</div>
					<strong class="title_product">
						<a href="<?php echo $url_fiche; ?>">
							<?php echo unknownlib_text_htmlentities_drop($current_product['title']); ?>
						</a>
					</strong>
					<span class="inter_product"> a partir de </span>
					<span class="price_product"><?php echo $current_product['price']; ?><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?></span>
					<?php
				}
			}
			
		?>
		</div>
	</div>
	<div class="block_internal">
		<h2>Nouveaux produits</h2>
		<div class="body_block" style="text-align:center;">
		<?php
			$products=unknownlib_comparator_get_new_product(2,'',$array_current_cat['english_name']);
			if(count($products)==0)
				echo 'No hay un producto nuevo.';
			else
			{
				foreach($products as $current_product)
				{
					$url_fiche='/'.$GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']].'/'.$current_product['url_alias_for_seo'].'.html';
					?>
					<div class="photo thumb_preview">
						<a href="<?php echo $url_fiche; ?>">
						<?php echo unknownlib_comparator_get_thumb_multi($GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']],$current_product['url_alias_for_seo'],$current_product['thumb_overwrite'],$current_product['title']); ?>
						</a>
					</div>
					<strong class="title_product">
						<a href="<?php echo $url_fiche; ?>">
							<?php echo unknownlib_text_htmlentities_drop($current_product['title']); ?>
						</a>
					</strong>
					<span class="inter_product"> a partir de </span>
					<span class="price_product"><?php echo $current_product['price']; ?><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?></span>
					<?php
				}
			}
			
		?>
		</div>
	</div>
	<div class="block_internal">
		<h2 id="top_productos">Actualités</h2>
		<div class="body_block news">
			<div style="margin:0 40px"><img src="/images/loader.gif" alt="" height="24px" width="24px" style="vertical-align:middle;" /> Chargement des actualités</div>
		</div>
	</div>
</div>
<?php
$GLOBALS['unknownlib']['site']['extra_footer']='<script type="text/javascript" src="/js/sub_cat.js"></script><script type="text/javascript" src="/js/jquery-ui.js"></script><script type="text/javascript" src="/js/news-content.js"></script>';
$GLOBALS['unknownlib']['site']['extra_js']='l("';
$GLOBALS['unknownlib']['site']['extra_js'].=$_GET['sub_cat'];
$GLOBALS['unknownlib']['site']['extra_js'].='",';
$array_data=array();
$reply=unknownlib_mysql_query('SELECT * FROM `information_extra_'.$array_current_cat['english_name'].'`');
while($data=mysql_fetch_array($reply))
{
	foreach($data as $key => $val)
		if($key!='id_product' && !preg_match('#^[0-9]+$#',$key) && $val!='' && $val!='0')
			$array_data[$data['unique_identifier_product']][$key]=$data[$key];
}
$array_top_id=array();
$number_of_new_product_remaining=10;
$number_of_top_product_remaining=10;
if($GLOBALS['unknownlib']['site']['show_without_thumb'])
	$reply=unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `table_product`=\''.$array_current_cat['english_name'].'\' AND `last_time_not_used`=0 ORDER BY `date` DESC');
else
	$reply=unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE `table_product`=\''.$array_current_cat['english_name'].'\' AND `last_time_not_used`=0 AND `have_thumb`=1 ORDER BY `date` DESC');
while($data=mysql_fetch_array($reply))
{
	$array_data[$data['unique_identifier']]['id']=$data['id'];
	$reply_prices = unknownlib_mysql_query('SELECT MIN(price) AS price_min, COUNT(price) AS price_count FROM `prices` WHERE `unique_identifier_product`=\''.$data['unique_identifier'].'\'');
	if($data_prices = mysql_fetch_array($reply_prices))
	{
		if($data_prices['price_count']>0)
		{
			$array_data[$data['unique_identifier']]['price']=$data_prices['price_min'];
			$array_data[$data['unique_identifier']]['nbr_shop']=$data_prices['price_count'];
		}
		else
			continue;
	}
	$reply_output = unknownlib_mysql_query('SELECT * FROM `output_product` WHERE `unique_identifier_product`=\''.$data['unique_identifier'].'\' AND `timestamp`>'.(time()-3600*24*31));
	while($data_output = mysql_fetch_array($reply_output))
	{
		if(!isset($array_top_id[$data['id']]))
			$array_top_id[$data['id']]=$data_output['count'];
		else
			$array_top_id[$data['id']]+=$data_output['count'];
	}
	if($number_of_new_product_remaining>0)
	{
		$array_data[$data['unique_identifier']]['new']='1';
		$number_of_new_product_remaining--;
	}
	$array_data[$data['unique_identifier']]['title']=$data['title'];
	$array_data[$data['unique_identifier']]['url_alias_for_seo']=$data['url_alias_for_seo'];
	$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data['unique_identifier']).'\'');
	if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
		$data['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
	else
		$data['thumb_overwrite']='';
	if($data['thumb_overwrite']!='')
		$array_data[$data['unique_identifier']]['thumb_overwrite']=$data['thumb_overwrite'];
	else
	{
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$data['table_product'].'/'.$data['url_alias_for_seo'].'.jpg'))
			$array_data[$data['unique_identifier']]['thumb_normal']='1';
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$data['table_product'].'/'.$data['url_alias_for_seo'].'-mini.jpg'))
			$array_data[$data['unique_identifier']]['thumb_mini']='1';
	}
	if($data['mark']!='')
		$array_data[$data['unique_identifier']]['mark']=$data['mark'];
	$reply_note = unknownlib_mysql_query('SELECT AVG( note ) AS note_avg, COUNT( note ) AS note_count FROM `comment_product` WHERE `unique_identifier_product`=\''.addslashes($data['unique_identifier']).'\'');
	if($data_note = mysql_fetch_array($reply_note))
		if($data_note['note_count']>0)
		{
			$array_data[$data['unique_identifier']]['note_count']	= (int)$data_note['note_count'];
			$array_data[$data['unique_identifier']]['note']	= (int)$data_note['note_avg'];
		}
}
//print_r($array_data);
//exit;
arsort($array_top_id);
$array_top_id=array_slice($array_top_id,0,10,true);
$data_to_send=array();
foreach($array_data as $item)
	if(isset($item['title']))
	{
		if(isset($array_top_id[$item['id']]))
			$item['top']='1';
		unset($item['id']);
		$data_to_send[]=$item;
	}
/** *************************** Load the content ************************** **/
$GLOBALS['unknownlib']['site']['extra_js'].=unknownlib_array_to_json($data_to_send);
$GLOBALS['unknownlib']['site']['extra_js'].=',';
$arr=$GLOBALS['unknownlib']['site']['categories'][$GLOBALS['unknownlib']['site']['reverse_link'][$_GET['sub_cat']]]['sub_cat'][$_GET['sub_cat']]['spec'];
$GLOBALS['unknownlib']['site']['extra_js'].=unknownlib_array_to_json($arr);
$GLOBALS['unknownlib']['site']['extra_js'].=');nC(15,40);';

require 'php-part/footer.php';
?>
