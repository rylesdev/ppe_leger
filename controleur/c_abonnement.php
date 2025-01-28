<?php
require_once("controleur/controleur.class.php");
$unControleur = new Controleur();

$idUser = $_SESSION['idUser'];

require_once("vue/vue_abonnement.php");

    if (isset($_POST['Abonnement1m'])) {
        $unControleur->insertAbonnement1m($idUser);
    }

    if (isset($_POST['Abonnement3m'])) {
        $unControleur->insertAbonnement3m($idUser);
    }

    if (isset($_POST['Abonnement1a'])) {
        $unControleur->insertAbonnement1a($idUser);
    }


    $dateAbonnement = $unControleur->selectDateAbonnement($idUser);
    if ($dateAbonnement == null || $dateAbonnement == 0) {
        echo "<h3> Vous n'avez pas d'abonnement en cours. </h3>";
    } elseif ($dateAbonnement !== null ) {
        echo "<h3> Votre abonnement prend fin dans " . $dateAbonnement['jourRestant'] . " jours. </h3>";
    }
?>