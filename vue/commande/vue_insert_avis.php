<?php
$idUser = $_SESSION['idUser'];

if (isset($_POST['ValiderAvis'])) {
    $idLivre = $_POST['idLivre'];
    $nomLivre = $_POST['nomLivre'];
    $commentaireAvis = $_POST['commentaireAvis'];
    $noteAvis = $_POST['noteAvis'];

    if ($noteAvis && $commentaireAvis) {
        $unControleur->insertAvis($idLivre, $nomLivre, $idUser, $commentaireAvis, $noteAvis);
    } else {
        echo "<h3 style='color:red'> Tous les champs doivent être remplis ! </h3>";
    }
}
?>
