<?php
$nom_serveur = "127.0.0.1"; // 127.0.0.1 ou localhost
$nom_basededonnees = "stagiaires";
$utilisateur = "root";
$motpass = "";
$port=3307;
//
// Options de connexion
$options = [
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Désactiver le mode d'émulation pour les "vraies" instructions préparées
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Désactiver les erreurs sous forme d'exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Faire en sorte que la récupération par défaut soit un tableau associatif
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',       // utf8 
];
//
// connexion sous try catch:
try {
    // Instancier l'Objet PDO de connexion
    $pdo = new PDO(
        "mysql:host=$nom_serveur;port=$port;dbname=$nom_basededonnees",
        $utilisateur,
        $motpass,
        $options
    );    
    //
    // RETOUR de la connexion
    return $pdo;
} catch (Exception $e) {    
    // ARRET exécution par die avec un message
    $msg = "Erreur de connexion mysql, sqlmsg e = " .$e->getMessage();
    die($msg);
}