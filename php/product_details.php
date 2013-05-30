<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['extra_header']= '<link rel="stylesheet" type="text/css" href="/css/product.css" media="all" />
<link rel="stylesheet" type="text/css" href="/css/tab.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']	= unknownlib_text_htmlentities_drop($product_informations['title']).' - '.$array_current_cat['title'];
$GLOBALS['unknownlib']['site']['description']	= unknownlib_text_htmlentities_drop($product_informations['title']).', ';
$array_text=array();
foreach($array_current_cat['spec'] as $key=>$val)
	if(isset($val['filter_on_interface_sort_by']))
	{
		if(isset($val['unit']))
			$array_text[]=$val['title'].': '.unknownlib_text_htmlentities_drop($product_informations[$key]).$val['unit'];
		else
			$array_text[]=$val['title'].': '.unknownlib_text_htmlentities_drop($product_informations[$key]);
	}
$GLOBALS['unknownlib']['site']['description'].= implode(', ',$array_text);
$GLOBALS['unknownlib']['site']['description'].= ', comparer les prix';
$GLOBALS['unknownlib']['site']['keywords']	= unknownlib_text_operation_clean_text($product_informations['title']).',comparar,precios';
$GLOBALS['unknownlib']['site']['page_title']	= array('/'=>'Comparer les prix','/'.$_GET['cat'].'.html'=>$GLOBALS['unknownlib']['site']['categories'][$_GET['cat']]['title'],'/'.$_GET['sub_cat'].'/'=>$array_current_cat['title'],''=>unknownlib_text_htmlentities_drop($product_informations['title']));
//load the header
require 'php-part/header.php';

$top_products=unknownlib_comparator_get_top_product(10,'',$array_current_cat['english_name']);
$new_products=unknownlib_comparator_get_new_product(10,'',$array_current_cat['english_name']);
$is_top=false;
$is_new=false;
foreach($top_products as $product)
	if($product['id']==$product_informations['id'])
	{
		$is_top=true;
		break;
	}
foreach($new_products as $product)
	if($product['id']==$product_informations['id'])
	{
		$is_new=true;
		break;
	}
$comments = unknownlib_comparator_get_comment_product_seo($_GET['seo']);
$tot_note=0;
foreach($comments as $single_comment)
	$tot_note+=$single_comment['note'];
