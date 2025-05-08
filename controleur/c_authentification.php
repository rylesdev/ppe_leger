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

        // Utiliser une redirection JavaScript au lieu de header()
        echo "<script>window.location.href = 'index.php?page=1';</script>";
        // Ne pas mettre de exit() ici, car nous voulons que le script JavaScript s'exécute
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

    $resultEmail = $unControleur->selectEmail($emailUser)[0][0];

    if (empty($resultEmail)) {
        $resultParticulier = $unControleur->insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser);
        if ($resultParticulier) {
            echo "<div class='alert alert-success'>Inscription de $prenomUser $nomUser réussie !</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de l'inscription de $prenomUser $nomUser !</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>L'email $emailUser est déjà utilisé !</div>";
    }
}

if (isset($_POST['InscriptionEntreprise'])) {
    $emailUser = $_POST['emailUser'];
    $mdpUser = $_POST['mdpUser'];
    $adresseUser = $_POST['adresseUser'];

    $siretUser = $_POST['siretUser'];
    $raisonSocialeUser = $_POST['raisonSocialeUser'];
    $capitalSocialUser = $_POST['capitalSocialUser'];

    $resultEmail = $unControleur->selectEmail($emailUser)[0][0];

    if (empty($resultEmail)) {
        $resultEntreprise = $unControleur->insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser);
        if ($resultEntreprise) {
            echo "<div class='alert alert-success'>Inscription de $raisonSocialeUser réussie !</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de l'inscription de $raisonSocialeUser !</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>L'email $emailUser est déjà utilisé !</div>";
    }
}

?>

<div class="auth-main-container">
    <div class="auth-container">
        <div class="auth-section">
            <h2 class="auth-title">Inscription</h2>

            <?php
            echo '<div class="inscription-options">';
            echo '<a href="index.php?page=11&inscription=particulier"><button class="auth-btn auth-btn-primary">Particulier</button></a>';
            echo '<a href="index.php?page=11&inscription=entreprise"><button class="auth-btn auth-btn-primary">Entreprise</button></a>';
            echo '</div>';

        $inscription = isset($_GET['inscription']) ? $_GET['inscription'] : '';

        switch ($inscription) {
            case "particulier":
                require_once("vue/auth/vue_inscription_particulier.php");
                break;
            case "entreprise":
                require_once("vue/auth/vue_inscription_entreprise.php");
                break;
        }
        ?>
        </div>

    <br>
        <div class="auth-section">
            <h2 class="auth-title">Connexion</h2>
            <?php
            require_once("vue/auth/vue_connexion.php");
            ?>
        </div>
    </div>
</div>