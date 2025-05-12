<?php session_start();
require_once('connexiondb.php');
$pageutilisateurs = true;
// UNIQUEMENT SI un user est connecté
if(isset($_SESSION['user'])){
    // BACK ------------------------------------------------------------------------------------------------------------------- BACK    
    // ZONES de l'action Activer dans la liste Utilisateurs -------------------------------------------------------------------- GET
    // ID idUser en GET, car en provenance d'une url d'une action de la liste Utilisateurs.php
    if(
        isset($_GET['iduser']) AND 
        isset($_GET['etat']) ){
        // donnees
        $iduser = $_GET['iduser'];
        $etat = $_GET['etat'];
        //
        // INVERSER l'etat:
        // - SI Activé     Alors => Désactiver
        // - SI Désactivé  Alors => Activer
        if($etat==1){
            // Activé    => Désactiver
            $newEtat=0;
        }else{
            // Désactivé => Activer
            $newEtat=1;
        }

        // SQL Modification Utiisateur ==================================================== (1)
        $sql="
        UPDATE utilisateur
        SET    etat=?
        WHERE  iduser=?
        ";
        $req = $pdo->prepare($sql);
        // SQL sous try-catch
        try {        
            $req->execute([
                $newEtat,
                $iduser
            ]);
            // Vérifier le upadte en base de donnees (Activer/Désactiver)
            if($req->rowCount()!=0){
                // ok
                // Redirection sur la liste des Utilisateurs
                // header('location:utilisateurs.php');
                $msg = "";
                if($etat==1){
                    // Activé    => Désactiver
                    $msg = "Utilisateur désactivé avec success.";           
                }else{
                    // Désactivé => Activer
                    $msg = "Utilisateur activé avec success.";                               
                }
                header("location:acq.php?message=$msg&url=utilisateurs.php");
                // =============================================================================== (1)
            }else{
                // zéro Mise a jour
                $msg = "AUCUNE mise à jour BdD a été Nécessaire.";
                header("location:acq.php?message=$msg&url=utilisateurs.php");                  
            }//FINSI vérification sql
        } catch (Exception $e) {
            // ARRET exécution par die avec un message
            $msg = "Une erreur SQL s'est produite lors de l'activation -OU- la désactivation de l'Utilisateur, sqlmsg e = " .$e->getMessage();
            header("location:alert.php?message=$msg&url=utilisateurs.php");
        }//FIN try catch

    }//FINSI isset            
}else {
    // => connexion
    header('location:login.php');
}//FINSI connecté
