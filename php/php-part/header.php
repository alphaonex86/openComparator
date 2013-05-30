<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<title><?php echo $GLOBALS['unknownlib']['site']['title']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo $GLOBALS['unknownlib']['site']['description']; ?>" />
<meta name="keywords" content="<?php echo $GLOBALS['unknownlib']['site']['keywords']; ?>" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="french" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
<link rel="icon" type="image/png" href="/images/favicon.png" />
<link rel="stylesheet" type="text/css" href="/css/style.css" media="all" />
<?php
if(isset($GLOBALS['unknownlib']['site']['extra_header']))
	echo $GLOBALS['unknownlib']['site']['extra_header'];
?>
</head>
<body>
<noscript><div class="noscript_top">Votre javascript doit être activé</div></noscript>
<div class="top">
	<div class="blockfix blockfix_top">
		<a href="/"><img src="/images/logo.png" alt="" title="Calle-Hardware.com" height="100px" width="380px" /></a>
		<div id="pub-header"></div>
	</div>
<div class="middle">
	<div class="blockfix blockfix_middle">
	<a href="/">Comparateur de prix</a> <img src="/images/dot.png" alt="" height="8px" width="6px" /> <a href="/top.html">Top produits</a> <img src="/images/dot.png" alt="" style="height:8px;width:6px;" /> <a href="/boutiques/">Boutiques</a>
	</div>
</div>
<div class="bottom">
<div class="blockfix blockfix_bottom">
	<div class="title_page"><?php
	if(isset($GLOBALS['unknownlib']['site']['page_title']))
	{
		if(is_array($GLOBALS['unknownlib']['site']['page_title']))
		{
			echo '<div id="breadCrumbs"> ';
			$text='';
			foreach($GLOBALS['unknownlib']['site']['page_title'] as $link => $text_link)
			{
				if($text!='')
					$text.=' &gt; ';
				if($link!='')
					$text.='<a href="'.$link.'">'.$text_link.'</a>';
				else
					$text.='<strong>'.$text_link.'</strong>';
			}
			echo $text;
			echo '</div>';
		}
		else
			echo '<h1>'.$GLOBALS['unknownlib']['site']['page_title'].'</h1>';
	}
	?></div>
<div class="page_internal2">
