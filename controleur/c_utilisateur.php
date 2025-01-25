<?php
require_once("controleur/controleur.class.php");
$unControleur = new Controleur();

$idUser = $_SESSION['idUser'];

$user = $unControleur->selectUser($idUser);

if ($user) {
    require_once("vue/vue_select_user.php");
}

require_once ("vue/vue_update_user.php");

if (isset($_POST['UpdateUser'])){
    $nomUser = $_POST['nomUser'];
    $prenomUser = $_POST['prenomUser'];
    $emailUser = $_POST['emailUser'];
    $mdpUser = $_POST['mdpUser'];
    $adresseUser = $_POST['adresseUser'];

    $unControleur->updateUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser, $idUser);
}
?>