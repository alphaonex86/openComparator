<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['extra_header']= '<link rel="stylesheet" type="text/css" href="/css/tab.css" media="all" />
<link rel="stylesheet" type="text/css" href="/css/product.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']	= unknownlib_text_htmlentities_drop($shop_informations['name']);
$GLOBALS['unknownlib']['site']['description']	= 'Prix dans la boutique '.$shop_informations['name'];
$GLOBALS['unknownlib']['site']['keywords']	= unknownlib_text_operation_clean_text($shop_informations['name']);
$GLOBALS['unknownlib']['site']['page_title']	= array('/'=>'Comparateur de prix en BTC','/boutiques/'=>'Boutiques',''=>unknownlib_text_htmlentities_drop($shop_informations['name']));
//load the header
require 'php-part/header.php';
$comments = unknownlib_comparator_get_comment_shop_seo($_GET['seo']);
$tot_note=0;
foreach($comments as $single_comment)
	$tot_note+=$single_comment['note'];
?>
<div class="col_left ">
	<div class="block_internal">
		<span class="hreview-aggregate">
		<span class="item"><h2 id="<?php echo str_replace(' ','_',unknownlib_text_operation_clean_text($shop_informations['name'])); ?>" class="fn"><?php echo unknownlib_text_htmlentities_drop($shop_informations['name']); ?></h2></span>
		<div class="body_block"><img src="<?php
		if(file_exists(unknownlib_clean_path($_SERVER['DOCUMENT_ROOT'].'/boutiques/'.$shop_informations['url_alias_for_seo'].'.png')))
			echo '/boutiques/'.$shop_informations['url_alias_for_seo'].'.png';
		else
			echo '/images/no-photo-ban.png';
		?>" alt="" style="height:31px;width:88px;float:left;margin:5px;" />
		<div>
			<table class="info" cellspacing="0">
				<tr class="altern_true">
					<td class="info_type">Nom</td><td class="info_val"><?php echo unknownlib_text_htmlentities_drop($shop_informations['name']); ?></td>
				</tr>
				<tr class="altern_false">
					<td class="info_type">Site</td><td class="info_val"><a href="<?php echo unknownlib_text_htmlentities_drop($shop_informations['site']); ?>" target="_blank"><?php echo unknownlib_text_htmlentities_drop($shop_informations['site']); ?></a></td>
				</tr>
				<tr class="altern_true">
					<td class="info_type">Forme de paiment</td><td class="info_val"><?php echo unknownlib_text_htmlentities_drop($shop_informations['payment']); ?></td>
				</tr>
				<tr class="altern_false">
					<td class="info_type">Zone d'envoie</td><td class="info_val"><?php echo unknownlib_text_htmlentities_drop($shop_informations['delivery_zones']); ?></td>
				</tr>
				<tr class="altern_true">
					<td class="info_type">Assurance</td><td class="info_val"><?php echo unknownlib_text_htmlentities_drop($shop_informations['insurance_safety']); ?></td>
				</tr>
			</table>
		<br style="clear:both;" />
		</div>
		<?php
		if(count($comments)>0)
		{
			echo '<div id="aviso" class="aviso">';
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
			echo '"></div> &nbsp; Note: <span class="rating">',$note,'</span>/5 con <span class="count">',count($comments),'</span> avis';
		}
		else
			echo '<div id="aviso" class="aviso"><span style="float:left;">Avis: &nbsp;</span><span style="font-style:italic;">Pas d\'avis</span>';
		?>
		</div>
		<div class="addNewAvis"></div>
		<div id="all_comment">
			<div class="aviso2 g" style="clear:both;">
				<div style="margin:-4px 250px;white-space:nowrap;"><img src="/images/loader.gif" alt="" height="24px" width="24px" style="vertical-align:middle;" /> Chargement des avis</div>
			</div>
		</div>
		</div>
		</span>
	</div>
	<div class="block_internal">
		<h2>Top produits</h2>
		<div class="body_block">
		<?php
			$products=unknownlib_comparator_get_top_product(9,'','',$shop_informations['id']);
			if(count($products)==0)
				echo 'Pas de produits';
			else
			{
				foreach($products as $current_product)
				{
					$url_fiche='/'.$GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']].'/'.$current_product['url_alias_for_seo'].'.html';
					?>
					<div style="float:left;width:223px;">
						<strong class="title_product" style="height:32px;overflow:hidden;display:block;">
							<a href="<?php echo $url_fiche; ?>">
								<?php echo unknownlib_text_htmlentities_drop($current_product['title']); ?>
							</a>
						</strong>
						<table>
							<tr>
								<td style="width:64px;">
									<a href="<?php echo $url_fiche; ?>">
										<img src="<?php echo unknownlib_comparator_get_thumb_mini($GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']],$current_product['url_alias_for_seo'],$current_product['thumb_overwrite']); ?>" alt="" title="<?php echo unknownlib_text_htmlentities_drop($current_product['title']); ?>" style="height:64px;width:64px;" />
									</a>
								</td>
								<td>
									<span class="inter_product">a partir de</span><br />
									<span style="font-size:22px;font-weight:bold;"><?php echo $current_product['price']; ?><?php echo $GLOBALS['unknownlib']['site']['price_unit']; ?></span><br />
									<span class="boutique_product"><?php echo unknownlib_text_with_number('tienda',$current_product['nbr_shop']); ?></span><br />
								</td>
							</tr>
						</table>
					</div>
					<?php
				}
			}
		?>
		<div style="clear:both;"></div>
		</div>
	</div>
</div>
<div class="col_right">
	<?php require 'php-part/top_product_right.php'; ?>
	<?php require 'php-part/new_product_right.php'; ?>
</div>
<?php
$GLOBALS['unknownlib']['site']['extra_footer']='<script type="text/javascript" src="/js/comment.js"></script>';
$GLOBALS['unknownlib']['site']['extra_js']='s("boutiques","'.$shop_informations['url_alias_for_seo'].'",[';
//check the var input
$string_array=array();
foreach($comments as $single_comment)
	$string_array[]='{"login":"'.unknownlib_string_to_json($single_comment['login']).'","note":"'.unknownlib_string_to_json($single_comment['note']).'","comment":"'.unknownlib_string_to_json($single_comment['comment']).'","date":"'.unknownlib_string_to_json($single_comment['date']).'"}';
$GLOBALS['unknownlib']['site']['extra_js'].=implode(',',$string_array);
$GLOBALS['unknownlib']['site']['extra_js'].=']);';

require 'php-part/footer.php';
