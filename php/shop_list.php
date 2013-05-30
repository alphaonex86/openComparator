<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['record_last_page']=true;
$GLOBALS['unknownlib']['site']['extra_header']= '<link rel="stylesheet" type="text/css" href="/css/tab.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']	= 'Listes des boutiques informatiques';
$GLOBALS['unknownlib']['site']['description']	= 'Listes des boutiques informatiques';
$GLOBALS['unknownlib']['site']['keywords']	= 'boutique,informatique';
$GLOBALS['unknownlib']['site']['page_title']	= array('/'=>'Comparateur de prix en BTC',''=>'Boutique');
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<div class="block_internal">
		<h2 id="top_productos">Boutique informatique</h2>
		<div class="body_block">
		<table cellspacing="0">
			<tr>
				<td class="table_tab1">Boutique</td><td class="table_tab1">Note</td><td class="table_tab1">Avis</td><td class="table_tab1">Prix</td>
			</tr>
			<?php
			$shops=unknownlib_comparator_get_shops_seo();
			$alert=true;
			foreach($shops as $shop)
			{
				if($alert)
					echo '<tr class="altern_true">';
				else
					echo '<tr class="altern_false">';
				?>
					<td class="table_tab1">
						<a href="/boutiques/<?php echo $shop['url_alias_for_seo']; ?>.html#<?php echo $shop['url_alias_for_seo']; ?>"><img src="<?php
						if(file_exists(unknownlib_clean_path($_SERVER['DOCUMENT_ROOT'].'/boutiques/'.$shop['url_alias_for_seo'].'.png')))
							echo '/boutiques/'.$shop['url_alias_for_seo'].'.png';
						else
							echo '/images/no-photo-ban.png';
						?>" style="float:left;" alt="<?php echo htmlentities($shop['name']); ?>" title="<?php echo htmlentities($shop['name']); ?>" /><?php echo unknownlib_text_htmlentities_drop($shop['name']); ?></a>
					</td>
					<td class="table_tab1">
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
							echo '"></div><span style="font-size:10px;"><br />';
							echo $shop['note'];
							echo '/5</span>';
						}
						?>
					</td>
					<td class="table_tab1">
						<a href="/boutiques/<?php echo $shop['url_alias_for_seo']; ?>.html#aviso">
							<?php
							if(!isset($shop['note_count']))
								$shop['note_count']=0;
							echo $shop['note_count'];
							echo ' avis';
							?>
						</a>
					</td>
					<td class="table_tab1">
						<?php
						if(!isset($shop['note_count']))
							$shop['note_count']=0;
						echo $shop['price_count'];
						echo ' prix';
						?>
					</td>
				</tr>
				<?php
				$alert=!$alert;
			}
			?>
		</table>
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