<?php
require_once("modele/modele.class.php");

if (!isset($_SESSION['livreOffert'])) {
    echo "Variable de session non définie.";
} else {
    echo "Variable définie : " . $_SESSION['livreOffert'];
}

if (isset($_SESSION['livreOffert'])) {
    echo $_SESSION['livreOffert'];
    unset($_SESSION['livreOffert']);
}

$prenomUser = $_SESSION['prenomUser'];
$nomUser = $_SESSION['nomUser'];
echo "<h2> Bienvenue  " . $prenomUser . ' ' . $nomUser . "</h2>";

?>

<br>

<img src="images/livreOffert.png" height="500" width="450">
<img src="images/accueil.png" height="400" width="800">