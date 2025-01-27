<?php
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}


$prenomUser = $_SESSION['prenomUser'];
$nomUser = $_SESSION['nomUser'];
echo "<h2> Bienvenue  " . $prenomUser . ' ' . $nomUser . "</h2>";

?>

<br>

<img src="images/accueil.png" height="400" width="1000">