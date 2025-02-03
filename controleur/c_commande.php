<h2> Vos Commandes </h2>

<?php
if (isset($_SESSION['roleUser']) && $_SESSION['roleUser']=="client") {
    $idUser = $_SESSION['idUser'];

    require_once("vue/vue_commande.php");


    if (isset($_POST['FiltrerCommande'])) {
        $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
    } else {
        $lesLivres = $unControleur->selectAllLivres($idUser);
    }

} else {
    echo "Page indisponible pour le rôle admin.";
}
?>