<h2> Votre Panier </h2>

<?php
if (isset($_SESSION['roleUser']) && $_SESSION['roleUser']=="client") {
    $idUser = $_SESSION['idUser'];

    require_once("vue/vue_panier.php");
    if (isset($_GET['action'])){
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

            case "payer" :  $idCommande = $unControleur->selectCommandeEnCours($idUser);
                            if ($idCommande) {
                                $unControleur->updateCommande($idCommande);
                                echo "<h3 style='color: green;'>Commande mise à jour avec succès. Redirection vers PayPal...</h3>";

                                $chiffre = rand(5, 5);
                                var_dump($chiffre);
                                $unControleur->procedureInsertLivre($idUser, $chiffre);

                                header("Location: https://paypal.me/rylesatm?country.x=FR&locale.x=fr_FR");
                                exit();
                            } else {
                                echo "<h3 style='color: red;'>Erreur : Aucune commande en cours trouvée.</h3>";
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