<?php
if (empty($isAdmin)) {
    echo "<h3 style='color: red;'>Vous n'avez pas les permissions pour accéder à cette page.</h3>";
} else {
    $idUser = $_SESSION['idUser'];

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
        $idMaisonEdition = $_REQUEST['idMaisonEdition'] ?? null;
        $idLivre = $_REQUEST['idLivre'] ?? null;

        switch ($action) {
            case "sup":
                $resultDelete = $unControleur->deleteMaisonEdition($idMaisonEdition);
                if ($resultDelete) {
                    echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Suppression réussie de la maison d'édition.</div>";
                } else {
                    echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de supprimer la maison d'édition</div>";
                }
                break;

            case "edit":
                $laMaisonEdition = $unControleur->selectWhereMaisonEdition($idMaisonEdition);
                break;

            case "associer":
                $associerMaisonEditionLivre = $unControleur->selectWhereLivre($idLivre);
        }
    }

    if (isset($_POST['InsertMaisonEdition'])) {
        $nomMaisonEdition = $_POST['nomMaisonEdition'];
        $idMaisonEditionExistante = $unControleur->selectIdMaisonEditionByNom($nomMaisonEdition)[0][0];

        if ($idMaisonEditionExistante) {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Une maison d'édition avec ce nom existe déjà.</div>";
        } else {
            $resultInsertMaisonEdition = $unControleur->insertMaisonEdition($nomMaisonEdition);
            if ($resultInsertMaisonEdition) {
                echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Insertion réussie de la maison d'édition.</div>";
            }
        }

        echo "<script>window.location.href = 'index.php?page=8';</script>";
    }

    if (isset($_POST['UpdateMaisonEdition'])) {
        $idMaisonEdition = $_POST['idMaisonEdition'];
        $nomMaisonEdition = $_POST['nomMaisonEdition'];

        $resultUpdateMaisonEdition = $unControleur->updateMaisonEdition($nomMaisonEdition, $idMaisonEdition);
        if ($resultUpdateMaisonEdition) {
            echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Mise à jour réussie de la maison d'édition.</div>";
        } else {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de mettre à jour la maison d'édition</div>";
        }

        echo "<script>window.location.href = 'index.php?page=8';</script>";
    }

    if (isset($_POST['UpdateLivre'])) {
        $idLivre = $_POST['idLivre'];
        $idMaisonEdition = $_POST['nomMaisonEdition'];

        $resultUpdateLivre = $unControleur->updateMaisonEditionLivre($idMaisonEdition, $idLivre);
        if ($resultUpdateLivre) {
            echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Association réussie du livre avec la maison d'édition.</div>";
        } else {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible d'associer le livre à la maison d'édition</div>";
        }

        echo "<script>window.location.href = 'index.php?page=8';</script>";
    }

    if (isset($_POST['FiltrerMaisonEdition'])) {
        $lesMaisonsEdition = $unControleur->selectLikeMaisonEdition($_POST['filtreMaisonEdition']);
    } else {
        $lesMaisonsEdition = $unControleur->selectMaisonEdition();
    }

    if (isset($_POST['FiltrerLivre'])) {
        $lesLivres = $unControleur->selectLikeLivre($_POST['filtreLivre']);
    } else {
        $lesLivres = $unControleur->selectLivre();
    }

    echo '<div class="container mx-auto px-4 py-6">';

    if (isset($isAdmin) && $isAdmin == 1) {
        require_once("vue/maisonEdition/vue_insert_maisonEdition.php");
        echo '<div class="my-8 border-t-2 border-gray-200"></div>';
    }

    require_once("vue/maisonEdition/vue_maisonEdition.php");

    echo '</div>';
}