<?php
echo '<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="http://'.$_SERVER['HTTP_HOST'].'/rss.xml" rel="self" type="application/rss+xml" />
<description>Comparatif BTC</description>
<link>http://'.$_SERVER['HTTP_HOST'].'/</link>
<title>Comparatif BTC</title>
<language>fr</language>';

$products=array();

$reply_product = unknownlib_mysql_query('SELECT * FROM `product_base_information` WHERE '.$extra_mysql_filter.' `last_time_not_used`=0 ORDER BY date DESC');
while($data_product = mysql_fetch_array($reply_product))
{
	$reply_base_thumbs = unknownlib_mysql_query('SELECT * FROM `product_base_thumbs` WHERE `unique_identifier_product`=\''.addslashes($data_product['unique_identifier']).'\'');
	if($data_base_thumbs = mysql_fetch_array($reply_base_thumbs))
		$data_product['thumb_overwrite']=$data_base_thumbs['thumb_overwrite'];
	else
		$data_product['thumb_overwrite']='';
	$reply_extra_informations = unknownlib_mysql_query('SELECT * FROM `information_extra_'.addslashes($data_product['table_product']).'` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\'');
	$reply_count_prices = unknownlib_mysql_query('SELECT COUNT(*) AS count FROM `prices` WHERE `unique_identifier_product`=\''.$data_product['unique_identifier'].'\'');
	if(($data_extra_informations = mysql_fetch_array($reply_extra_informations)) && ($data_count_prices = mysql_fetch_array($reply_count_prices)) && $data_count_prices['count']>0)
		$products[]=array_merge($data_extra_informations,$data_product);
}

foreach($products as $data)
{
	$url='http://'.$_SERVER['HTTP_HOST'].'/'.$data['table_product'].'/'.$data['url_alias_for_seo'].'.html';
	echo '
	<item>
	<title>'.$data['title'].'</title>
	<link>'.$url.'</link>
	<description>';
	$array_current_cat=$GLOBALS['unknownlib']['site']['categories'][$GLOBALS['unknownlib']['site']['reverse_link'][$data['table_product']]]['sub_cat'][$data['table_product']];
	ob_start();
	?>
	<h2 id="<?php echo str_replace(' ','_',unknownlib_text_operation_clean_text($data['title'])); ?>"><span itemprop="name"><?php echo unknownlib_text_htmlentities_drop($data['title']); ?></span><?php
	?><span itemprop="category" content="<?php echo $array_current_cat['english_name']; ?>"></span></h2>
	<?php
	$title=unknownlib_text_htmlentities_drop($data['title']);
	if($data['thumb_overwrite']!='')
		$file_thumb=unknownlib_clean_path('/'.$data['table_product'].'/thumb_overwrite/'.$data['thumb_overwrite']);
	else
		$file_thumb=unknownlib_clean_path('/'.$data['table_product'].'/'.$data['url_alias_for_seo']);
	if(file_exists($_SERVER['DOCUMENT_ROOT'].$file_thumb.'.jpg'))
		echo '<img src="http://'.$_SERVER['HTTP_HOST'].$file_thumb.'.jpg" alt="'.$title.'" title="'.$title.'" height="130px" width="130px" style="float:left;margin:0 5px" itemprop="image" />';
	elseif(file_exists($_SERVER['DOCUMENT_ROOT'].$file_thumb.'-mini.jpg'))
		echo '<img src="http://'.$_SERVER['HTTP_HOST'].$file_thumb.'-mini.jpg" alt="'.$title.'" title="'.$title.'" height="64px" width="64px" style="float:left;margin:0 5px" itemprop="image" />';
	else
		echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/images/no-photo.png" alt="" height="130px" width="130px" style="float:left;margin:0 5px" />';
	?>
	<table cellspacing="0">
		<tr class="altern_true"><td style="background-color:#eee;">Marca</td><td style="background-color:#eee;"><span itemprop="brand"><?php
		if(isset($data['mark']) && $data['mark']!='')
			echo $data['mark'];
		else
			echo 'Générique';
		?></span></td></tr>
		<?php
			$altern=false;
			foreach($array_current_cat['spec'] as $key=>$val)
			{
				if($data[$key]!='' && $data[$key]!='0')
				{
					echo '<tr style="';
					if($altern==true)
						echo 'background-color:#eee;';
					else
						echo 'background-color:#fff;';
					echo '"><td class="info_type">',$val['title'],'</td><td class="info_val">';
					if($data[$key]=='yes')
						echo 'Si';
					elseif($data[$key]=='no')
						echo 'No';
					else
						echo $data[$key];
					if(isset($val['unit']))
						echo $val['unit'];
					echo '</td></tr>';
					$altern=!$altern;
				}
			}
			echo '<tr style="';
			if($altern==true)
				echo 'background-color:#eee;';
			else
				echo 'background-color:#fff;';
			echo '"><td class="info_type">Ean</td><td class="info_val"><span itemprop="identifier" content="ean:',$data['ean'],'">',$data['ean'],'</span></td></tr>';
			$altern=!$altern;
		?>
	</table><br style="clear:both" />
	<?php
	$content=ob_get_contents();
	ob_end_clean();
	echo htmlspecialchars($data['mark'].' '.$data['title'].'<br />'.$content,ENT_COMPAT,'utf-8');
	echo '</description>
	<comments>'.$url.'#avis</comments>
	<author>alpha_one_x86@first-world.info (brule herman)</author>
	<pubDate>'.date('D, d M Y H:i:s',$data['date']).' +0100</pubDate>
	<guid>'.$url.'</guid>
	<source url="http://'.$_SERVER['HTTP_HOST'].'/">http://'.$_SERVER['HTTP_HOST'].'/</source>
	</item>';
}

echo '
	</channel>
</rss>
';
?> 
