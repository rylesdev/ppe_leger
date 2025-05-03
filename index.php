<?php
session_start();
error_reporting(0);
require_once("controleur/controleur.class.php");

$unControleur = new Controleur();

$resultAdminPrincipal = $unControleur->selectAdminPrincipal($_SESSION['idUser']);
$isAdmin = $resultAdminPrincipal[0][0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>PPE Book'In</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="includes/css/style.css">
</head>
<body>
<center>
    <h1>Book'In</h1>
    <div class="relief-box">
        <img src="images/logo.png" height="100" width="100">
        <?php
        if (isset($isAdmin) && $isAdmin == 1) {
            echo "<br>";
            echo "<!-- Mode Admin -->";
        }
        ?>
    </div>

    <div id="navbar" class="navbar">
        <a href="index.php?page=1"><img src="images/logo.png" height="80" width="80" alt="Accueil"></a>
        <a href="index.php?page=2"><img src="images/rechercher.png" height="80" width="80" alt="Livres"></a>
        <?php if (empty($isAdmin) || $isAdmin == 0): ?>
            <a href="index.php?page=3"><img src="images/panier.png" height="80" width="80" alt="Panier"></a>
            <a href="index.php?page=4"><img src="images/commande.png" height="80" width="80" alt="Commande"></a>
            <a href="index.php?page=5"><img src="images/abonnement.png" height="80" width="80" alt="Abonnement"></a>
            <a href="index.php?page=6"><img src="images/utilisateur.png" height="80" width="80" alt="Utilisateur"></a>
        <?php endif; ?>
        <?php if (isset($isAdmin) && $isAdmin == 1): ?>
            <a href="index.php?page=7"><img src="images/promotion.png" height="80" width="80" alt="Promotion"></a>
            <a href="index.php?page=8"><img src="images/stockage.png" height="80" width="80" alt="Stockage"></a>
            <a href="index.php?page=9"><img src="images/statistique.png" height="80" width="80" alt="Statistique"></a>
        <?php endif; ?>
        <a href="index.php?page=10"><img src="images/authentification.png" height="80" width="80" alt="Authentification"></a>
    </div>

    <?php
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
    <br><br>
</center>
</body>
</html>
