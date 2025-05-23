<?php
error_reporting(0);

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
        echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Article '" . $nomLivre . "' (x$quantiteLivre) ajouté à la commande " . $idCommande . "</div>";
    } else if ($result == 2) {
        echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Nouvelle commande créée pour l'article '" . $nomLivre . "' (x$quantiteLivre)</div>";
    } else if ($result == 0) {
        echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'> Erreur : La quantité totale commandée pour '" . $nomLivre . "' est supérieure au stock disponible.<br>
             Veuillez sélectionner, dans la page Panier, une quantité d'articles inférieure ou égale au nombre d'exemplaires en stock pour cet article.</div>";
    }
}

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    $idLivre = $_REQUEST['idLivre'] ?? null;

    switch ($action) {
        case "sup":
            $result = $unControleur->deleteLivre($idLivre);
            if ($result) {
                echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Suppression réussie du livre.</div>";
            } else {
                echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de supprimer le livre</div>";
            }
            break;

        case "edit":
            $leLivre = $unControleur->selectWhereLivre($idLivre);
            break;
    }
}

if (isset($_POST['InsertLivre'])) {
    $nomLivre = $_POST['nomLivre'];
    $auteurLivre = $_POST['auteurLivre'];
    $imageLivre = $_POST['imageLivre'];
    $exemplaireLivre = $_POST['exemplaireLivre'];
    $prixLivre = $_POST['prixLivre'];
    $nomCategorie = $_POST['nomCategorie'];
    $nomMaisonEdition = $_POST['nomMaisonEdition'];
    $nomPromotion = $_POST['nomPromotion'];

    $resultInsertLivre = $unControleur->procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition, $nomPromotion);
    if ($resultInsertLivre) {
        echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Insertion réussie du livre.</div>";
    } else {
        echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible d'insérer le livre</div>";
    }
}

if (isset($_POST['UpdateLivre'])) {
    $nomLivre = $_POST['nomLivre'];
    $auteurLivre = $_POST['auteurLivre'];
    $imageLivre = $_POST['imageLivre'];
    $prixLivre = $_POST['prixLivre'];
    $nomCategorie = $_POST['nomCategorie'];
    $nomMaisonEdition = $_POST['nomMaisonEdition'];
    $nomPromotion = $_POST['nomPromotion'];
    $idCategorie = $unControleur->selectIdCategorieByNom($nomCategorie)[0];
    $idMaisonEdition = $unControleur->selectIdMaisonEditionByNom($nomMaisonEdition)[0];
    $idPromotion = $unControleur->selectIdPromotionByNom($nomPromotion)[0];

    $result = $unControleur->updateLivre($nomLivre, $auteurLivre, $imageLivre, $prixLivre, $idCategorie, $idMaisonEdition, $idPromotion, $idLivre);
    if ($result) {
        echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Mise à jour réussie du livre.</div>";
    } else {
        echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de mettre à jour le livre</div>";
    }
}

if (isset($_POST['FiltrerLivre'])) {
    $lesLivres = $unControleur->selectLikeLivre($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectLivre();
}

echo '<div class="container mx-auto px-4 py-6">';

if (isset($isAdmin) && $isAdmin == 1) {
    require_once("vue/livre/vue_insert_livre.php");
    echo '<div class="my-8 border-t-2 border-gray-200"></div>';
}

require_once("vue/livre/vue_livre.php");

echo '</div>';