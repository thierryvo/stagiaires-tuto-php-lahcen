<?php 
require_once('identifier.php'); // Vérification LOGIN
require_once("connexiondb.php");
//
// Infomer la base de données de la déconnexion
$user = $_SESSION['user'];
$iduser=$user['iduser'];

$sql=" SELECT * FROM utilisateur_token WHERE iduser=?";
$req = $pdo->prepare($sql);
$req->execute([$iduser]);
$retour=$req->fetch(PDO::FETCH_ASSOC);
if($retour){
    // update
    $sql="
        UPDATE utilisateur_token
        SET    isConnecter=?
        WHERE  iduser=?
    ";
    $req = $pdo->prepare($sql);
    // sql sous try catch:
    try {        
        $req->execute([            
            0,
            $iduser
        ]);
        // Vérifier l'update en base de donnees
        if($req->rowCount()!=0){
            // ok
        }else{
            // zéro Mise a jour                                     
        }//FINSI verifier
    } catch (Exception $e) {    
        // ARRET exécution par die avec un message
        $mess = "Une erreur SQL s'est produite lors de la modification de l'Utilisateur, sqlmsg e = " .$e->getMessage();
    }//FIN try catch
}//FINSI retour existe
//
//
// Détruire la session
session_destroy();
//
// Redirection vers la PAGE: login
header('location:login.php');