<?php
if (empty($isAdmin)) {
    echo "<h3 style='color: red;'>Vous n'avez pas les permissions pour accéder à cette page.</h3>";
} else {
    $idUser = $_SESSION['idUser'];

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
        $idPromotion = $_REQUEST['idPromotion'] ?? null;
        $idLivre = $_REQUEST['idLivre'] ?? null;

        switch ($action) {
            case "sup":
                $resultDelete = $unControleur->deletePromotion($idPromotion);
                if ($resultDelete) {
                    echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Suppression réussie de la promotion.</div>";
                } else {
                    echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de supprimer la promotion</div>";
                }
                break;

            case "edit":
                $laPromotion = $unControleur->selectWherePromotion($idPromotion);
                break;

            case "associer":
                $associerPromoLivre = $unControleur->selectWhereLivre($idLivre);
        }
    }

    if (isset($_POST['InsertPromotion'])) {
        $nomPromotion = $_POST['nomPromotion'];
        $dateDebutPromotion = $_POST['dateDebutPromotion'];
        $dateFinPromotion = $_POST['dateFinPromotion'];
        $reductionPromotion = $_POST['reductionPromotion'];
        $idPromotionExistante = $unControleur->selectIdPromotionByNom($nomPromotion)[0][0];

        if ($idPromotionExistante) {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Une promotion avec ce nom existe déjà.</div>";
        } else {
            $resultInsertPromotion = $unControleur->insertPromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion);
            if ($resultInsertPromotion) {
                echo "<div class='alert alert-success' style='background-color: #1A365D; color";
            }
        }

        echo "<script>window.location.href = 'index.php?page=7';</script>";
    }

    if (isset($_POST['UpdatePromotion'])) {
        $idPromotion = $_POST['idPromotion'];
        $nomPromotion = $_POST['nomPromotion'];
        $dateDebutPromotion = $_POST['dateDebutPromotion'];
        $dateFinPromotion = $_POST['dateFinPromotion'];
        $reductionPromotion = $_POST['reductionPromotion'];

        $resultUpdatePromotion = $unControleur->updatePromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion, $idPromotion);
        if ($resultUpdatePromotion) {
            echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Mise à jour réussie de la promotion.</div>";
        } else {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de mettre à jour la promotion</div>";
        }

        echo "<script>window.location.href = 'index.php?page=7';</script>";
    }

    if (isset($_POST['UpdateLivre'])) {
        $idLivre = $_POST['idLivre'];
        $idPromotion = $_POST['nomPromotion'];

        $resultUpdateLivre = $unControleur->updatePromotionLivre($idPromotion, $idLivre);
        if ($resultUpdateLivre) {
            echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Association réussie du livre avec la promotion.</div>";
        } else {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible d'associer le livre à la promotion</div>";
        }

        echo "<script>window.location.href = 'index.php?page=7';</script>";
    }


    if (isset($_POST['FiltrerPromotion'])) {
        $lesPromotions = $unControleur->selectLikePromotion($_POST['filtrePromotion']);
    } else {
        $lesPromotions = $unControleur->selectPromotion();
    }

    if (isset($_POST['FiltrerLivre'])) {
        $lesLivres = $unControleur->selectLikeLivre($_POST['filtreLivre']);
    } else {
        $lesLivres = $unControleur->selectLivre();
    }

    echo '<div class="container mx-auto px-4 py-6">';

    if (isset($isAdmin) && $isAdmin == 1) {
        require_once("vue/promotion/vue_insert_promotion.php");
        echo '<div class="my-8 border-t-2 border-gray-200"></div>';
    }

    require_once("vue/promotion/vue_promotion.php");

    echo '</div>';
}