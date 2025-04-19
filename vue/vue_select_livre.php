<header style="background-color: #2E6E49; color: white; text-align: center; padding: 15px; font-size: 24px; font-weight: bold;">
    <link rel="stylesheet" href="includes/css/vue_select_livre.css">
    <h2>Acheter un livre</h2>
</header>
<h3>Liste des livres (<?= count($lesLivres) ?>)</h3>

<form method="post">
    Filtrer par : <input type="text" name="filtre">
    <input type="submit" name="FiltrerLivre" value="Filtrer" class="table-success">
</form>
<br>

<table class="table" style="margin-left: 50px; margin-right: 50px; table-layout: fixed; width: 100%">
    <thead class="table-success">
    <tr>
        <th scope="col" style="width: 100px">Image</th>
        <th scope="col" style="width: 200px">Nom</th>
        <th scope="col" style="width: 150px">Catégorie</th>
        <th scope="col" style="width: 150px">Auteur</th>
        <th scope="col" style="width: 150px">Maison d'édition</th>
        <th scope="col" style="width: 100px">Exemplaires</th>
        <th scope="col" style="width: 120px">Prix</th>
        <th scope="col" style="width: 200px">Opérations</th>
    </tr>
    </thead>
    <tbody>

    <?php
    // Récupération des promotions indexées par idLivre pour optimisation
    $livresPromotion = $unControleur->selectLivrePromotion();
    $promosParId = [];
    foreach ($livresPromotion as $promo) {
        $promosParId[$promo['idLivre']] = $promo;
    }

    $idUser = $_SESSION['idUser'];

    if (isset($lesLivres)) {
        foreach ($lesLivres as $unLivre) {
            $enPromotion = isset($promosParId[$unLivre['idLivre']]);
            $prixPromo = $enPromotion ?
                $unLivre['prixLivre'] * (1 - $promosParId[$unLivre['idLivre']]['reductionPromotion'] / 100) :
                null;

            echo "<tr>";
            // Image du livre
            echo "<td style='width: 100px'><img src='images/livres/" . htmlspecialchars($unLivre['imageLivre']) . "' style='height: 150px; width: 100px; object-fit: contain' alt='Couverture du livre'></td>";

            // Informations du livre
            echo "<td style='width: 200px'>" . htmlspecialchars($unLivre['nomLivre']) . "</td>";
            echo "<td style='width: 150px'>" . htmlspecialchars($unLivre['nomCategorie']) . "</td>";
            echo "<td style='width: 150px'>" . htmlspecialchars($unLivre['auteurLivre']) . "</td>";
            echo "<td style='width: 150px'>" . htmlspecialchars($unLivre['nomMaisonEdition']) . "</td>";
            echo "<td style='width: 100px; text-align: center'>" . htmlspecialchars($unLivre['exemplaireLivre']) . "</td>";

            // Prix avec gestion de la promotion
            if ($enPromotion) {
                echo "<td style='width: 120px'>
                    <del>" . number_format($unLivre['prixLivre'], 2) . "€</del><br>
                    <span style='color: red; font-weight: bold'>" . number_format($prixPromo, 2) . "€</span><br>
                    <small>-" . $promosParId[$unLivre['idLivre']]['reductionPromotion'] . "%</small>
                </td>";
            } else {
                echo "<td style='width: 120px'>" . number_format($unLivre['prixLivre'], 2) . "€</td>";
            }

            // Opérations (admin ou utilisateur)
            echo "<td style='width: 200px; white-space: nowrap'>";
            if (isset($isAdmin) && $isAdmin == 1) {
                // Boutons admin
                echo "<a href='index.php?page=2&action=sup&idLivre=" . $unLivre['idLivre'] . "' style='margin-right: 5px' title='Supprimer'>
                        <img src='images/supprimer.png' height='30' width='30'>
                    </a>
                    <a href='index.php?page=2&action=edit&idLivre=" . $unLivre['idLivre'] . "' title='Éditer'>
                        <img src='images/editer.png' height='30' width='30'>
                    </a>";
            } else {
                // Formulaire utilisateur
                echo "<form method='post' style='display: inline-block; margin-right: 10px; margin-bottom: 5px'>
                        <input type='number' name='insertQuantiteLivre' min='1' max='" . $unLivre['exemplaireLivre'] . "' 
                               style='width: 50px; display: inline-block' required>
                        <button type='submit' name='QuantiteLivre' style='background-color: #28a745; color: white; border: none; padding: 2px 5px; cursor: pointer'>
                            OK
                        </button>
                    </form>
                    <a href='index.php?page=2&action=acheter&idLivre=" . $unLivre['idLivre'] . "&idUser=" . $idUser . "' 
                       style='display: inline-block; margin-top: 5px' title='Ajouter au panier'>
                        <img src='images/acheter.png' height='30' width='30' style='vertical-align: middle'>
                        Panier
                    </a>";
            }
            echo "</td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>

<?php
require_once("includes/footer.php");
?>