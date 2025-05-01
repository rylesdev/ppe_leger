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

    $result = $unControleur->procedureInsertOrUpdateLigneCommande($idCommande, $idLivre, $quantiteLivre);
    $nomLivre = $unControleur->selectLivreById($idLivre)[0][1];

    if ($result == 1) {
        echo "<div class='alert alert-success'>Article '" . $nomLivre . "' (x$quantiteLivre) ajouté à la commande " . $idCommande . "</div>";
    } else if ($result == 2) {
        echo "<div class='alert alert-success'>Nouvelle commande crée pour l'article '" . $nomLivre . "' (x$quantiteLivre)</div>";
    } else if ($result == 0) {
        echo "<div class='alert alert-danger'> Erreur : La quantité totale commandée pour '" . $nomLivre . "' est supérieure au stock disponible.<br>
             Veuillez sélectionner, dans la page Panier, une quantité d'articles inférieure ou égale au nombre d'exemplaires en stock pour cet article.</div>";
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

if (isset($_POST['ValiderInsert'])) {
    $nomLivre = $_POST['nomLivre'];
    $auteurLivre = $_POST['auteurLivre'];
    $imageLivre = $_POST['imageLivre'];
    $exemplaireLivre = $_POST['exemplaireLivre'];
    $prixLivre = $_POST['prixLivre'];
    $nomCategorie = $_POST['nomCategorie'];
    $nomMaisonEdition = $_POST['nomMaisonEdition'];
    $nomPromotion = $_POST['nomPromotion'];

    $resultInsertLivre = $unControleur->procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition, $nomPromotion);
    var_dump($resultInsertLivre);
    if ($resultInsertLivre) {
        echo "<div class='alert alert-success'>Insertion réussie du livre.</div>";
    } else if ($resultInsertLivre == false){
        echo "<div class='alert alert-danger'>Erreur : Impossible d'insérer le livre</div>";
    }
}

if (isset($isAdmin) && $isAdmin == 1) {
    require_once("vue/livre/vue_insert_livre.php");
} else {
    echo "";
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