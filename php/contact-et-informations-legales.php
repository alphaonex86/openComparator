<?php
if(!isset($GLOBALS['unknownlib']['site']['engine_loaded']))
	die('bye');
//set the header variable
$GLOBALS['unknownlib']['site']['extra_header']= '<link rel="stylesheet" type="text/css" href="/css/index.css" media="all" />';
$GLOBALS['unknownlib']['site']['title']	= 'Contact avec le comparateur de prix';
$GLOBALS['unknownlib']['site']['description']	= 'Avec ce compateur de prix, recherchez les meilleur prix de maniére éfficace';
$GLOBALS['unknownlib']['site']['keywords']	= 'contact,comparateur de prix';
$GLOBALS['unknownlib']['site']['page_title']	= 'Contact avec le comparateur de prix BTC';
//load the header
require 'php-part/header.php';
?>
<div class="col_left">
	<div class="block_internal">

		<h2 id="note">Contact et informations légales</h2>
		<div class="body_block">
			<img src="/images/utilities-file-archiver.png" alt="" style="float:left" height="64px" width="64px" /> <span style="font-size:2em;">S'enregistrer</span><br /><br />
			Pour s'enregistrer envoyé par email:<br />
			<ul>
			<li>Un lien vers en CVS réguliérement généré de votre liste de produits (";" pour les séparateurs)<br />
			Vous pouvez utiliser ce module pour <b>prestashop</b>: [Lien]<br />
				<ul>
				<li>Categorie (Carte mére, cpu, ...)</li>
				<li>Sous-catégorie (facultatif)</li>
				<li>Url du produits</li>
				<li>Url de l'image (130x130 ou plus)</li>
				<li>Code produit du constructeur (P8H61-M, ...) ou/et EAN</li>
				<li>Marque</li>
				<li>Nom du produit</li>
				<li>Description du produit (facultatif)</li>
				<li>Prix</li>
				<li>Prix d'envoie</li>
				<li>Disponiblité (en stock, ...)</li>
				<li>Url avec les détails technique formaté pour une analise (récommandé)<br />
					<ul>
					<li>Fréquence</li>
					<li>Mémoire</li>
					<li>Cache</li>
					<li>...</li>
					</ul>
				</li>
				</ul>
			</li>
			<li>Nom de la boutique</li>
			<li>Format de paiment</li>
			<li>Zone d'envoie</li>
			<li>Logo 88x31</li>
			<li>Un lien vers notre comparatif est bienvenu</li>
			</ul>
			<br style="clear:both;" />
			<img src="/images/application-x-font-otf.png" alt="" style="float:left" height="64px" width="64px" /> <span style="font-size:2em;">Contact</span><br />
			<br />
			Vous pouvez nous contacter de plusieurs façon:<br />
			<ul>
			<li>Email: <span class="underline">email@site.com</span>
			</ul>
			<br style="clear:both;" />
			<img src="/images/knotes.png" alt="" style="float:left" height="64px" width="64px" /> <span style="font-size:2em;">Informations légales</span><br />
			<br />
			En consultant ce site, vous acceptez sans réserve les conditions suivante. Ces conditions peuvent être modifié à tout moment et sans pré-avis.
			<br style="clear:both;" />
			<br style="clear:both;" />
			<img src="/images/basket.png" alt="" style="float:left" height="64px" width="64px" /> <span style="font-size:2em;">Copyright</span><br />
			<br />
			Tout le contenu est sous copyright. Les icones du thémes oxygen sont sous license http://creativecommons.org/licenses/by-sa/3.0/. Pour les images et informations des produits, elles appartiennes à leurs boutiques respective.<br />
			Chaque personne est responable de ses écrit, inclut dans les commentaires. Le site se réverse le droit de supprimer ou modifier à n'importe quel moment ces derniers.<br />
			<br />
			<br />
			<br style="clear:both;" />
			<img src="/images/konqueror.png" alt="" style="float:left" height="64px" width="64px" /> <span style="font-size:2em;">Site web</span><br />
			Le site n'as pas de controle sur les éléments externe du site et ne peut donc pas être tenu pour responsable. La responsabilité reviens à ces sources externes. Ce site ne peu être tenu comme responsable de l'indisponibilité d'un de ces sites.
			<br />
			<br />
			<br style="clear:both;" />
			<img src="/images/utilities-file-archiver.png" alt="" style="float:left" height="64px" width="64px" /> <span style="font-size:2em;">Produit</span><br />
			Les prix sont indicatif et peuvent avoir changé. Seul les prix sur les pages des sites de vente sont les prix réél. Les prix sont avec la TVA sauf mention contraire. Les photos ne sont pas contractuel.<br />
			Les détails téchniques étant tirer des pages tiers. Le site ne peu être tenu comme responable lors d'une erreur dans celle si.
			<br />
			<br />
			<br style="clear:both;" />
			
			<br />
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
