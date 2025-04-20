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
    <link rel="stylesheet" href="includes/css/index.css">
</head>
<body>
<center>
    <h1> Book'In </h1>
    <div class="relief-box">
        <img src="images/logo.png" height="100" width="100">
        <?php
        if (isset($isAdmin) && $isAdmin == 1) {
            echo "<br>";
            echo "/**************** Mode Admin ****************/";
        }
        ?>
    </div>

    <?php
    if (isset($_POST['Connexion'])) {
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];

        $unUser = $unControleur->verifConnexion($emailUser, $mdpUser);
        if ($unUser) {
            $_SESSION['idUser'] = $unUser['idUser'];
            $_SESSION['emailUser'] = $_POST['emailUser'];
            $_SESSION['mdpUser'] = $_POST['mdpUser'];
            $_SESSION['roleUser'] = $unUser['roleUser'];

        } else {
            echo "<br> Vérifier les identifiants. ";
        }
    }

    if (isset($_POST['InscriptionParticulier'])) {
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];
        $adresseUser = $_POST['adresseUser'];

        $nomUser = $_POST['nomUser'];
        $prenomUser = $_POST['prenomUser'];
        $dateNaissanceUser = $_POST['dateNaissanceUser'];
        $sexeUser = $_POST['sexeUser'];

        var_dump($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser);

        $unControleur->insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser);
    }

    if (isset($_POST['InscriptionEntreprise'])) {
        $emailUser = $_POST['emailUser'];
        $mdpUser = $_POST['mdpUser'];
        $adresseUser = $_POST['adresseUser'];

        $siretUser = $_POST['siretUser'];
        $raisonSocialeUser = $_POST['raisonSocialeUser'];
        $capitalSocialUser = $_POST['capitalSocialUser'];

        $unControleur->insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser);
    }

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
        echo '<a href="index.php?page=7"> <img src="images/promotion.png" height="80" width="80" style="margin-right: 30px"> </a>';
        echo '<a href="index.php?page=8"> <img src="images/stockage.png" height="80" width="80" style="margin-right: 30px"> </a>';
        echo '<a href="index.php?page=9"> <img src="images/statistique.png" height="80" width="80" style="margin-right: 30px"> </a>';
    }

    echo '<a href="index.php?page=10"> <img src="images/authentification.png" height="80" width="80" style="margin-right: 30px"> </a>
    </div>';

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    switch ($page) {
        case 1: require_once("controleur/home.php"); break;
        case 2: require_once("controleur/c_livre.php"); break;
        case 3: require_once("controleur/c_panier.php"); break;
        case 4: require_once("controleur/c_commande.php"); break;
        case 5: require_once("controleur/c_abonnement.php"); break;
        case 6: require_once("controleur/c_user.php"); break;
        case 7: require_once("controleur/c_promotion.php"); break;
        case 8: require_once("controleur/c_stockage.php"); break;
        case 9: require_once("controleur/c_statistique.php"); break;
        case 10:
            if (isset($_SESSION['emailUser']) && $_SESSION['emailUser'] != NULL) {
                echo '<form method="post">';
                echo '<button type="submit" name="ConfirmerDeconnexion" class="btn btn-danger">Confirmer la déconnexion</button>';
                echo '</form>';

                if (isset($_POST['ConfirmerDeconnexion'])) {
                    session_destroy();
                    unset($_SESSION['emailUser']);
                    header("Location: index.php?page=11");
                    exit();
                }
            } else {
                session_destroy();
                unset($_SESSION['emailUser']);
                header("Location: index.php?page=11");
                exit();
            }
            break;
        case 11:
            require_once("controleur/c_authentification.php");
            break;
    }
    ?>
    <br>
    <br>
</center>
</body>
</html>
