<?php
if (!isset($_SESSION['emailUser'])) {
    echo "<h3 style='color: red;'>Vous devez être connecté pour accéder à cette page.</h3>";
} elseif (empty($isAdmin) || $isAdmin == 0) {
    require_once("controleur/controleur.class.php");
    $unControleur = new Controleur();

    $idUser = $_SESSION['idUser'];

    require_once("vue/abonnement/vue_abonnement.php");

        if (isset($_POST['Abonnement1m'])) {
            if (empty($unControleur->selectDateAbonnement($idUser)['jourRestant']) || $unControleur->selectDateAbonnement($idUser)['jourRestant'] < 0) {
                $resultInsertAbonnement1m = $unControleur->insertAbonnement1m($idUser);
                if ($resultInsertAbonnement1m) {
                    echo "<div class='alert alert-success'>Souscription réussie à l'abonnement de 1 mois.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erreur : Impossible de souscrire à l'abonnement de 1 mois</div>";
                }
            } elseif ($unControleur->selectDateAbonnement($idUser)['jourRestant'] >= 0) {
                $resultUpdateAbonnement1m = $unControleur->updateAbonnement1m($idUser);
                if ($resultUpdateAbonnement1m) {
                    echo "<div class='alert alert-success'>Modification réussie de l'abonnement actuel pour l'abonnement de 1 mois.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erreur : Impossible de modifier l'abonnement actuel pour l'abonnement de 1 mois</div>";
                }
            }
        }

        if (isset($_POST['Abonnement3m'])) {
            if (empty($unControleur->selectDateAbonnement($idUser)['jourRestant']) || $unControleur->selectDateAbonnement($idUser)['jourRestant'] < 0) {
                $resultInsertAbonnement3m = $unControleur->insertAbonnement3m($idUser);
                if ($resultInsertAbonnement3m) {
                    echo "<div class='alert alert-success'>Souscription réussie à l'abonnement de 3 mois.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erreur : Impossible de souscrire à l'abonnement de 3 mois</div>";
                }
            } elseif ($unControleur->selectDateAbonnement($idUser)['jourRestant'] >= 0) {
                $resultUpdateAbonnement3m = $unControleur->updateAbonnement3m($idUser);
                if ($resultUpdateAbonnement3m) {
                    echo "<div class='alert alert-success'>Modification réussie de l'abonnement actuel pour l'abonnement de 3 mois.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erreur : Impossible de modifier l'abonnement actuel pour l'abonnement de 3 mois</div>";
                }
            }
        }

        if (isset($_POST['Abonnement1a'])) {
            if (empty($unControleur->selectDateAbonnement($idUser)['jourRestant']) || $unControleur->selectDateAbonnement($idUser)['jourRestant'] < 0) {
                $resultInsertAbonnement1a = $unControleur->insertAbonnement1a($idUser);
                if ($resultInsertAbonnement1a) {
                    echo "<div class='alert alert-success'>Souscription réussie à l'abonnement de 1 an.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erreur : Impossible de souscrire à l'abonnement de 1 an</div>";
                }
            } elseif ($unControleur->selectDateAbonnement($idUser)['jourRestant'] >= 0) {
                $resultUpdateAbonnement1a = $unControleur->updateAbonnement1a($idUser);
                if ($resultUpdateAbonnement1a) {
                    echo "<div class='alert alert-success'>Modification réussie de l'abonnement actuel pour l'abonnement de 1 an.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Erreur : Impossible de modifier l'abonnement actuel pour l'abonnement de 1 an</div>";
                }
            }
        }


        $dateAbonnement = $unControleur->selectDateAbonnement($idUser);
        if ($dateAbonnement == null || $dateAbonnement == 0) {
            echo "<h3> Vous n'avez pas d'abonnement en cours. </h3>";
        } elseif ($dateAbonnement !== null ) {
            echo "<h3> Votre abonnement prend fin dans " . $dateAbonnement['jourRestant'] . " jours. </h3>";
        }
} else {
    echo "<h3 style='color: red;'>Page indisponible pour le rôle admin.</h3>";
}