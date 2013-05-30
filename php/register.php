<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['extra_header']	= '<link rel="stylesheet" type="text/css" href="/css/index.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']		= 'S\'enregistrer sur le comparateur';
$GLOBALS['unknownlib']['site']['description']		= 'S\'enregistrer sur le comparateur';
$GLOBALS['unknownlib']['site']['keywords']		= 'S\'enregistrer sur le comparateur';
$GLOBALS['unknownlib']['site']['page_title']		= 'S\'enregistrer sur le comparateur';
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<div class="block_internal">
		<h2 id="login">S'enregistrer sur le comparateur</h2>
		<div class="body_block">
		<img src="/images/im-user.png" alt="" style="float:left;height:64px;width:64px;" /> 
		<script type="text/javascript" src="/js/register.js"></script>
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