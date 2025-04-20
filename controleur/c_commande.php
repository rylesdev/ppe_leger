<?php
if (isset($_SESSION['roleUser']) && $_SESSION['roleUser']!="admin") {
    $idUser = $_SESSION['idUser'];

    require_once("vue/commande/vue_commande.php");


    if (isset($_POST['FiltrerCommande'])) {
        $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
    } else {
        $lesLivres = $unControleur->selectAllLivres($idUser);
    }

} else if (!isset($_SESSION['emailUser'])) {
    echo "<h3 style='color: red;'>Vous devez être connecté pour accéder à cette page.</h3>";
} else {
    echo "<h3 style='color: red;'>Page indisponible pour le rôle admin.</h3";
}