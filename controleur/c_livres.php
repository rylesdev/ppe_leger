<h2> Acheter un livre </h2>

<?php
$leLivre = null;

if (!isset($_SESSION['commandeEnCours'])) {
    $_SESSION['commandeEnCours'] = 0;
    $commandeEnCours = $_SESSION['commandeEnCours'];
}

if (isset($_GET['action']) && isset($_GET['idLivre']) ){
    if (isset($_POST['QuantiteLivre'])) {
        /*$_SESSION['panier'] = [];*/
        $quantiteLivre = $_POST['insertQuantiteLivre'];
    }

    $action  = $_GET['action'];
    $idLivre = $_GET['idLivre'];
    $idUser = $_SESSION['idUser'];

    switch($action){
        case "sup"  :       $unControleur->deleteLivre($idLivre);
                            break;

        case "edit" :       $leLivre = $unControleur->selectWhereLivre($idLivre);
                            break;

        case "acheter" :    if (isset($idLivre) && isset($quantiteLivre) && $quantiteLivre > 0) {
                                if (($commandeEnCours === 0) || ($commandeEnCours === null)) {
                                    $idCommande = $unControleur->insertCommande($idUser);
                                    if ($idCommande) {
                                        $commandeEnCours = $idCommande;
                                    } else {
                                        echo "<h3 style='color: red;'>Erreur : Impossible de créer la commande.</h3>";
                                    }
                                } else {
                                    $idCommande = $commandeEnCours;
                                }

// Le problème vient du fait que, malgré qu'il existe déjà une "$commandeEnCours", le "insertCommande" se fait tout de même.
// Cela fait que, lorsque je sélectionne un livre, la commande est créée.
// Or, il faudrait que la commande ne soit crée que si je clique sur "Acheter" pour la première fois.
// commande -> 1,n (Contient) -> 1,1 ligneCommande

                                $result = $unControleur->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);

                                if ($result) {
                                    echo "<h3 style='color: green;'>Livre ajouté à la commande avec succès.</h3>";
                                } else {
                                    echo "<h3 style='color: red;'>Erreur : Impossible d'ajouter le livre à la commande.</h3>";
                                }
                            } else {
                                echo "<h3 style='color: red;'>Erreur : Quantité non valide ou livre manquant.</h3>";
                            }
                                break;

            /*if (isset($idLivre) && isset($quantiteLivre) && $quantiteLivre > 0) {
                                if (!isset($_SESSION['panier'][$idLivre])) {
                                    $_SESSION['panier']['idLivre'] = [];
                                    $_SESSION['panier']['quantiteLivre'] = [];
                                }
                            $index = array_search($idLivre, $_SESSION['panier']['idLivre']);
                            if ($index === false) {
                                $_SESSION['panier']['idLivre'][] = $idLivre;
                                $_SESSION['panier']['quantiteLivre'][] = $quantiteLivre;
                            } else {
                                $_SESSION['panier']['quantiteLivre'][$index] += $quantiteLivre;
                            }
                                echo "<h3 style='color: green;'>Livre ajouté au panier avec succès.</h3>";
                            } else {
                                echo "<h3 style='color: gray;'>Veuillez entrer la quantité, puis cliquer sur 'Ajouter au panier'.</h3>";
                            }
                            break;*/

            /*if (isset($quantiteLivre) && $quantiteLivre > 0) {
                                $idCommande = $unControleur->insertCommande($idUser);
                                $unControleur->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);*/
                                /*$idCommande = $unControleur->insertCommande($idUser);
                                if ($idCommande) {
                                    $lignesCommande = $unControleur->selectLigneCommande($idCommande);
                                    foreach ($lignesCommande as $ligne) {
                                        $idLivre = $ligne['idLivre'];
                                        $unControleur->insertLigneCommande(153, 5, 5);
                                    }
                                } else {
                                    echo "Erreur lors de l'insertion de la commande.";
                                }*/
                            } /*elseif (isset($quantiteLivre) && $quantiteLivre == null) {
                                $quantiteLivre = 1;
                                $idCommande = $unControleur->insertCommande($idUser);
                                $unControleur->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);
                            }*/
}

if (isset($_SESSION['roleUser']) && $_SESSION['roleUser'] == "admin") {
    echo "<h3>Ajout d'un livre</h3>";
    echo "<br>";

    require_once("vue/vue_insert_livre.php");
} else {
    echo "";
}

if (isset($_POST['ValiderInsert'])) {
    $nomLivre = $_POST['nomLivre'];
    $categorieLivre = $_POST['categorieLivre'];
    $auteurLivre = $_POST['auteurLivre'];
    $imageLivre = $_POST['imageLivre'];
    $prixLivre = $_POST['prixLivre'];

    if ($unControleur->insertLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $prixLivre)) {
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
    $idUser = $_SESSION['idUser'];
    $lesLivres = $unControleur->selectAllLivres($idUser);
}

require_once("vue/vue_select_livre.php");
?>
