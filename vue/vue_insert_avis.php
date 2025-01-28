<?php
$idUser = $_SESSION['idUser'];

if (isset($_POST['ValiderAvis'])) {
    $idLigneCommande = $_POST['idLigneCommande'];
    $noteAvis = $_POST['noteAvis'];
    $commentaireAvis = $_POST['commentaireAvis'];
    $idLivre = $_SESSION['idLivre'];

    if ($noteAvis && $commentaireAvis) {
        $unControleur->insertAvis($idLivre, $idUser, $commentaireAvis, $noteAvis);
    } else {
        echo "Tous les champs doivent être remplis !";
    }
}
?>
