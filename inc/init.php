<?php

try {

    $type_bdd = "mysql";
    $host = "localhost";
    $bdname = "php_compte";
    $username = "root";
    $password = "";
    $option = [
        PDO ::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO ::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC// ICI je définie que le mode de recup des donnees par defaut 
        //sera sous forme associative
    ];

    $bdd= new PDO("$type_bdd:host=$host;dbname=$bdname", $username, $password);

} catch (Exception $e) {
    die("ERREUR CONNEXION BDD: ". $e->getMessage());
  
}

//-----------Appel de mes fonctions

require_once "functions.php";

//Déclaration des variables "globales";

$errorMessage = "";
$successMessage = "";
