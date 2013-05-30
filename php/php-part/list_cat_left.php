<div class="block_internal">
	<h2 id="comparar_los_precios">Comparer les prix informatiques</h2>
	<div class="body_block">
	<?php
		foreach($GLOBALS['unknownlib']['site']['categories'] as $cat=>$value)
		{ ?>
		<table class="block_main">
		<tr>
			<td><div class="block_main_img thumb_preview"><img src="<?php echo $value['thumb']; ?>" alt="<?php echo $value['title']; ?>" title="<?php echo $value['title']; ?>" height="96px" width="64px" /></div></td>
			<td style="width:143px;">
			<h3><a href="/<?php echo $cat; ?>.html"><?php echo $value['title']; ?></a></h3>
			<div class="block_cat_sub">
			<?php
			$nbr_hiden=0;
			foreach($value['sub_cat'] as $cat_sub=>$value_sub)
			{
				if($value_sub['on_main_page'])
				{ ?><a href="/<?php echo $cat_sub; ?>/"><?php echo $value_sub['title']; ?></a><br /><?php }
				else
					$nbr_hiden++;
			}
			?>
			</div>
			<?php if($nbr_hiden>0)
				{ ?><a href="/<?php echo $cat; ?>.html" class="all_product">Tout les produits</a><br /><?php }
			?>
			</td>
		</tr>
		</table>
		<?php }
	?>
	</div>
</div> 
