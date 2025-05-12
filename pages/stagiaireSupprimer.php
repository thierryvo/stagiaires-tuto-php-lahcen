<?php session_start();
require_once('connexiondb.php');
$pagestagiaires = true;
// UNIQUEMENT SI un user est connecté
if(isset($_SESSION['user'])){
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES de l'action supprimmer dans la liste stagiaires ------------------------------------------------------------------- GET
// ID idStagiaire en GET, car en provenance d'une url d'une action de la liste stagiaires.php
    if(isset($_GET['idStagiaire'])){
        // donnees
        $idStagiaire = $_GET['idStagiaire'];

        // SQL de suppression du Stagiaire =================================================== (2)
        $sql="
        DELETE FROM stagiaire
        WHERE idStagiaire=?
        ";
        $req = $pdo->prepare($sql);
        // SQL sous try-catch
        try {        
            $req->execute([$idStagiaire]);
            // Vérifier le delete en base de donnees
            if($req->rowCount()!=0){
                // ok
                // Redirection sur la liste des Stagiaire
                // header('location:stagiaire.php');
                $msg = "Stagiaire supprimée avec succés.";
                header("location:acq.php?message=$msg&url=stagiaires.php");
                // ============================================================================================== (1)
            }else{
                // zéro Mise a jour
                $msg = "AUCUNE mise à jour BdD a été Nécessaire.";
                header("location:acq.php?message=$msg&url=stagiaires.php");                  
            }//FINSI vérification sql
        } catch (Exception $e) {    
            // ARRET exécution par die avec un message
            $msg = "Une erreur SQL s'est produite lors de la suppression d'un Stagiaire, sqlmsg e = " .$e->getMessage();
            header("location:alert.php?message=$msg&url=stagiaires.php");
        }//FIN try catch

    }//FINSI GET idStagiaire
}else {
    // NON connecté: Connexion
    header('location:login.php');
}//FINSI connecté