<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['record_last_page']=true;
$GLOBALS['unknownlib']['site']['extra_header']= '';
$GLOBALS['unknownlib']['site']['title']	= 'Les produits les plus populaires';
$GLOBALS['unknownlib']['site']['description']	= 'Les produits les plus populaires';
$GLOBALS['unknownlib']['site']['keywords']	= 'top,meilleur produits';
$GLOBALS['unknownlib']['site']['page_title']	= array('/'=>'Comparateur de prix en BTC',''=>'Top produits');
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<div class="block_internal">
		<h2 id="top_productos">Top produits</h2>
		<div class="body_block">
		<?php
			$products=unknownlib_comparator_get_top_product(21);
			if(count($products)==0)
				echo 'No hay un producto popular.';
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
	<?php require 'php-part/boosted_product_right.php'; ?>
	<?php require 'php-part/new_product_right.php'; ?>
</div>
<?php
require 'php-part/footer.php';
?>