$prices_list=unknownlib_comparator_get_prices($product_informations['unique_identifier']);
?>
<div class="col_left" itemscope itemtype="http://data-vocabulary.org/Product">
	<div class="block_internal">
		<h2 id="<?php echo str_replace(' ','_',unknownlib_text_operation_clean_text($product_informations['title'])); ?>"><span itemprop="name"><?php echo unknownlib_text_htmlentities_drop($product_informations['title']); ?></span><?php
		if($is_top)
			echo '<img src="/images/top-mini.png" alt="" style="float:left;">';
		if($is_new)
			echo '<img src="/images/new-mini.png" alt="" style="float:left;">';
		?><span itemprop="category" content="<?php echo $array_current_cat['english_name']; ?>"></span></h2>
		<div class="body_block">
		<?php echo unknownlib_comparator_get_thumb_multi($_GET['sub_cat'],$product_informations['url_alias_for_seo'],$product_informations['thumb_overwrite'],$product_informations['title'],true); ?>
		<div>
			<table class="info" cellspacing="0">
				<tr class="altern_true"><td class="info_type">Marque</td><td class="info_val"><span itemprop="brand"><?php
				if(isset($product_informations['mark']) && $product_informations['mark']!='')
					echo $product_informations['mark'];
				else
					echo 'Générique';
				?></span></td></tr>
				<?php
					$altern=false;
					foreach($array_current_cat['spec'] as $key=>$val)
					{
						if(isset($val['filter_on_interface_sort_by']) && $product_informations[$key]!='' && $product_informations[$key]!='0')
						{
							echo '<tr class="';
							if($altern==true)
								echo 'altern_true';
							else
								echo 'altern_false';
							echo '"><td class="info_type">',$val['title'],'</td><td class="info_val">';
							if($product_informations[$key]=='yes')
								echo 'Si';
							elseif($product_informations[$key]=='no')
								echo 'No';
							else
								echo $product_informations[$key];
							if(isset($val['unit']))
								echo $val['unit'];
							echo '</td></tr>';
							$altern=!$altern;
						}
					}
				?>
			</table>
			<table class="info extra_info" cellspacing="0">
				<?php
					foreach($array_current_cat['spec'] as $key=>$val)
					{
						if(!isset($val['filter_on_interface_sort_by']) && $product_informations[$key]!='' && $product_informations[$key]!='0')
						{
							echo '<tr class="';
							if($altern==true)
								echo 'altern_true';
							else
								echo 'altern_false';
							echo '"><td class="info_type">',$val['title'],'</td><td class="info_val">';
							if($product_informations[$key]=='yes')
								echo 'Si';
							elseif($product_informations[$key]=='no')
								echo 'No';
							else
								echo $product_informations[$key];
							if(isset($val['unit']))
								echo $val['unit'];
							echo '</td></tr>';
							$altern=!$altern;
						}
					}
					if($product_informations['ean']!='')
					{
						echo '<tr class="';
						if($altern==true)
							echo 'altern_true';
						else
							echo 'altern_false';
						echo '"><td class="info_type">Ean</td><td class="info_val"><span itemprop="identifier" content="ean:',$product_informations['ean'],'">',$product_informations['ean'],'</span></td></tr>';
						$altern=!$altern;
					}
					if($product_informations['product_code']!='')
					{
						echo '<tr class="';
						if($altern==true)
							echo 'altern_true';
						else
							echo 'altern_false';
						echo '"><td class="info_type">Code produit</td><td class="info_val"><span itemprop="identifier" content="product_code:',$product_informations['product_code'],'">',$product_informations['product_code'],'</span></td></tr>';
						$altern=!$altern;
					}
				?>
			</table>
		</div>
		<div style="float:right;">
			<span class="fake_link extra_info2" onclick='$(".extra_info2").css("display","none");$(".extra_info").css("display","block");'>Plus de détails</span>
			<span class="fake_link extra_info" onclick='$(".extra_info").css("display","none");$(".extra_info2").css("display","block");'>Moins de détails</span>
		</div>
		<?php
		if(count($comments)>0)
		{
			echo '<div id="aviso" class="aviso" itemprop="review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">';
			echo '<span style="float:left;">Avis: &nbsp;</span>';
			$note=round($tot_note/count($comments),1);
			$note_pixel=round(($tot_note/count($comments))/5*80);
			echo '<div style="height:16px;width:',$note_pixel,'px;background:url(\'/images/note/';
			if(count($comments)<2)
				echo '3';
			else if($note<3)
				echo '2';
			else
				echo '0';
			echo '.png\') top left;float:left;" title="',count($comments),' avis';
			echo '"></div><div style="height:16px;width:',(80-$note_pixel),'px;background:url(\'/images/note/1.png\') top right;float:left;" title="',count($comments),' avis';
			echo '"></div> &nbsp; Nota: <span itemprop="rating">',$note,'</span>/5 con <span itemprop="count">',count($comments),'</span> avis';
		}
		else
			echo '<div id="aviso" class="aviso"><span style="float:left;">Avis: &nbsp;</span><span style="font-style:italic;">Pas d\'avis pour le moment</span>';
		?>
		</div>
		<div class="addNewAvis"></div>
		<div id="all_comment">
			<div class="aviso2 g" style="clear:both;">
				<div style="margin:-4px 250px;white-space:nowrap;"><img src="/images/loader.gif" alt="" height="24px" width="24px" style="vertical-align:middle;" /> Chargements des avis</div>
			</div>
		</div>
		</div>
	</div>
	<div class="block_internal">
		<h2 id="precios">Prix
		<?php
		if(count($prices_list)>0)
		{
			?> <span itemprop="offerDetails" itemscope itemtype="http://data-vocabulary.org/Offer-aggregate"> de 
			<span itemprop="lowPrice" content="<?php echo str_replace('.',',',$prices_list[0]['price']); ?>"><?php echo $prices_list[0]['price']; ?></span><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?> jusqu'as 
			<span itemprop="highPrice" content="<?php echo str_replace('.',',',$prices_list[count($prices_list)-1]['price']); ?>"><?php echo $prices_list[count($prices_list)-1]['price']; ?></span><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?>
			<meta itemprop="currency" content="EUR" />
			</span><?php
		}
		?>
		</h2>
		<div class="body_block">
			<table cellspacing="0">
				<tr>
					<td class="table_tab1">Boutique</td><td class="table_tab1">Prix</td><td class="table_tab1">Total</td><td class="table_tab1">Disponnibilité</td>
				</tr>
				<?php
				$alert=true;
				foreach($prices_list as $shop)
				{
					if($alert)
						echo '<tr class="altern_true">';
					else
						echo '<tr class="altern_false">';
					?>
						<td class="table_tab1">
							<span class="title_shop"><a href="<?php echo unknownlib_text_htmlentities_drop($shop['url']); ?>" class="outlink"><?php echo unknownlib_text_htmlentities_drop($shop['name']); ?></a><br /></span>
							<span class="info_shop"><a href="/boutiques/<?php echo unknownlib_text_htmlentities_drop($shop['url_alias_for_seo']); ?>.html">Info boutique</a><br /></span>
							<?php
							if(isset($shop['note_count']) && $shop['note_count']>0)
							{
								echo '<div style="height:16px;width:',$shop['note']*16,'px;background:url(\'/images/note/';
								if($shop['note_count']<$GLOBALS['unknownlib']['site']['min_note_count'])
									echo '3';
								else if($shop['note']<3)
									echo '2';
								else
									echo '0';
								echo '.png\') top left;float:left;" title="',$shop['note_count'],' avis';
								echo '"></div><div style="height:16px;width:',((5-$shop['note'])*16),'px;background:url(\'/images/note/1.png\') top right;float:left;" title="',$shop['note_count'],' avis';
								echo '"></div><br style="clear:both;" /><span style="font-size:10px;">Note: ';
								echo $shop['note'];
								echo '/5, ';
								echo $shop['note_count'];
								echo ' avis';
								echo '</span>';
							}
							?>
						</td>
						<td class="table_tab1">
							<a href="<?php echo unknownlib_text_htmlentities_drop($shop['url']); ?>" class="outlink">
								<span class="product_price"><?php echo $shop['price']; ?><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?></span><br />
								<span style="font-size:10px;">Voir l'offre</span>
							</a>
						</td>
						<td class="table_tab1">
							<span class="product_price"><?php echo number_format($shop['price']+$shop['price_port'],2); ?><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?></span><br />
							<span style="font-size:10px;">Port: <?php echo ($shop['price_port']); ?><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?></span>
						</td>
						<td class="table_tab1">
							<?php
							if($shop['delivery']=='In stock')
								echo 'En stock';
							else
								echo $shop['delivery'];
							?>
						</td>
					</tr>
					<?php
					$alert=!$alert;
				}
				?>
			</table>
		</div>
	</div>
