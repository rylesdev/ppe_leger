<?php
if (!isset($_SESSION['emailUser'])) {
    echo "<h3 style='color: red;'>Vous devez être connecté pour accéder à cette page.</h3>";
    exit();
}

if (isset($isAdmin) && $isAdmin == 1) {
    echo "<h3 style='color: red;'>Page indisponible pour le rôle admin.</h3>";
    exit();
}

$idUser = $_SESSION['idUser'];

function fOffrirLivre($idUser, $controleur) {
    $chiffre = rand(1, 5);
    $result = $controleur->procedureOffrirLivre($idUser, $chiffre);

    if (!$result) {
        echo "<div class='alert alert-success'>Un livre vous a été offert ! Il est visible dans la page Commande.</div>";
        return true;
    }
    return false;
}

function fUpdateCommande($idCommande, $controleur) {
    $result = $controleur->updateCommande($idCommande);

    if ($result) {
        echo "<div class='alert alert-success'>La commande a bien été validée et a été expédiée.</div>";
        return true;
    } else {
        echo "<div class='alert alert-danger'>Erreur : La commande n'a pas pu être validée</div>";
        return false;
    }
}

function fAjouterPointAbonnement($idUser, $idCommande, $controleur) {
    $dateAbonnement = $controleur->selectDateAbonnement($idUser);
    if (!empty($dateAbonnement) && isset($dateAbonnement[0][0]) && $dateAbonnement[0][0] > 0) {
        $nbArticles = $controleur->selectNbLigneCommande($idCommande);
        if (!empty($nbArticles) && isset($nbArticles[0])) {
            $points = $nbArticles[0] * 10;
            $result = $controleur->ajouterPointAbonnement($points, $idUser);

            if ($result) {
                echo "<div class='alert alert-success'>Vous avez gagné $points points d'abonnement !</div>";
                return true;
            } else {
                echo "<div class='alert alert-danger'>Erreur : Les points d'abonnement n'ont pas pu être ajoutés</div>";
                return false;
            }
        }
    } elseif (empty($dateAbonnement) || !isset($dateAbonnement[0][0])) {
        echo "";
    }
    return false;
}

if (isset($_POST['ModifierPanier'])) {
    $idLigneCommande = $_POST['idLigneCommande'];
    $quantiteLigneCommande = $_POST['quantiteLigneCommande'];

    $result = $unControleur->updateLigneCommande($quantiteLigneCommande, $idLigneCommande);
    if ($result) {
        echo "<div class='alert alert-success'>Quantité modifiée avec succès (x$quantiteLigneCommande)</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur : Impossible de mettre à jour la quantité</div>";
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $idCommande = $_GET['idCommande'] ?? null;
    $idLigneCommande = $_GET['idLigneCommande'] ?? null;

    switch($action) {
        case "sup":
            if ($idCommande) {
                $result = $unControleur->deleteLigneCommande($idLigneCommande);
                if ($result) {
                    echo "<div class='alert alert-success'>Suppression réussie de la ligne de commande.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erreur : Impossible de supprimer la ligne de commande</div>";
                }
            }
            break;

        case "payer":
            $commandeEnAttente = $unControleur->selectCommandeEnAttente($idUser);
            $idCommande = !empty($commandeEnAttente) && isset($commandeEnAttente[0][0]) ? $commandeEnAttente[0][0] : null;

            if (isset($_POST['PayerPaypal'])) {
                if ($idCommande) {
                    fOffrirLivre($idUser, $unControleur);
                    fUpdateCommande($idCommande, $unControleur);
                    fAjouterPointAbonnement($idUser, $idCommande, $unControleur);

                } else {
                    echo "<div class='alert alert-danger'>Aucune commande en cours trouvée</div>";
                }
            }

            if (isset($_POST['PayerPoint'])) {
                $pointAUtiliser = $_SESSION['pointAUtiliser'];
                $pointsDisponibles = $unControleur->selectPointAbonnement($idUser);
                $pointsDisponibles = !empty($pointsDisponibles) ? $pointsDisponibles['pointAbonnement'] : 0;

                if ($pointAUtiliser > 0 && $pointsDisponibles >= $pointAUtiliser) {
                    if ($idCommande) {
                        fOffrirLivre($idUser, $unControleur);
                        $unControleur->enleverPointAbonnement($pointAUtiliser, $idUser);

                        if (fUpdateCommande($idCommande, $unControleur)) {
                            echo "<div class='alert alert-success'>Paiement effectué avec $pointAUtiliser points</div>";
                        }
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

if (isset($_POST['FiltrerLivre'])) {
    $lesLivres = $unControleur->selectLikeLivre($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectLivre();
}

require_once("vue/panier/vue_panier.php");