<h2> Inscription </h2>

<?php
echo '<a href="index.php?page=11&inscription=particulier"><button class="btn-green">Vous êtes un particulier</button></a><br>';
echo '<a href="index.php?page=11&inscription=entreprise"><button class="btn-green">Vous êtes une entreprise</button></a><br>';

$inscription = isset($_GET['inscription']) ? $_GET['inscription'] : '';

switch ($inscription) {
    case "particulier":
        require_once("vue/auth/vue_inscription_particulier.php");
        break;
    case "entreprise":
        require_once("vue/auth/vue_inscription_entreprise.php");
        break;
}
?>

<h2> Connexion </h2>

<?php
require_once("vue/auth/vue_connexion.php");