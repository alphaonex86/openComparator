<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['extra_header']= '<link rel="stylesheet" type="text/css" href="/css/index.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']	= 'Message dans le comparateur de prix';
$GLOBALS['unknownlib']['site']['description']	= 'Message dans le comparateur de prix';
$GLOBALS['unknownlib']['site']['keywords']	= 'comparateur de prix';
$GLOBALS['unknownlib']['site']['page_title']	= array('/'=>'Comparateur de prix en BTC',''=>'Message');
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<?php
	$note_index='Le site est en création, merci de votre compréansion';
	if(isset($note_index) && $note_index!='')
	{
	?>
	<div class="block_internal">
		<h2 id="Message">Message</h2>
		<div class="body_block"><?php echo $text_main; ?><br style="clear:both;" />
		</div>
	</div>
	<?php
	}
	?>
	<div class="block_internal">
		<h2 id="comparar_los_precios">Comparer les prix</h2>
		<div class="body_block">
		<?php
			foreach($GLOBALS['unknownlib']['site']['categories'] as $cat=>$value)
			{ ?>
			<table class="block_main">
			<tr>
				<td><div class="block_main_img thumb_preview"><img src="<?php echo $value['thumb']; ?>" alt="" title="" /></div></td>
				<td style="width:143px;">
				<h3><a href="/<?php echo $cat; ?>.html"><?php echo $value['title']; ?></a></h3>
				<div class="block_cat_sub">
				<?php
				$nbr_hiden=0;
				foreach($value['sub_cat'] as $cat_sub=>$value_sub)
				{
					if($value_sub['on_main_page'])
					{ ?><a href="/<?php echo $cat_sub; ?>.html"><?php echo $value_sub['title']; ?></a><br /><?php }
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
</div>
<div class="col_right">
	<?php require 'php-part/top_product_right.php'; ?>
	<?php require 'php-part/new_product_right.php'; ?>
</div>
<?php
require 'php-part/footer.php';
?>