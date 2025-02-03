<header style="background-color: #2E6E49; color: white; text-align: center; padding: 15px; font-size: 24px; font-weight: bold;">
Informations de l'utilisateur
</header>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations de l'utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .user-info {
            margin-bottom: 20px;
        }
        .user-info label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .user-info span {
            display: block;
            margin-bottom: 10px;
        }

        .table-success {
            background-color: #2E6E49 !important;
            color: white !important;
        }

        .footer {
            background-color: #2c6e49;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px;
        }

        .footer-banner-img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>

<body>
<div class="container">
    <?php
    if (isset($idUser)) {
        $userParticulier = $unControleur->selectParticulier($idUser);
        $userEntreprise = $unControleur->selectEntreprise($idUser);

        if ($userParticulier) {
            echo "<h3> Particulier </h3>";
            echo '<div class="user-info"><label>Email :</label><span>' . $userParticulier[0][1] . '</span></div>';
            echo '<div class="user-info"><label>Mot de passe :</label><span>' . $userParticulier[0][2] . '</span></div>';
            echo '<div class="user-info"><label>Nom :</label><span>' . $userParticulier[0][3] . '</span></div>';
            echo '<div class="user-info"><label>Prénom :</label><span>' . $userParticulier[0][4] . '</span></div>';
            echo '<div class="user-info"><label>Adresse :</label><span>' . $userParticulier[0][5] . '</span></div>';
            echo '<div class="user-info"><label>Date de naissance :</label><span>' . $userParticulier[0][6] . '</span></div>';
            echo '<div class="user-info"><label>Sexe :</label><span>' . $userParticulier[0][7] . '</span></div>';
        } else if ($userEntreprise) {
            echo "<h3> Entreprise </h3>";
            echo '<div class="user-info"><label>Email :</label><span>' . $userEntreprise[0][1] . '</span></div>';
            echo '<div class="user-info"><label>Mot de passe :</label><span>' . $userEntreprise[0][2] . '</span></div>';
            echo '<div class="user-info"><label>SIRET :</label><span>' . $userEntreprise[0][3] . '</span></div>';
            echo '<div class="user-info"><label>Raison sociale :</label><span>' . $userEntreprise[0][4] . '</span></div>';
            echo '<div class="user-info"><label>Capital social :</label><span>' . $userEntreprise[0][5] . '</span></div>';
        } else {
            echo '<p>Aucun utilisateur sélectionné.</p>';
        }
    }
    ?>
</div>
<footer class="footer">
    <p>&copy; 2025 Librairie en ligne - Tous droits réservés</p>
</footer>
</body>
</html>
