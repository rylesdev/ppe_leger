<?php
if (isset($_SESSION['roleUser']) && $_SESSION['roleUser'] != "admin") {
    $idUser = $_SESSION['idUser'];

    // Initialisation
    $idCommandeSelectionnee = $_POST['idCommandeSelectionnee'] ?? null;
    $tri = $_POST['tri'] ?? null;

    // Récupérer toutes les commandes pour le menu
    $toutesLesCommandes = $unControleur->selectCommandeByUser($idUser);

    // Gestion combinée sélection + tri
    if ($idCommandeSelectionnee) {
        // Cas 1 : Commande spécifique avec tri
        $lesCommandes = $unControleur->selectCommandeByIdTri(
            $idCommandeSelectionnee,
            $tri
        );
    } else {
        // Cas 2 : Toutes les commandes avec tri
        $lesCommandes = $unControleur->selectCommandeTri($idUser, $tri);
    }

    require_once("vue/commande/vue_commande.php");

} elseif (!isset($_SESSION['emailUser'])) {
    echo "<h3 style='color: red;'>Vous devez être connecté pour accéder à cette page.</h3>";
} else {
    echo "<h3 style='color: red;'>Page indisponible pour le rôle admin.</h3>";
}