<?php
session_start();
error_reporting(0);
require_once("controleur/controleur.class.php");

$unControleur = new Controleur();

$result = $unControleur->selectAdminPrincipal($_SESSION['idUser']);
$isAdmin = $result[0][0];

?>
<!DOCTYPE html>
<html>
<head>
    <title>PPE Book'In</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<center>
    <h1> Site Internet Librairie </h1>
    <?php
    if (isset($isAdmin) && $isAdmin == 1) {
        echo "/**************** Mode Admin ****************/";
    }

    if (!isset($_SESSION['emailUser'])) {
        require_once("vue/vue_connexion.php");
        require_once("vue/vue_inscription.php");
    }

    if (isset($_POST['Connexion'])) {
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];

        $unUser = $unControleur->verifConnexion($emailUser, $mdpUser);
        if ($unUser) {
            // User mère :
            $_SESSION['idUser'] = $unUser['idUser'];
            $_SESSION['emailUser'] = $_POST['emailUser'];
            $_SESSION['mdpUser'] = $_POST['mdpUser'];
            $_SESSION['roleUser'] = $unUser['roleUser'];


            // Particulier fille :
            $_SESSION['nomUser'] = $unUser['nomUser'];
            $_SESSION['prenomUser'] = $unUser['prenomUser'];
            $_SESSION['adresseUser'] = $unUser['emailUser'];

            $_SESSION['dateNaissanceUser'] = $unUser['dateNaissanceUser'];
            $_SESSION['sexeUser'] = $unUser['sexeUser'];

            // Entreprise fille :
            $_SESSION['siretUser'] = $unUser['siretUser'];
            $_SESSION['raisonSocialeUser'] = $unUser['raisonSocialeUser'];
            $_SESSION['capitalSocialUser'] = $unUser['capitalSocialUser'];

            header("Location: index.php?page=1");
        } else {
            echo "<br> Vérifier les identifiants. ";
        }
    }

    if (isset($_POST['InscriptionParticulier'])) {
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];

        $nomUser = $_POST['nomUser'];
        $prenomUser = $_POST['prenomUser'];
        $adresseUser = $_POST['adresseUser'];
        $dateNaissanceUser = $_POST['dateNaissanceUser'];
        $sexeUser = $_POST['sexeUser'];

        $unControleur->triggerInsertParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser);
    }

    if (isset($_POST['InscriptionEntreprise'])) {
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];

        $siretUser = $_POST['siretUser'];
        $raisonSocialeUser = $_POST['raisonSocialeUser'];
        $capitalSocialUser = $_POST['capitalSocialUser'];

        $unControleur->triggerInsertEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser);
    }


    if (isset($_SESSION['emailUser'])) {

        echo '<div id="navbar" style="background-Color: #f0f0f0">
		<a href="index.php?page=1"> <img src="images/logo.png" height="80" width="80" style="margin-right: 30px"> </a>

		<a href="index.php?page=2"> <img src="images/rechercher.png" height="80" width="80" style="margin-right: 30px"> </a>';

        if (empty($isAdmin) || $isAdmin == 0) {
            echo '<a href="index.php?page=3"> <img src="images/panier.png" height="80" width="80" style="margin-right: 30px"> </a>';
            echo '<a href="index.php?page=4"> <img src="images/commande.png" height="80" width="80" style="margin-right: 30px"> </a>';
            echo '<a href="index.php?page=5"> <img src="images/abonnement.png" height="80" width="80" style="margin-right: 30px"> </a>';
            echo '<a href="index.php?page=6"> <img src="images/utilisateur.png" height="80" width="80" style="margin-right: 30px"> </a>';
        }

        if (isset($isAdmin) && $isAdmin == 1) {
            echo '<a href="index.php?page=7"> <img src="images/stockage.png" height="80" width="80" style="margin-right: 30px"> </a>';
            echo '<a href="index.php?page=8"> <img src="images/statistique.png" height="80" width="80" style="margin-right: 30px"> </a>';
        }

        echo '<a href="index.php?page=9"> <img src="images/deconnexion.png" height="80" width="80" style="margin-right: 30px"> </a>
    </div>';

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        switch ($page) {
            case 1: require_once("controleur/home.php"); break;
            case 2: require_once("controleur/c_livres.php"); break;
            case 3: require_once("controleur/c_panier.php"); break;
            case 4: require_once("controleur/c_commande.php"); break;
            case 5: require_once("controleur/c_abonnement.php"); break;
            case 6: require_once("controleur/c_utilisateur.php"); break;
            case 7: require_once("controleur/c_stockage.php"); break;
            case 8: require_once("controleur/c_statistique.php"); break;
            case 9: session_destroy(); unset($_SESSION['email']);
                header("Location: index.php");
                break;
        }
    }
    ?>
    <br>
    <br>
</center>
</body>
</html>
