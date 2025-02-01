<h2> Stockage des livres </h2>

<?php
if (isset($isAdmin) && $isAdmin == 1) {
    $idUser = $_SESSION['idUser'];

    echo "<h3>Modifier le stockage des livres</h3>";
    echo "<br>";

    $leLivre = null;

    require_once("vue/vue_stockage_livre.php");
    if(isset($_POST['ValiderStockage'])){
        $exemplaireLivre = $_POST['exemplaireLivre'];
        $nomLivre = $_POST['nomLivre'];

        if ($unControleur->updateStockageLivre($exemplaireLivre, $nomLivre)) {
            echo "<br> Modification réussie du nombre d'exemplaire. <br>";
        }
    }

if (isset($_POST['Filtrer'])){
    $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectAllLivres ($idUser);
}

require_once("vue/vue_select_livre_stockage.php");
} else {
    echo "<h1>Vous n'avez pas les droits pour rentrer ici :)</h1>";
}
?>