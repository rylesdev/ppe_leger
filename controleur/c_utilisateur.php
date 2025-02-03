<?php
require_once("controleur/controleur.class.php");
$unControleur = new Controleur();

$idUser = $_SESSION['idUser'];

require_once("vue/vue_utilisateur.php");

require_once ("vue/vue_update_user.php");

if (isset($_POST['UpdateParticulier'])){
    $emailUser = $_POST['emailUser'];
    $mdpUser = $_POST['mdpUser'];
    $nomUser = $_POST['nomUser'];
    $prenomUser = $_POST['prenomUser'];
    $adresseUser = $_POST['adresseUser'];
    $dateNaissanceUser = $_POST['dateNaissanceUser'];
    $sexeUser = $_POST['sexeUser'];

    $unControleur->updateParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser, $idUser);
}

if (isset($_POST['UpdateEntreprise'])){
    $emailUser = $_POST['emailUser'];
    $mdpUser = $_POST['mdpUser'];
    $siretUser = $_POST['siretUser'];
    $raisonSocialeUser = $_POST['raisonSocialeUser'];
    $capitalSocialUser = $_POST['capitalSocialUser'];

    $unControleur->updateEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser);
}
?>