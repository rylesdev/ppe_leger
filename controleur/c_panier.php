<h2> Votre Panier </h2>

<?php
if (isset($_SESSION['roleUser']) && $_SESSION['roleUser']=="client") {
    $idUser = $_SESSION['idUser'];

    require_once("vue/vue_panier.php");
    if (isset($_GET['action'])){
        $idCommande = $_GET['idCommande'];
        $idLigneCommande = $_GET['idLigneCommande'];
        $action = $_GET['action'];

        switch($action) {
            case "sup" :    $unControleur->deleteLigneCommande($idLigneCommande);
                //$unControleur->deleteCommande($idLigneCommande);
                break;

            case "edit" :   if (isset($_POST['updateQuantiteLivre']) && $_POST['updateQuantiteLivre'] > 0) {
                $quantiteLigneCommande = $_POST['updateQuantiteLivre'];
                $unControleur->updateLigneCommande($quantiteLigneCommande, $idLigneCommande);
                echo "<h3 style='color: green;'>Livre modifié avec succès !</h3>";
            } elseif (isset($_POST['updateQuantiteLivre']) && $_POST['updateQuantiteLivre'] == null) {
                $quantiteLigneCommande = 1;
                $unControleur->updateLigneCommande($quantiteLigneCommande, $idLigneCommande);
                echo "<h3 style='color: green;'>Livre modifié avec succès !</h3>";
            }
                break;

            case "payer" :  if(isset($_POST['PayerPoint'])) {
                $pointAbonnement = $_SESSION['pointAUtiliser'];
                if ($unControleur->selectPointAbonnement($idUser)['pointAbonnement'] >= $pointAbonnement) {
                    $unControleur->enleverPointAbonnement($pointAbonnement, $idUser);
                } else if (empty($pointAbonnement) || $pointAbonnement < 0) {
                    echo "";
                }
            }

                $idCommande = $unControleur->selectCommandeEnCours($idUser);
                if ($idCommande) {
                    $unControleur->updateCommande($idCommande);
                    echo "<h3 style='color: green;'>Commande mise à jour avec succès. Redirection vers PayPal...</h3>";

                    if ($unControleur->selectDateAbonnement($idUser) > 0) {
                        $resultat = $unControleur->selectNbLigneCommande($idCommande);
                        $nombreLigneCommande = $resultat['nombreLigneCommande'];
                        $pointAbonnement = $nombreLigneCommande * 10;
                        $unControleur->ajouterPointAbonnement($pointAbonnement, $idUser);
                    }

                    $chiffre = rand(1, 5);
                    try{
                        $unControleur->procedureOffrirLivre($idUser, $chiffre);
                    } catch (PDOException $e) {
                        echo "<h3 style='color: red;'>" . ($e->getMessage()) . "</h3>";
                    }

                    header("Location: https://paypal.me/rylesatm?country.x=FR&locale.x=fr_FR");
                    exit();
                } else {
                    echo "<h3 style='color: red;'>Erreur : Aucune commande en cours trouvée.</h3>";
                }
                break;

        }
    }

    if (isset($_POST['FiltrerPanier'])) {
        $lesLivres = $unControleur->selectLikeLivres($_POST['filtre']);
    } else {
        $lesLivres = $unControleur->selectAllLivres();
    }
} else {
    echo "Page indisponible pour le rôle admin.";
}
?>