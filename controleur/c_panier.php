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
            $idCommande = $unControleur->selectCommandeEnAttente($idUser)[0][0];

            if (isset($_POST['PayerPaypal'])) {
                if ($idCommande) {
                    $resultUpdateCommande = $unControleur->updateCommande($idCommande);
                    if ($resultUpdateCommande) {
                        echo "<div class='alert alert-success'>La commande a bien été validée et elle est passée en expédiée.</div>";
                    } else if ($resultUpdateCommande == false) {
                        echo "<div class='alert alert-danger'>Erreur : La commande n'a pas pu être validée</div>";
                    }

                    if ($unControleur->selectDateAbonnement($idUser)[0] >= 0) {
                        $resultNbLigneCommande = $unControleur->selectNbLigneCommande($idCommande)[0];
                        $pointAbonnement = $resultNbLigneCommande * 10;
                        $resultPointAbonnement = $unControleur->ajouterPointAbonnement($pointAbonnement, $idUser);
                        if ($resultPointAbonnement) {
                            echo "<div class='alert alert-success'>Vous avez gagné $pointAbonnement points d'abonnement !</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Erreur : Les points d'abonnement n'ont pas pu être ajoutés</div>";
                        }
                    }

                    $chiffre = rand(5, 5);
                    $result = $unControleur->procedureOffrirLivre($idUser, $chiffre);
                    if ($result) {
                        echo "<div class='alert alert-success'>Un livre vous a été offert ! Il est visibile dans la page Commande.</div>";
                    }

                    header("Location: https://paypal.me/rylesatm?country.x=FR&locale.x=fr_FR");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Aucune commande en cours trouvée</div>";
                }
            }

            if (isset($_POST['PayerPoint'])) {
                $pointAUtiliser = $_SESSION['pointAUtiliser'];
                $pointsDisponibles = $unControleur->selectPointAbonnement($idUser)['pointAbonnement'];

                if ($pointAUtiliser > 0 && $pointsDisponibles >= $pointAUtiliser) {
                    if ($idCommande) {
                        $chiffre = rand(5, 5);
                        $result = $unControleur->procedureOffrirLivre($idUser, $chiffre);
                        if ($result) {
                            echo "<div class='alert alert-success'>Un livre vous a été offert ! Il est visibile dans la page Commande.</div>";
                        }

                        $unControleur->enleverPointAbonnement($pointAUtiliser, $idUser);

                        $unControleur->updateCommande($idCommande);

                        echo "<div class='alert alert-success'>Paiement effectué avec $pointAUtiliser points</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Aucune commande en cours trouvée</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Points insuffisants pour effectuer le paiement</div>";
                }
            }
            break;
    }
}

// Gestion du filtre de livres pour la page complète (pas juste le panier)
if (isset($_POST['FiltrerLivre'])) {
    $lesLivres = $unControleur->selectLikeLivre($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectLivre();
}

// Inclure la vue qui affiche le panier
require_once("vue/panier/vue_panier.php");