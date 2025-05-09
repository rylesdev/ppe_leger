<?php
$idUser = $_SESSION['idUser'];

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    $idPromotion = $_REQUEST['idPromotion'] ?? null;
    $idLivre = $_REQUEST['idLivre'] ?? null;

    switch ($action) {
        case "sup":
            $result = $unControleur->deletePromotion($idPromotion);
            if ($result) {
                echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Suppression réussie de la promotion.</div>";
            } else {
                echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de supprimer la promotion</div>";
            }
            break;

        case "edit":
            $laPromotion = $unControleur->selectWherePromotion($idPromotion, $idLivre);
            break;
    }
}

if (isset($_POST['ValiderPromotion'])) {
    $idLivre = $_POST['idLivre'];
    $nomLivre = $_POST['nomLivre'];
    $nomPromotion = $_POST['nomPromotion'];
    $dateDebutPromotion = $_POST['dateDebutPromotion'];
    $dateFinPromotion = $_POST['dateFinPromotion'];
    $reductionPromotion = $_POST['reductionPromotion'];

    var_dump($idLivre, $nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion, $nomLivre);

    // Vérifier si une promotion avec le même nom existe déjà
    $idPromotionExistante = $unControleur->selectIdPromotionByNom($nomPromotion)[0][0];
    $nbLivres = $unControleur->selectNbLivreByPromotion($idPromotionExistante)[0][0];
    echo "nbLivre";
    var_dump($nbLivres);

    if ($idPromotionExistante) {
        if ($nbLivres > 1) {
            $resultInsertPromotion = $unControleur->insertPromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion);
            if ($resultInsertPromotion) {
                $idPromotionInseree = $unControleur->selectIdPromotionByNom($nomPromotion)[0][0];
                var_dump($idPromotionInseree);

                $resultAssocierLivre = $unControleur->updatePromotionLivre($idPromotionInseree, $idLivre);
                if ($resultAssocierLivre) {
                    echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Association réussie du livre à la promotion.</div>";
                } else {
                    echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible d'associer le livre à la promotion</div>";
                }
                echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Insertion réussie de la promotion.</div>";
            } else {
                echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible d'insérer la promotion</div>";
            }
        } else {
            // updatePromotion fonctionne
            $result = $unControleur->updatePromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion, $idPromotionExistante);
            echo"RESULTAT";
            var_dump($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion, $idPromotionExistante);
            if ($result) {
                echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Mise à jour réussie de la promotion.</div>";
            } else {
                echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de mettre à jour la promotion</div>";
            }
        }
    } else {
        // insertPromotion fonctionne
        $resultInsertPromotion = $unControleur->insertPromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion);
        if ($resultInsertPromotion) {
            $idPromotionInseree = $unControleur->selectIdPromotionByNom($nomPromotion)[0][0];
            echo "test";
            // updatePromotionLivre fonctionne
            $resultAssocierLivre = $unControleur->updatePromotionLivre($idPromotionInseree, $idLivre);
            if ($resultAssocierLivre) {
                echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Association réussie du livre à la promotion.</div>";
            } else {
                echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible d'associer le livre à la promotion</div>";
            }
            echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Insertion réussie de la promotion.</div>";
        } else {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible d'insérer la promotion</div>";
        }
    }
}


if (isset($_POST['FiltrerPromotion'])) {
    $lesPromotions = $unControleur->selectLikePromotion($_POST['filtre']);
} else {
    $lesPromotions = $unControleur->selectPromotion();
}

// Structure modifiée pour afficher d'abord le formulaire puis la liste
echo '<div class="container mx-auto px-4 py-6">';

// Afficher d'abord le formulaire pour les admins
if (isset($isAdmin) && $isAdmin == 1) {
    require_once("vue/promotion/vue_insert_promotion.php");
    echo '<div class="my-8 border-t-2 border-gray-200"></div>'; // Séparateur visuel
}

// Ensuite afficher la liste des promotions
require_once("vue/promotion/vue_promotion.php");

echo '</div>'; // Fermeture du container
