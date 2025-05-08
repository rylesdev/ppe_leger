<?php
if (isset($isAdmin) && $isAdmin == 1) {
    $idUser = $_SESSION['idUser'];

    $leLivre = null;

    require_once("vue/stockage/vue_stockage.php");

    if(isset($_POST['ValiderStockage'])){
        $exemplaireLivre = $_POST['exemplaireLivre'];
        $nomLivre = $_POST['nomLivre'];

        $result = $unControleur->updateStockageLivre($exemplaireLivre, $nomLivre);
        if ($result) {
            echo "<div class='alert alert-success'>Mise à jour réussie du stockage du livre.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : Impossible de mettre à jour le stockage du livre</div>";
        }
    }

if (isset($_POST['FiltrerStockage'])){
    $lesLivres = $unControleur->selectLikeLivre($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectLivre();
}

require_once("vue/stockage/vue_livre_stockage.php");
}
?>