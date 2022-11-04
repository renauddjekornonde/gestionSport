<?php
function executeRequete($req)
{
    global $mysqli;
    $resultat= $mysqli->query($req);
    if(!$resultat)
    {
        die("Erreur sur la requete sql.<br>Message : ".$mysqli->error. "<br>Code: ".$req);
    }
    return $resultat;
}

function debug($var, $mode= 1)
{
    $trace= debug_backtrace();
    $trace= array_shift($trace);
    if($mode=== 1)
    {
       // print '<pre>'; print_r($var); print '<pre>';
    }
    else
    {
       // print '<pre>'; var_dump($var); print '<pre>';
    }
}

function etudiantEstConnecter()
{
    if(!isset($_SESSION['etudiant'])) return false;
    else return true;
}

function professeurEstConnecter()
{
    if(!isset($_SESSION['professeur'])) return false;
    else return true;
    $_SESSION["sportProf"]= $_POST['Nom_Sport'];
}

function internauteEstConnecterEtEstAdmin()
{
   
    if(!isset($_SESSION['administrateur'])) return false;
    else return true;
}
?>