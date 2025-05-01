<?php
if (isset($isAdmin) && $isAdmin == 1) {
    require_once("controleur/controleur.class.php");
    $unControleur = new Controleur();

    if (isset($_POST['ValiderPromotion'])) {
        $nomLivre = $_POST['nomLivre'];
        $reductionPromotion = $_POST['prixPromotion'];
        $dateFinPromotion = $_POST['dateFinPromotion'];

        $result = $unControleur->procedureInsertOrUpdatePromotion($nomLivre, $reductionPromotion, $dateFinPromotion);

        if ($result == 2) {
            echo "<div class='alert alert-success'>Promotion existante de " . $reductionPromotion . "% mise à jour et appliquée au livre " . $nomLivre . "</div>";
        } else if ($result == 3) {
            echo "<div class='alert alert-success'>Nouvelle promotion de " . $reductionPromotion . "% créée et appliquée au livre " . $nomLivre . "</div>";
        } else if ($result == -1) {
            echo "<div class='alert alert-danger'>Erreur : Le livre spécifié n'existe pas </div>";
        }
    }

    require_once("vue/promotion/vue_promotion.php");
}
?>