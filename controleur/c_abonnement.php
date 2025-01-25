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
?>