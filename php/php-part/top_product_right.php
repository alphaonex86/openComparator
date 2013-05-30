<div class="block_internal">
	<h2>Top produits</h2>
	<div class="body_block" style="text-align:center;">
	<?php
		$products=unknownlib_comparator_get_top_product(2);
		if(count($products)==0)
			echo 'Pas de produits';
		else
		{
			foreach($products as $current_product)
			{
				if(isset($GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']]))
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
		}
		
	?>
	</div>
</div> 
