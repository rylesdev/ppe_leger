<h2> Votre panier </h2>

<?php
if (isset($_SESSION['roleUser']) && $_SESSION['roleUser']=="client") {
    $idUser = $_SESSION['idUser'];

    require_once("vue/vue_panier.php");
    if (isset($_GET['action']) && isset($_GET['idCommande']) ){
        $idCommande = $_GET['idCommande'];
        $action = $_GET['action'];

        switch($action) {
            case "sup" :    $unControleur->deleteLigneCommande($idCommande);
                            $unControleur->deleteCommande($idCommande);
                            break;

            case "edit" :   if (isset($_POST['updateQuantiteLivre']) && $_POST['updateQuantiteLivre'] > 0) {
                                $quantiteLigneCommande = $_POST['updateQuantiteLivre'];
                                $unControleur->updateLigneCommande($quantiteLigneCommande, $idCommande);
                                echo "<h3 style='color: green;'>Livre modifié avec succès !</h3>";
                            } elseif (isset($_POST['updateQuantiteLivre']) && $_POST['updateQuantiteLivre'] == null) {
                                $quantiteLigneCommande = 1;
                                $unControleur->updateLigneCommande($quantiteLigneCommande, $idCommande);
                                echo "<h3 style='color: green;'>Livre modifié avec succès !</h3>";
                            }
                            break;
        }
    }

    if (isset($_POST['Filtrer'])) {
        $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
    } else {
        $lesLivres = $unControleur->selectAllLivres($idUser);
    }

} else {
    echo "Page indisponible pour le rôle admin.";
}
?>