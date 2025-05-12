<?php session_start();
require_once('connexiondb.php');
$pagefilieres = true;
// UNIQUEMENT SI un user est connecté
if(isset($_SESSION['user'])){
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES de l'action supprimmer dans la liste filieres --------------------------------------------------------------------- GET
// ID idFiliere en GET, car en provenance d'une url d'une action de la liste filieres.php
    if(isset($_GET['idFiliere'])){
        // donnees
        $idFiliere = $_GET['idFiliere'];

        // CONTROLE: SI il y a des Stagiaires,  qui font partie de cette filière ================= (1)
        $sql="
        SELECT count(*) AS nb
        FROM stagiaire 
        WHERE idFiliere=?
        ";
        $req = $pdo->prepare($sql);
        $req->execute([$idFiliere]);
        $resultat = $req->fetch(PDO::FETCH_ASSOC);
        $nb = $resultat['nb'];// nb = Nombre de stagiaire qui font partie de la filière à supprimmer
        if($nb==0){
            // ok
            // SQL de suppression de la filiere ================================================== (2)
            $sql="
            DELETE FROM filiere 
            WHERE idFiliere=?
            ";
            $req = $pdo->prepare($sql);
            // SQL sous try-catch
            try {        
                $req->execute([$idFiliere]);
                // Vérifier le delete en base de donnees
                if($req->rowCount()!=0){
                    // ok
                    // Redirection sur la liste des Filieres
                    // header('location:filieres.php');
                    $msg = "Filiere supprimée avec succés.";
                    header("location:acq.php?message=$msg&url=filieres.php");
                    // ============================================================================================== (1)
                }else{
                    // zéro Mise a jour
                    $msg = "AUCUNE mise à jour BdD a été Nécessaire.";
                    header("location:acq.php?message=$msg&url=filieres.php");                  
                }//FINSI vérification sql
            } catch (Exception $e) {    
                // ARRET exécution par die avec un message
                $msg = "Une erreur SQL s'est produite lors de la suppression d'une Filiere, sqlmsg e = " .$e->getMessage();
                header("location:alert.php?message=$msg&url=filieres.php");
            }//FIN try catch            

        }else{
            // ko
            $msg = "Suppression impossible: Vous devez supprimer tous les stagiaires inscris dans cette filière";            
            header("location:alert.php?message=$msg&url=filieres.php");
        }//FINSI controle nb stagiaire


    }//FINSI GET idFiliere    
}else {
    // NON connecté: Connexion
    header('location:login.php');
}//FINSI connecté