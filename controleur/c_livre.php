<?php
$idUser = $_SESSION['idUser'];

if (isset($_POST['action']) && $_POST['action'] == 'AjouterPanier') {
    $idLivre = (int)$_POST['idLivre'];
    $quantiteLivre = (int)$_POST['quantiteLivre'];
    $idUser = (int)$_POST['idUser'];

    $idCommande = $unControleur->selectCommandeEnAttente($idUser)[0][0];

    if (!$idCommande || ($unControleur->selectCommandeExpediee($idUser)[0][0] && !$unControleur->selectCommandeEnAttente($idUser)[0][0])) {
        $idCommande = $unControleur->insertCommande($idUser);
    }

    $result = $unControleur->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);

    if ($result) {
        echo "<div class='alert alert-success'>Article ajouté au panier (x$quantiteLivre)</div>";
    }
}

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    $idLivre = $_REQUEST['idLivre'] ?? null;

    switch ($action) {
        case "sup":
            $unControleur->deleteLivre($idLivre);
            break;

        case "edit":
            $leLivre = $unControleur->selectWhereLivre($idLivre);
            break;
    }
}

if (isset($isAdmin) && $isAdmin == 1) {
    require_once("vue/livre/vue_insert_livre.php");
} else {
    echo "";
}

if (isset($_POST['ValiderInsert'])) {
    $nomLivre = $_POST['nomLivre'];
    $nomCategorie = $_POST['nomCategorie'];
    $auteurLivre = $_POST['auteurLivre'];
    $imageLivre = $_POST['imageLivre'];
    $nomMaisonEdition = $_POST['nomMaisonEdition'];
    $exemplaireLivre = $_POST['exemplaireLivre'];
    $prixLivre = $_POST['prixLivre'];

    if ($unControleur->procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition)){
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

if (isset($_POST['FiltrerLivre'])) {
    $lesLivres = $unControleur->selectLikeLivre($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectLivre();
}

require_once("vue/livre/vue_livre.php");