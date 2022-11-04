<?php 
require_once("commande.php");

if (etudiantEstConnecter()){ 
require_once("haider.php");
    echo "<h1>". $_SESSION['etudiant']['Nom_Etu'] . "</h1>" . "<br>";
    echo "<h3>LA LIGUE DE ". $_SESSION['etudiant']['Nom_Sport']  . " VOUS SOUHAITE BIENVENU" . "</h3>";
require_once("footer.php");
}
elseif (professeurEstConnecter()){
require_once("haider.php");
echo "<h1>". $_SESSION['professeur']['Nom_Prof'] . "</h1>" . "<br>";
echo "<h3>LA LIGUE DE ". $_SESSION['professeur']['Nom_Sport']  . " VOUS SOUHAITE BIENVENU" . "</h3>";

require_once("footer.php");
}
elseif (internauteEstConnecterEtEstAdmin())
{
    require_once("haider.php");
        echo "<h1>ACCES PRIVE ☣️</h1>"; 
    require_once("footer.php");
}
else{ header("location:connexion.php");}

?>