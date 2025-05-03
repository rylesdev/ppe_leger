<?php
$titrePage = "Vos Commandes";
require_once("includes/header.php");

error_reporting(0);

$idUser = $_SESSION['idUser'];

// Récupérer les données nécessaires
$toutesLesCommandes = $unControleur->selectCommandeByUser($idUser);
$livresPromotion = $unControleur->selectLivrePromotion();

// Gestion des paramètres
$idCommandeSelectionnee = $_POST['idCommandeSelectionnee'] ?? null;
$tri = $_POST['tri'] ?? null;

// Récupérer les commandes selon sélection et tri
if ($idCommandeSelectionnee) {
    $lesCommandes = $unControleur->selectCommandeByIdTri($idCommandeSelectionnee, $tri);
} else {
    $lesCommandes = $unControleur->selectCommandeTri($idUser, $tri);
}

// Calcul du montant total avec promotions
$sommeAPayer = 0;
foreach ($lesCommandes as $uneCommande) {
    $promo = array_filter($livresPromotion, fn($p) => $p['idLivre'] == $uneCommande['idLivre']);
    if (!empty($promo)) {
        $promo = current($promo);
        $prix = $promo['prixPromo']; // Utilisation du prixPromo calculé
    } else {
        $prix = $uneCommande['prixLivre'];
    }
    $sommeAPayer += $prix * $uneCommande['quantiteLigneCommande'];
}

// Récupérer les infos utilisateur
$adresseUser = $unControleur->selectAdresseUser($idUser)['adresseUser'];
$dateCommande = $unControleur->selectDateLivraisonCommande($idUser)[0];
?>

<link rel="stylesheet" href="includes/css/vue_commande.css">

<div class="main-container">
    <!-- Colonne principale - Détails des commandes -->
    <div class="table-container">
        <div class="command-actions">
            <form method="post">
                <div class="form-group">
                    <label for="idCommandeSelectionnee">Sélectionner une commande :</label>
                    <select name="idCommandeSelectionnee" id="idCommandeSelectionnee">
                        <option value="">Toutes mes commandes</option>
                        <?php foreach ($toutesLesCommandes as $cmd):
                            $nbArticles = $unControleur->countLigneCommande($cmd['idCommande']);
                            $dateFormatee = date('d/m/Y à H:i', strtotime($cmd['dateCommande']));
                            ?>
                            <option value="<?= $cmd['idCommande'] ?>"
                                <?= ($cmd['idCommande'] == $idCommandeSelectionnee) ? 'selected' : '' ?>>
                                Commande du <?= $dateFormatee ?> (<?= $nbArticles ?> articles)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tri">Trier par :</label>
                    <select name="tri" id="tri">
                        <option value="">-- Non trié --</option>
                        <option value="prixMin" <?= ($tri == 'prixMin') ? 'selected' : '' ?>>Prix croissant</option>
                        <option value="prixMax" <?= ($tri == 'prixMax') ? 'selected' : '' ?>>Prix décroissant</option>
                        <option value="ordreCroissant" <?= ($tri == 'ordreCroissant') ? 'selected' : '' ?>>A-Z</option>
                        <option value="ordreDecroissant" <?= ($tri == 'ordreDecroissant') ? 'selected' : '' ?>>Z-A</option>
                    </select>
                </div>

                <button type="submit" class="btn-apply">Appliquer</button>
            </form>
        </div>

        <h2>Détails des commandes</h2>
        <table>
            <thead>
            <tr>
                <th>Livre</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Avis</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($lesCommandes as $uneCommande):
                $promo = array_filter($livresPromotion, fn($p) => $p['idLivre'] == $uneCommande['idLivre']);
                $hasPromo = !empty($promo);
                if ($hasPromo) {
                    $promo = current($promo);
                    $prixAffichage = $promo['prixPromo'];
                    $reduction = $promo['reductionPromotion'];
                } else {
                    $prixAffichage = $uneCommande['prixLivre'];
                }
                $totalLivre = $prixAffichage * $uneCommande['quantiteLigneCommande'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($uneCommande['nomLivre']) ?></td>
                    <td>
                        <?php if ($hasPromo): ?>
                            <span class="old-price"><?= number_format($uneCommande['prixLivre'], 2) ?>€</span>
                            <span class="promo-price"><?= number_format($prixAffichage, 2) ?>€ (-<?= $reduction ?>%)</span>
                        <?php else: ?>
                            <?= number_format($uneCommande['prixLivre'], 2) ?>€
                        <?php endif; ?>
                    </td>
                    <td><?= $uneCommande['quantiteLigneCommande'] ?></td>
                    <td><?= number_format($totalLivre, 2) ?>€</td>
                    <td>
                        <form method="post" class="avis-form">
                            <input type="hidden" name="idLivre" value="<?= $uneCommande['idLivre'] ?>">
                            <input type="hidden" name="nomLivre" value="<?= htmlspecialchars($uneCommande['nomLivre']) ?>">
                            <div class="star-rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>_<?= $uneCommande['idLivre'] ?>"
                                           name="noteAvis" value="<?= $i ?>">
                                    <label for="star<?= $i ?>_<?= $uneCommande['idLivre'] ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <textarea name="commentaireAvis" placeholder="Votre avis..."></textarea>
                            <button type="submit" name="ValiderAvis" class="btn-submit">Envoyer l'avis</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Colonne latérale - Récapitulatif -->
    <div class="payment-container">
        <h3>Récapitulatif</h3>
        <div class="summary">
            <p><strong>Total payé :</strong> <?= number_format($sommeAPayer, 2) ?>€</p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($adresseUser) ?></p>
            <p><strong>Date livraison :</strong> <?= $dateCommande ?></p>
        </div>
    </div>
</div>

<?php
require_once("includes/footer.php");
?>