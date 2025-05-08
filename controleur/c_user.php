<?php
if (!isset($_SESSION['emailUser'])) {
    echo "<h3 style='color: red;'>Vous devez être connecté pour accéder à cette page.</h3>";
} elseif (empty($isAdmin) || $isAdmin == 0) {
    require_once("controleur/controleur.class.php");
    $unControleur = new Controleur();

    $idUser = $_SESSION['idUser'];

    // A SUPPRIMER
    var_dump($idUser);

    require_once("vue/user/vue_user.php");

    if (isset($_POST['UpdateParticulier'])){
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];
        $adresseUser = $_POST['adresseUser'];
        $nomUser = $_POST['nomUser'];
        $prenomUser = $_POST['prenomUser'];
        $dateNaissanceUser = $_POST['dateNaissanceUser'];
        $sexeUser = $_POST['sexeUser'];

        $resultParticulier = $unControleur->updateParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser, $idUser);
        if ($resultParticulier) {
            echo "<div class='alert alert-success'>Mise à jour réussie du particulier.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : Impossible de mettre à jour le particulier</div>";
        }
    } else if (isset($_POST['UpdateEntreprise'])){
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];
        $adresseUser = $_POST['adresseUser'];
        $siretUser = $_POST['siretUser'];
        $raisonSocialeUser = $_POST['raisonSocialeUser'];
        $capitalSocialUser = $_POST['capitalSocialUser'];

        $resultEntreprise = $unControleur->updateEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser);
        if ($resultEntreprise) {
            echo "<div class='alert alert-success'>Mise à jour réussie de l'entreprise.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : Impossible de mettre à jour l'entreprise</div>";
        }
    }

    if (isset($_POST['DeleteUser'])) {
        $userParticulier = $unControleur->selectParticulier($idUser);
        $userEntreprise = $unControleur->selectEntreprise($idUser);

        if ($userParticulier) {
            $result = $unControleur->deleteParticulier($idUser);
            if ($result) {
                echo "<div class='alert alert-success'>Suppression réussie du particulier.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur : Impossible de supprimer le particulier</div>";
            }
        } elseif ($userEntreprise) {
            $result = $unControleur->deleteEntreprise($idUser);
            if ($result) {
                echo "<div class='alert alert-success'>Suppression réussie de l'entreprise.</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur : Impossible de supprimer l'entreprise</div>";
            }
        } else {
            echo "Aucun utilisateur trouvé.";
        }

        session_destroy();
        header("Location: index.php?message=Compte supprimé avec succès");
        exit();
    }
} else {
    echo "<h3 style='color: red;'>Page indisponible pour le rôle admin.</h3>";
}