<?php
$titrePage = "Informations de l'utilisateur";
require_once("includes/header.php");
?>

<link rel="stylesheet" href="includes/css/vue_user.css">

<div class="container">
    <?php
    if (isset($idUser)) {
        $userParticulier = $unControleur->selectParticulier($idUser);
        $userEntreprise = $unControleur->selectEntreprise($idUser);

        if ($userParticulier) {
            echo "<h3> Particulier </h3>";
            echo '<div class="user-info"><label>Email :</label><span>' . $userParticulier[0][0] . '</span></div>';
            echo '<div class="user-info"><label>Mot de passe :</label><span>' . $userParticulier[0][1] . '</span></div>';
            echo '<div class="user-info"><label>Adresse :</label><span>' . $userParticulier[0][2] . '</span></div>';
            echo '<div class="user-info"><label>Nom :</label><span>' . $userParticulier[0][3] . '</span></div>';
            echo '<div class="user-info"><label>Prénom :</label><span>' . $userParticulier[0][4] . '</span></div>';
            echo '<div class="user-info"><label>Date de naissance :</label><span>' . $userParticulier[0][5] . '</span></div>';
            echo '<div class="user-info"><label>Sexe :</label><span>' . $userParticulier[0][6] . '</span></div>';
        } else if ($userEntreprise) {
            echo "<h3> Entreprise </h3>";
            echo '<div class="user-info"><label>Email :</label><span>' . $userEntreprise[0][0] . '</span></div>';
            echo '<div class="user-info"><label>Mot de passe :</label><span>' . $userEntreprise[0][1] . '</span></div>';
            echo '<div class="user-info"><label>Adresse :</label><span>' . $userEntreprise[0][2] . '</span></div>';
            echo '<div class="user-info"><label>SIRET :</label><span>' . $userEntreprise[0][3] . '</span></div>';
            echo '<div class="user-info"><label>Raison sociale :</label><span>' . $userEntreprise[0][4] . '</span></div>';
            echo '<div class="user-info"><label>Capital social :</label><span>' . $userEntreprise[0][5] . '</span></div>';
        } else {
            echo '<p>Aucun utilisateur sélectionné.</p>';
        }
    }
    ?>
</div>
<br>

<?php
require_once("includes/footer.php");
?>
</body>
</html>
