<h2> Acheter un livre </h2>

<?php
$leLivre = null;

if (isset($_GET['action']) && isset($_GET['idLivre']) ){
    $action  = $_GET['action'];
    $idLivre = $_GET['idLivre'];
    $idUser = $_SESSION['idUser'];
    if (isset($_POST['QuantiteLivre'])) {
        $quantiteLivre = $_POST['insertQuantiteLivre'];
    }

    switch($action){
        case "sup"  :       $unControleur->deleteLivre($idLivre);
                            break;

        case "edit" :       $leLivre = $unControleur->selectWhereLivre($idLivre);
                            break;

        case "acheter" :    if (isset($quantiteLivre) && $quantiteLivre > 0) {
                                $idCommande = $unControleur->insertCommande($idUser);
                                $unControleur->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);
                            } elseif (isset($quantiteLivre) && $quantiteLivre == null) {
                                $quantiteLivre = 1;
                                $idCommande = $unControleur->insertCommande($idUser);
                                $unControleur->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);
                            }
    }
}

if (isset($_SESSION['roleUser']) && $_SESSION['roleUser'] == "admin") {
    echo "<h3>Ajout d'un livre</h3>";
    echo "<br>";

    require_once("vue/vue_insert_livre.php");
} else {
    echo "";
}

if (isset($_POST['ValiderInsert'])) {
    $nomLivre = $_POST['nomLivre'];
    $categorieLivre = $_POST['categorieLivre'];
    $auteurLivre = $_POST['auteurLivre'];
    $imageLivre = $_POST['imageLivre'];
    $prixLivre = $_POST['prixLivre'];

    if ($unControleur->insertLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $prixLivre)) {
        echo "<br> Insertion réussie du livre <br>";
    }
}

if (isset($_POST['Modifier'])) {
    $nomLivre = $_POST['nomLivre'];
    $categorieLivre = $_POST['categorieLivre'];
    $auteurLivre = $_POST['auteurLivre'];
    $imageLivre = $_POST['imageLivre'];
    $idLivre = $_POST['idLivre'];
    $prixLivre = $_POST['prixLivre'];

    $unControleur->updateLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $idLivre, $prixLivre);
    header("Location: index.php?page=2");
}

if (isset($_POST['Filtrer'])) {
    $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectAllLivres();
}

require_once("vue/vue_select_livre.php");
?>
