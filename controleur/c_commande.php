<?php
if (!isset($_SESSION['emailUser'])) {
    echo "<h3 style='color: red;'>Vous devez être connecté pour accéder à cette page.</h3>";
} elseif (empty($isAdmin) || $isAdmin == 0) {
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

    if (isset($_POST['ValiderAvis'])) {
        $idLivre = $_POST['idLivre'];
        $commentaireAvis = $_POST['commentaireAvis'];
        $noteAvis = $_POST['noteAvis'];

        if ($noteAvis && $commentaireAvis) {
            $result = $unControleur->insertAvis($idLivre, $idUser, $commentaireAvis, $noteAvis);
            if ($result) {
                echo "<div class='alert alert-success'>Votre avis a été enregistré avec succès.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de l'enregistrement de votre avis.</div>";
            }
        } else {
            echo "<h3 style='color:red'> Tous les champs doivent être remplis ! </h3>";
        }
    }
    require_once("vue/commande/vue_commande.php");
} else {
    echo "<h3 style='color: red;'>Page indisponible pour le rôle admin.</h3>";
}