</div>
<div class="col_right">
	<?php require 'php-part/top_product_right_sub_cat.php'; ?>
	<?php require 'php-part/new_product_right_sub_cat.php'; ?>
</div>
<?php
$GLOBALS['unknownlib']['site']['extra_footer']='<script type="text/javascript" src="/js/comment.js"></script>';
$GLOBALS['unknownlib']['site']['extra_js']='
s("';
$GLOBALS['unknownlib']['site']['extra_js'].=$_GET['sub_cat'];
$GLOBALS['unknownlib']['site']['extra_js'].='","';
$GLOBALS['unknownlib']['site']['extra_js'].=$product_informations['url_alias_for_seo'];
$GLOBALS['unknownlib']['site']['extra_js'].='",[';
//check the var input
$string_array=array();
foreach($comments as $single_comment)
	$string_array[]='{"login":"'.unknownlib_string_to_json($single_comment['login']).'","note":"'.unknownlib_string_to_json($single_comment['note']).'","comment":"'.unknownlib_string_to_json($single_comment['comment']).'","date":"'.unknownlib_string_to_json($single_comment['date']).'"}';
$GLOBALS['unknownlib']['site']['extra_js'].=implode(',',$string_array);
$GLOBALS['unknownlib']['site']['extra_js'].=']);
';
require 'php-part/footer.php';
?>
