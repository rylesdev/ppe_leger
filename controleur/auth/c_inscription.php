<h2> Inscription </h2>

<?php
echo '<a href="index.php?inscription=particulier"><button class="btn-green">Vous êtes un particulier</button></a><br>';
echo '<a href="index.php?inscription=entreprise"><button class="btn-green">Vous êtes une entreprise</button></a><br>';

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