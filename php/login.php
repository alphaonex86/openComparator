<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['extra_header']	= '<link rel="stylesheet" type="text/css" href="/css/index.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']		= 'Utilisateur dans le comparateur de prix';
$GLOBALS['unknownlib']['site']['description']		= 'Pour enregistrer des commentaires';
$GLOBALS['unknownlib']['site']['keywords']		= 'Utilisateur';
$GLOBALS['unknownlib']['site']['page_title']		= array('/'=>'Comparer les prix',''=>'Utilisateur');
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<div class="block_internal">
		<h2 id="login">Utilisateur</h2>
		<div class="body_block">
		<img src="/images/dialog-password.png" alt="" style="float:left;height:64px;width:64px;" /> 
			<span style="font-size:1em;" class="r">
			<script type="text/javascript" src="/js/login.js"></script>
			<a href="/register.html">[ S'enregistrer ]</a>
			</span>
		<br style="clear:both;" />
		</div>
	</div>
	<?php require 'php-part/list_cat_left.php'; ?>
</div>
<div class="col_right">
	<?php require 'php-part/top_product_right.php'; ?>
	<?php require 'php-part/new_product_right.php'; ?>
</div>
<?php
require 'php-part/footer.php';
?>