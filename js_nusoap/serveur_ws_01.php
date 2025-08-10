<?php
/*
NOM DU SCRIPT : server_ws_01.php
REALISATION INFORMATIQUE : Christian VIGOUROUX
DATE DE CREATION : 15/10/2018
DATE DE DERNIERE : 15/10/2018
OBJET : Listage de voitures de sport via un WebService (retour de champs en format XML)
*/

// Utilisation de la bibliothèque NuSOAP
require_once("nusoap.php");

// Création d'un objet SOAP (instanciation)
$serveur_soap = new soap_server;

// Enregistrement de la méthode listerVoitures dans l'objet afin qu'elles soit
// disponible pour les clients de cet objet
$serveur_sopa->register("listerVoitures");

// Méthode listerVoitures
function listerVoitures()
{
    // Définition de la requête SQL à soumettre à la base de données MySQL
    
}