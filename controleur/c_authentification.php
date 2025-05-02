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
        header ("Location :index.php?page=1");
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

    $unControleur->insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser);
    echo "<div class='alert alert-success'>Inscription de $prenomUser $nomUser réussie !</div>";
}

if (isset($_POST['InscriptionEntreprise'])) {
    $emailUser = $_POST['emailUser'];
    $mdpUser = $_POST['mdpUser'];
    $adresseUser = $_POST['adresseUser'];

    $siretUser = $_POST['siretUser'];
    $raisonSocialeUser = $_POST['raisonSocialeUser'];
    $capitalSocialUser = $_POST['capitalSocialUser'];

    $unControleur->insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser);
    echo "<div class='alert alert-success'>Inscription de $raisonSocialeUser réussie !</div>";
}

?>

<div class="auth-container">
    <div class="auth-section">
        <h2>Inscription</h2>

        <?php
        echo '<div class="inscription-options">';
        echo '<a href="index.php?page=11&inscription=particulier"><button class="btn btn-primary">Vous êtes un particulier</button></a>';
        echo '<a href="index.php?page=11&inscription=entreprise"><button class="btn btn-primary">Vous êtes une entreprise</button></a>';
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

    <div class="auth-section">
        <h2>Connexion</h2>

        <?php
        require_once("vue/auth/vue_connexion.php");
        ?>
    </div>
</div>

<style>
    .auth-container {
        display: flex;
        justify-content: space-around;
        margin: 20px;
    }
    .auth-section {
        width: 45%;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    .inscription-options {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .btn-primary {
        width: 48%;
    }
    .required {
        color: red;
    }
    .required-hint {
        text-align: right;
        font-size: 0.8em;
        color: #666;
    }
</style>
