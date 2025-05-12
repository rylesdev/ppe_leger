<?php
if (empty($isAdmin)) {
    echo "<h3 style='color: red;'>Vous n'avez pas les permissions pour accéder à cette page.</h3>";
} else {
    $idUser = $_SESSION['idUser'];

    if (isset($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
        $idCategorie = $_REQUEST['idCategorie'] ?? null;
        $idLivre = $_REQUEST['idLivre'] ?? null;

        switch ($action) {
            case "sup":
                $resultDelete = $unControleur->deleteCategorie($idCategorie);
                if ($resultDelete) {
                    echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Suppression réussie de la catégorie.</div>";
                } else {
                    echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de supprimer la catégorie</div>";
                }
                break;

            case "edit":
                $laCategorie = $unControleur->selectWhereCategorie($idCategorie);
                break;

            case "associer":
                $associerCategorieLivre = $unControleur->selectWhereLivre($idLivre);
        }
    }

    if (isset($_POST['InsertCategorie'])) {
        $nomCategorie = $_POST['nomCategorie'];
        $idCategorieExistante = $unControleur->selectIdCategorieByNom($nomCategorie)[0][0];

        if ($idCategorieExistante) {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Une catégorie avec ce nom existe déjà.</div>";
        } else {
            $resultInsertCategorie = $unControleur->insertCategorie($nomCategorie);
            if ($resultInsertCategorie) {
                echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Insertion réussie de la catégorie.</div>";
            }
        }

        echo "<script>window.location.href = 'index.php?page=9';</script>";
    }

    if (isset($_POST['UpdateCategorie'])) {
        $idCategorie = $_POST['idCategorie'];
        $nomCategorie = $_POST['nomCategorie'];

        $resultUpdateCategorie = $unControleur->updateCategorie($nomCategorie, $idCategorie);
        if ($resultUpdateCategorie) {
            echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Mise à jour réussie de la catégorie.</div>";
        } else {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible de mettre à jour la catégorie</div>";
        }

    }

    if (isset($_POST['UpdateLivre'])) {
        $idLivre = $_POST['idLivre'];
        $idCategorie = $_POST['nomCategorie'];

        $resultUpdateLivre = $unControleur->updateCategorieLivre($idCategorie, $idLivre);
        if ($resultUpdateLivre) {
            echo "<div class='alert alert-success' style='background-color: #1A365D; color: white;'>Association réussie du livre avec la catégorie.</div>";
        } else {
            echo "<div class='alert alert-danger' style='border-color: #1A365D; color: #1A365D;'>Erreur : Impossible d'associer le livre à la catégorie</div>";
        }

        echo "<script>window.location.href = 'index.php?page=9';</script>";
    }

    if (isset($_POST['FiltrerCategorie'])) {
        $lesCategories = $unControleur->selectLikeCategorie($_POST['filtreCategorie']);
    } else {
        $lesCategories = $unControleur->selectCategorie();
    }

    if (isset($_POST['FiltrerLivre'])) {
        $lesLivres = $unControleur->selectLikeLivre($_POST['filtreLivre']);
    } else {
        $lesLivres = $unControleur->selectLivre();
    }

    echo '<div class="container mx-auto px-4 py-6">';

    if (isset($isAdmin) && $isAdmin == 1) {
        require_once("vue/categorie/vue_insert_categorie.php");
        echo '<div class="my-8 border-t-2 border-gray-200"></div>';
    }

    require_once("vue/categorie/vue_categorie.php");

    echo '</div>';
}