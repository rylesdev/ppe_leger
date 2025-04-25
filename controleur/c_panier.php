<?php
if (!isset($_SESSION['emailUser'])) {
    echo "<h3 style='color: red;'>Vous devez être connecté pour accéder à cette page.</h3>";
    exit();
}

if (isset($_SESSION['roleUser']) && $_SESSION['roleUser'] == "admin") {
    echo "<h3 style='color: red;'>Page indisponible pour le rôle admin.</h3>";
    exit();
}

$idUser = $_SESSION['idUser'];

// Gestion des actions
if (isset($_POST['ModifierPanier'])) {
    $idLigneCommande = $_POST['idLigneCommande'];
    $quantiteLigneCommande = $_POST['quantiteLigneCommande'];

    $result = $unControleur->updateLigneCommande($quantiteLigneCommande, $idLigneCommande);
    if ($result) {
        echo "<div class='alert alert-success'>Quantité modifiée avec succès (x$quantiteLigneCommande)</div>";
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $idCommande = $_GET['idCommande'] ?? null;
    $idLigneCommande = $_GET['idLigneCommande'] ?? null;

    switch($action) {
        case "sup":
            if ($idLigneCommande) {
                $unControleur->deleteLigneCommande($idLigneCommande);
                echo "<div class='alert alert-success'>Article supprimé du panier</div>";
            }
            break;

        case "payer":
            $pointAbonnement = $_POST['pointAUtiliser'] ?? 0;

            if ($pointAbonnement > 0 && $unControleur->selectPointAbonnement($idUser)['pointAbonnement'] >= $pointAbonnement) {
                $unControleur->enleverPointAbonnement($pointAbonnement, $idUser);
            }

            $idCommande = $unControleur->selectCommandeEnCours($idUser);
            if ($idCommande) {
                $unControleur->updateCommande($idCommande);

                if ($unControleur->selectDateAbonnement($idUser) > 0) {
                    $resultat = $unControleur->selectNbLigneCommande($idCommande);
                    $pointAbonnement = $resultat['nombreLigneCommande'] * 10;
                    $unControleur->ajouterPointAbonnement($pointAbonnement, $idUser);
                }

                try {
                    $chiffre = rand(1, 5);
                    $unControleur->procedureOffrirLivre($idUser, $chiffre);
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";
                }

                header("Location: https://paypal.me/rylesatm?country.x=FR&locale.x=fr_FR");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Aucune commande en cours trouvée</div>";
            }
            break;
    }
}

// Filtrage des livres
if (isset($_POST['FiltrerPanier'])) {
    $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectAllLivres();
}

require_once("vue/panier/vue_panier.php");