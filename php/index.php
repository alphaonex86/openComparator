<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['extra_header']= '<link rel="stylesheet" type="text/css" href="/css/index.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']	= 'Comparateur de prix en BTC';
$GLOBALS['unknownlib']['site']['description']	= 'Grace à compatif BTC, vous pouvez comparer les produits informatique vendu en BTC pour trouver le meilleur prix';
$GLOBALS['unknownlib']['site']['keywords']	= 'Comparateur de prix,BTC';
$GLOBALS['unknownlib']['site']['page_title']	= 'Les meilleurs prix en BTC pour vos achats';
//load the header
require_once 'php-part/header.php';
?>
<div class="col_left">
	<?php require 'php-part/list_cat_left.php'; ?>
	<div class="block_internal">
		<h2 id="top_productos">Top produits</h2>
		<div class="body_block">
		<?php
			$products=unknownlib_comparator_get_top_product(12);
			if(count($products)==0)
				echo 'Il n\'y a pas de produits populaires';
			else
			{
				foreach($products as $current_product)
				{
					if(isset($GLOBALS['unknownlib']['site']['sub_cat_translation'][$current_product['table_product']]))
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
										<span class="boutique_product"><?php echo unknownlib_text_with_number('boutique',$current_product['nbr_shop']); ?></span><br />
									</td>
								</tr>
							</table>
						</div>
						<?php
					}
				}
			}
		?>
		<div style="clear:both;"></div>
		</div>
	</div>
</div>
<div class="col_right">
	<?php require 'php-part/new_product_right.php'; ?>
	<div class="block_internal">
		<h2 id="top_productos">Actualités</h2>
		<div class="body_block news">
			<div style="margin:0 40px"><img src="/images/loader.gif" alt="" height="24px" width="24px" style="vertical-align:middle;" /> Chargements des actualités</div>
		</div>
	</div>
</div>
<?php
$GLOBALS['unknownlib']['site']['extra_footer']='<script type="text/javascript" src="/js/news-content.js"></script>';
$GLOBALS['unknownlib']['site']['extra_js']='nC(15,40);';

require_once 'php-part/footer.php';
?>
