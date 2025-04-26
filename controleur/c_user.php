<?php
if (isset($_SESSION['roleUser']) && $_SESSION['roleUser']!="admin") {
    require_once("controleur/controleur.class.php");
    $unControleur = new Controleur();

    $idUser = $_SESSION['idUser'];

    // A SUPPRIMER URGENT
    var_dump($idUser);

    require_once("vue/user/vue_user.php");

    require_once("vue/user/vue_update_user.php");

    require_once("vue/user/vue_delete_user.php");

    if (isset($_POST['UpdateParticulier'])){
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];
        $adresseUser = $_POST['adresseUser'];
        $nomUser = $_POST['nomUser'];
        $prenomUser = $_POST['prenomUser'];
        $dateNaissanceUser = $_POST['dateNaissanceUser'];
        $sexeUser = $_POST['sexeUser'];

        $unControleur->updateParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser, $idUser);
    } else if (isset($_POST['UpdateEntreprise'])){
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];
        $adresseUser = $_POST['adresseUser'];
        $siretUser = $_POST['siretUser'];
        $raisonSocialeUser = $_POST['raisonSocialeUser'];
        $capitalSocialUser = $_POST['capitalSocialUser'];

        $unControleur->updateEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser);
    }

    if (isset($_POST['DeleteUser'])) {
        $userParticulier = $unControleur->selectParticulier($idUser);
        $userEntreprise = $unControleur->selectEntreprise($idUser);

        if ($unControleur->archiverCommandeUtilisateur($idUser)) {

            if ($userParticulier) {
                $unControleur->deleteParticulier($idUser);
            } elseif ($userEntreprise) {
                $unControleur->deleteEntreprise($idUser);
            } else {
                echo "Aucun utilisateur trouvé.";
            }

            session_destroy();
            header("Location: index.php?message=Compte supprimé avec succès");
            exit();
        }
    }

} else if (!isset($_SESSION['emailUser'])) {
    echo "<h3 style='color: red;'>Vous devez être connecté pour accéder à cette page.</h3>";
} else {
    echo "<h3 style='color: red;'>Page indisponible pour le rôle admin.</h3";
}