<h2> Inscription </h2>

<?php
echo '<a href="index.php?inscription=particulier">Vous êtes un particulier</a><br>';
echo '<a href="index.php?inscription=entreprise">Vous êtes une entreprise</a><br>';

$inscription = isset($_GET['inscription']) ? $_GET['inscription'] : '';

switch ($inscription) {
    case "particulier":
        require_once("controleur/c_inscription_particulier.php");
        break;
    case "entreprise":
        require_once("controleur/c_inscription_entreprise.php");
        break;
}
    ?>