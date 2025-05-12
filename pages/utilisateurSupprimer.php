<?php session_start();
require_once('connexiondb.php');
$pageutilisateurs = true;
// UNIQUEMENT SI un user est connecté
if(isset($_SESSION['user'])){
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES de l'action supprimmer dans la liste Utilisateurs ----------------------------------------------------------------- GET
// ID idUser en GET, car en provenance d'une url d'une action de la liste Utilisateurs.php
    if(isset($_GET['iduser'])){
        // donnees
        $iduser = $_GET['iduser'];            
        // SQL de suppression de la Utilisateur ================================================== (1)
        $sql="
        DELETE FROM utilisateur
        WHERE iduser=?
        ";
        $req = $pdo->prepare($sql);
        // SQL sous try-catch
        try {        
            $req->execute([$iduser]);
            // Vérifier le delete en base de donnees
            if($req->rowCount()!=0){
                // ok
                // Redirection sur la liste des Utilisateurs
                // header('location:utilisateurs.php');
                $msg = "Utilisateur supprimée avec succés.";
                header("location:acq.php?message=$msg&url=utilisateurs.php");
                // =============================================================================== (1)
            }else{
                // zéro Mise a jour
                $msg = "AUCUNE mise à jour BdD a été Nécessaire.";
                header("location:acq.php?message=$msg&url=utilisateurs.php");                  
            }//FINSI vérification sql
        } catch (Exception $e) {
            // ARRET exécution par die avec un message
            $msg = "Une erreur SQL s'est produite lors de la suppression d'une Utilisateur, sqlmsg e = " .$e->getMessage();
            header("location:alert.php?message=$msg&url=utilisateurs.php");
        }//FIN try catch
    }//FINSI GET idUser
}else {
    // NON connecté: Connexion
    header('location:login.php');
}//FINSI connecté