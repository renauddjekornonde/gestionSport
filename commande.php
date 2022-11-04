<?php
$mysqli= new mysqli("localhost", "root", "", "projetInformatique");
if ($mysqli->connect_error) die('Un problème est survenu lors de la tentative de connexion à la BDD :'. $mysqli->connect_error);
$mysqli->set_charset("utf-8");

session_start();

define("RACINE_SITE","/projetesitec/");

$contenu = '';

require_once("fonction.php");
?>