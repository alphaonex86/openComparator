<?php
@header("HTTP/1.0 404 Not Found");//with @ to prevent error message to generate html page

if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['title']	= 'Page non trouvé';
$GLOBALS['unknownlib']['site']['description']	= 'Page non trouvé';
$GLOBALS['unknownlib']['site']['keywords']	= 'Page non trouvé';
$GLOBALS['unknownlib']['site']['page_title']	= 'Page non trouvé';
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<div class="block_internal">
		<h2 id="note">Page non trouvé</h2>
		<div class="body_block">
		<img src="/images/security-medium.png" alt="" style="float:left;height:64px;width:64px;" /> <span style="font-size:2em;">Page non trouvé. Vous allez être redirigé.</span>
		<br style="clear:both;" />
		<script type="text/javascript">
		<!--
		setTimeout('location.href=\'/\';',5000);
		// -->
		</script>
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