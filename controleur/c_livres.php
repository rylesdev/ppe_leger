<?php
$modeEdition = false;
if (isset($_GET['action']) && $_GET['action'] == "edit" && isset($_GET['idLivre'])) {
    $idLivre = $_GET['idLivre'];
    $leLivre = $unControleur->selectWhereLivre($idLivre);
    $modeEdition = true;
} else {
    $leLivre = null;
}

$idUser = $_SESSION['idUser'];

if (!isset($_SESSION['commandeEnCours'])) {
    $_SESSION['commandeEnCours'] = 0;
    $commandeEnCours = $_SESSION['commandeEnCours'];
}

if (isset($_GET['action']) && isset($_GET['idLivre'])) {
    $action  = $_GET['action'];
    $idLivre = $_GET['idLivre'];

    if (isset($_POST['QuantiteLivre'])) {
        $_SESSION['quantiteLivre'] = $_POST['insertQuantiteLivre'];
        $quantiteLivre = $_SESSION['quantiteLivre'];
    } else {
        $quantiteLivre = isset($_SESSION['quantiteLivre']) ? $_SESSION['quantiteLivre'] : 0;
    }

    switch ($action) {
        case "sup":
                        $unControleur->deleteLivre($idLivre);
                        break;

        case "edit":
                        $leLivre = $unControleur->selectWhereLivre($idLivre);
                        break;

        case "acheter":
            if (isset($idLivre) && isset($quantiteLivre) && $quantiteLivre > 0) {
                $idCommande = $unControleur->selectCommandeEnCours($idUser);

                if (!$idCommande) {
                    $idCommande = $unControleur->insertCommande($idUser);
                    if ($idCommande) {
                        echo "<h3 style='color: green;'>Nouvelle commande créée.</h3>";
                    } else {
                        echo "<h3 style='color: red;'>Erreur : Impossible de créer une nouvelle commande.</h3>";
                        break;
                    }
                }

                $result = $unControleur->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);

                if ($result) {
                    echo "<h3 style='color: green;'>Livre ajouté à la commande avec succès.</h3>";
                }
            }
            break;
    }
}

if (isset($isAdmin) && $isAdmin == 1) {
    require_once("vue/vue_insert_livre.php");
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

if (isset($_POST['Filtrer'])) {
    $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
} else {
    $lesLivres = $unControleur->selectAllLivres();
}

require_once("vue/vue_select_livre.php");
?>