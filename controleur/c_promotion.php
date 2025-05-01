<?php
if (isset($isAdmin) && $isAdmin == 1) {
    require_once("controleur/controleur.class.php");
    $unControleur = new Controleur();

    require_once("vue/promotion/vue_promotion.php");

    if (isset($_POST['ValiderPromotion'])) {
        $nomLivre = $_POST['nomLivre'];
        $reductionPromotion = $_POST['prixPromotion'];
        $dateFinPromotion = $_POST['dateFinPromotion'];

        $unControleur->procedureInsertOrUpdatePromotion($nomLivre, $reductionPromotion, $dateFinPromotion);
    }
}
?>