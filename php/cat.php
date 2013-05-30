<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
$array_current_cat=$GLOBALS['unknownlib']['site']['categories'][$_GET['cat']];
//set the header variable
$GLOBALS['unknownlib']['site']['extra_header']= '';
$GLOBALS['unknownlib']['site']['title']	= $array_current_cat['page_title'];
$GLOBALS['unknownlib']['site']['description']	= $array_current_cat['page_desc'];
$GLOBALS['unknownlib']['site']['keywords']	= $array_current_cat['page_keywords'].',comparateur BTC';
$GLOBALS['unknownlib']['site']['page_title']	= array('/'=>'Comparer les prix',''=>$array_current_cat['title']);
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<div class="block_internal">
		<h2 id="<?php echo $_GET['cat']; ?>"><?php echo unknownlib_text_htmlentities_drop($array_current_cat['title']); ?></h2>
		<div class="body_block">
		<?php
			foreach($array_current_cat['sub_cat'] as $sub_cat=>$value)
			{ ?>
			<div class="block_main">
				<strong>
					<a href="/<?php echo $sub_cat;?>/"><?php echo $value['title'];?></a>
				</strong>
			</div>
			<?php }
		?>
		</div>
	</div>
	<div class="block_internal">
		<h2>Top produits</h2>
		<div class="body_block">
		<?php
			$products=unknownlib_comparator_get_top_product(15,$_GET['cat']);
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
									<img src="<?php echo unknownlib_comparator_get_thumb_mini($GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']],$current_product['url_alias_for_seo'],$current_product['thumb_overwrite']); ?>" alt="<?php echo $current_product['title']; ?>" title="<?php echo $current_product['title']; ?>" height="64px" width="64px" />
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
	<div class="block_internal">
		<h2>A voir</h2>
		<div class="body_block" style="text-align:center;">
		<?php
			$products=unknownlib_comparator_get_boosted_product(2,$_GET['cat']);
			if(count($products)==0)
				echo 'Pas de produits';
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
			$products=unknownlib_comparator_get_new_product(2,$_GET['cat']);
			if(count($products)==0)
				echo 'Pas de produits';
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
</div>
<?php
//load the footer
require 'php-part/footer.php';
?>
