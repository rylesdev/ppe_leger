<?php
require_once("controleur/controleur.class.php");
$unControleur = new Controleur();

$idUser = $_SESSION['idUser'];

require_once("vue/abonnement/vue_abonnement.php");

    if (isset($_POST['Abonnement1m'])) {
        if (empty($unControleur->selectDateAbonnement($idUser)['jourRestant']) || $unControleur->selectDateAbonnement($idUser)['jourRestant'] < 0) {
            $unControleur->insertAbonnement1m($idUser);
            echo "Vous venez de souscrire à l'abonnement de 1 mois.";
        } elseif ($unControleur->selectDateAbonnement($idUser)['jourRestant'] >= 0) {
            $unControleur->updateAbonnement1m($idUser);
            echo "Vous venez de modifier votre abonnement actuel pour l'abonnement de 1 mois.";
        }
    }

    if (isset($_POST['Abonnement3m'])) {
        if (empty($unControleur->selectDateAbonnement($idUser)['jourRestant']) || $unControleur->selectDateAbonnement($idUser)['jourRestant'] < 0) {
            $unControleur->insertAbonnement3m($idUser);
            echo "Vous venez de souscrire à l'abonnement de 1 mois.";
        } elseif ($unControleur->selectDateAbonnement($idUser)['jourRestant'] >= 0) {
            $unControleur->updateAbonnement3m($idUser);
            echo "Vous venez de modifier votre abonnement actuel pour l'abonnement de 3 mois.";
        }
    }

    if (isset($_POST['Abonnement1a'])) {
        if (empty($unControleur->selectDateAbonnement($idUser)['jourRestant']) || $unControleur->selectDateAbonnement($idUser)['jourRestant'] < 0) {
            $unControleur->insertAbonnement1a($idUser);
            echo "Vous venez de souscrire à l'abonnement de 1 mois.";
        } elseif ($unControleur->selectDateAbonnement($idUser)['jourRestant'] >= 0) {
            $unControleur->updateAbonnement1a($idUser);
            echo "Vous venez de modifier votre abonnement actuel pour l'abonnement de 1 an.";
        }
    }


    $dateAbonnement = $unControleur->selectDateAbonnement($idUser);
    if ($dateAbonnement == null || $dateAbonnement == 0) {
        echo "<h3> Vous n'avez pas d'abonnement en cours. </h3>";
    } elseif ($dateAbonnement !== null ) {
        echo "<h3> Votre abonnement prend fin dans " . $dateAbonnement['jourRestant'] . " jours. </h3>";
    }
?>