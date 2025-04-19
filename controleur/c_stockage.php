<?php
if (isset($isAdmin) && $isAdmin == 1) {
    $idUser = $_SESSION['idUser'];

    $leLivre = null;

    require_once("vue/stockage/vue_stockage.php");
    if(isset($_POST['ValiderStockage'])){
        $exemplaireLivre = $_POST['exemplaireLivre'];
        $nomLivre = $_POST['nomLivre'];

        if ($unControleur->updateStockageLivre($exemplaireLivre, $nomLivre)) {
            echo "<br> Modification réussie du nombre d'exemplaire. <br>";
        }
    }

if (isset($_POST['FiltrerStockage'])){
    $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectAllLivres ($idUser);
}

require_once("vue/stockage/vue_select_livre_stockage.php");
}
?>