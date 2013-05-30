nS(<?php
$content=unknownlib_rss_parse_xml(unknownlib_urlopen($GLOBALS['unknownlib']['site']['external_feed_url']));
$final_content=array();
foreach($content as $val)
{
	$new_item=array('link'=>$val['link'],'title'=>$val['title'],'important'=>'0');
	if(preg_match('#iphone|ipad|ipod|Apple|iCloud|Mac OS X#i',$val['title']))
		$new_item['icon']='ipod.png';
	elseif(preg_match('#linux|OpenSUSE|RedHat|debian|ubuntu|kubuntu#i',$val['title']))
		$new_item['icon']='linux.png';
	elseif(preg_match('#.cran#i',$val['title']))
		$new_item['icon']='screen.png';
	elseif(preg_match('#libreoffice|openoffice#i',$val['title']))
		$new_item['icon']='application.png';
	elseif(preg_match('#wireless|wifi#i',$val['title']))
		$new_item['icon']='wireless.png';
	elseif(preg_match('#tienda|Web Store#i',$val['title']))
		$new_item['icon']='cash.png';
	elseif(preg_match('#hdd|Disque dur|Western Digital|Seagate#i',$val['title']))
		$new_item['icon']='hdd.png';
	elseif(preg_match('#mobil|telecom|smartphone|tablet|phone|movistar|Telefónica|Nokia|Symbian|Vodafone|Galaxy|Bouygues|HTC#i',$val['title']))
		$new_item['icon']='phone.png';
	elseif(preg_match('#contacts sociales|r.seau soci|IPv6|fibre#i',$val['title']))
		$new_item['icon']='connect.png';
	elseif(preg_match('#s.curit.|Antivirus|BitDefender|dangeureu|cyber attaque|vol de|pass#i',$val['title']))
		$new_item['icon']='lock.png';
	elseif(preg_match('#livre#i',$val['title']))
		$new_item['icon']='book.png';
	elseif(preg_match('#firefox|page web|Internet Explorer|Google|sites? web|wikipedia|messagerie|Orange|chrome|WordPress#i',$val['title']))
		$new_item['icon']='web.png';
	elseif(preg_match('#facebook|Twitter#i',$val['title']))
		$new_item['icon']='facebook.png';
	elseif(preg_match('#PS3|Nintendo|Sony|PlayStation|jeu|xbox|ps2|Tamagotchi|kinect| EA|EA |Humble Bundle|Consoles#i',$val['title']))
		$new_item['icon']='game.png';
	elseif(preg_match('#Geforce|radeon|moteur 3D#i',$val['title']))
		$new_item['icon']='game.png';
	elseif(preg_match('#vuln.rabilit#i',$val['title']))
		$new_item['icon']='bomb.png';
	elseif(preg_match('#bug#i',$val['title']))
		$new_item['icon']='bomb.png';
	elseif(preg_match('#gps#i',$val['title']))
		$new_item['icon']='media-flash.png';
	elseif(preg_match('#record de#i',$val['title']))
		$new_item['icon']='stat.png';
	elseif(preg_match('#windows#i',$val['title']))
		$new_item['icon']='cd.png';
	elseif(preg_match('#cam.ra|photo#i',$val['title']))
		$new_item['icon']='camera.png';
	//if other not found
	elseif(preg_match('#t.l.charg.| r.seau |YouTube#i',$val['title']))
		$new_item['icon']='connect.png';
	elseif(preg_match('#performance| paiment |crise|commission euro|IBM#i',$val['title']))
		$new_item['icon']='coins.png';
	elseif(preg_match('#acc.l.r.|plus rapide|pcmark#i',$val['title']))
		$new_item['icon']='speed.png';
	elseif(preg_match('#exel|office#i',$val['title']))
		$new_item['icon']='x-office-spreadsheet.png';
	elseif(preg_match('#audio#i',$val['title']))
		$new_item['icon']='audio-headset.png';
	elseif(preg_match('#laptop|portable#i',$val['title']))
		$new_item['icon']='computer-laptop.png';
	elseif(preg_match('#BenQ|Television|Moniteur#i',$val['title']))
		$new_item['icon']='screen.png';
	else
		$new_item['icon']='computer.png';
	$new_item['date']=preg_replace('#^.*([0-9]{1,2} [a-z]+ 20[0-9]{2}).*$#isU','$1',$val['pubDate']);
	$new_item['title']=preg_replace('#^ +#','',$new_item['title']);
	$new_item['title']=preg_replace('# +$#','',$new_item['title']);
	$final_content[]=$new_item;
}
echo unknownlib_array_to_json($final_content);
?>);