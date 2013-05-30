<?php
session_start();
$_SESSION['random']=rand(100000,999999);

// Là, on défini le header de la page pour la transformer en image
header('Content-type: image/png');
// Là, on crée notre image
$image = imagecreate(56,17);

// On défini maintenant les couleurs
// Couleur de fond :
$arriere_plan = imagecolorallocate($image, 255, 255, 255); // Au cas où on utiliserai pas d'image de fond, on utilise cette couleur là.
// Autres couleurs :
$avant_plan = imagecolorallocate($image, 0, 0, 0); // Couleur des chiffres

imagestring($image, 5, 2, 0, $_SESSION['random'], $avant_plan);
imagepng($image);
?>