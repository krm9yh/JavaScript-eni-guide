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
$serveur_soap->register("listerVoitures");

// Méthode listerVoitures
function listerVoitures()
{
    // Définition de la requête SQL à soumettre à la base de données MySQL
    $requete_sql = "select code_voiture, libelle_voiture, vitesse_maximale_voiture
            from voitures
            order by code_voiture;";
    // Paramètres SGBD MySQL
    $serveur_mysql = "127.0.0.1";
    $utilisateur_mysql = "root";
    $mot_de_passe_mysql = "";
    $bdd_mysql = "voitures";

    // Test de connexion à MySQL
    if (($connexion_mysql = @mysql_connect($serveur_mysql, $utilisateur_mysql, $mot_de_passe_mysql)) === FALSE)
    {
        // Message d'erreur envoyé au client
        return new soap_fault("Server", "MySQL", mysql_error());
    }
    else
    {
        // Test accès à la base de données
        if ((@mysql_select_db($bdd_mysql, $connexion_mysql)) === FALSE)
        {
            // Message d'erreur envoyé au client
            return new_soap_fault("Server", "MySQL", mysql_error());
        }
        else
        {
            // Soumission de la requête SQL au moteur SQL de MySQL
            $resultat_sql = @mysql_query($requete_sql, $connexion_mysql);
            // Test du nombre d'enregistrements sélectionnés
            if (@mysql_num_rows($resultat_sql)<1)
            {
                // Message d'erreur envoyé au client si pas d'enregistrement
                $reponse[0] = "Table voitures vide";
                $reponse[1] = "0";
                return $reponse;
            }
            else
            {
                // Parcours séquentiel de l'extraction (vue SQL)
                $i = 0;
                $num_element = 0;
                while ($i < @mysql_num_rows($resultat_sql))
                {
                    // Récupération des valeurs à afficher
                    $code_voiture = mysql_result($resultat_sql, $i, "code_voiture");
                    $libelle_voiture = mysql_result($resultat_sql, $i, "libelle_voiture");
                    $vitesse_maximale_voiture = mysql_result($resultat_sql, $i, "vitesse_maximale_voiture");
                    // Préparation envoi des résultats au client
                    $reponse[$num_element] = $code_voiture;
                    $num_element = $num_element+1;
                    $reponse[$num_element] = $libelle_voiture;
                    $num_element = $num_element+1;
                    $reponse[$num_element] = $vitesse_maximale_voiture;
                    $num_element = $num_element+1;
                    // Passage à l'enregistrement suivant
                    $i++;
                }
                // Envoi de la réponse au client
                return $reponse;
            }
        }

        // Fermeture de la connexion MySQL
        @mysql_close($connexion_mysql);
    }
}

// Envoi de la valeur de retour au client
$serveur_soap->service($HTTP_RAW_POST_DATA);

// Fin de code PHP

?>