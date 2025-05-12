<?php
    require_once('identifier.php');// Vérification LOGIN
    require_once('connexiondb.php');
    // BACK ------------------------------------------------------------------------------------------------------------------- BACK
    // ZONES du formulaire: filiereNouvelle.php ------------------------------------------------------------------------------- POST
    if(
        isset($_POST['nomFiliere']) AND  
        isset($_POST['niveau'])){
        // donnees
        $nomFiliere = $_POST['nomFiliere'];
        $niveau = $_POST['niveau'];
        // $niveau = strtoupper($_POST['niveau']);

        if(!empty($nomFiliere) && !empty($niveau)){
            // SQL pour insérer un filiere ============================================ (1)
            $sql="
            INSERT INTO filiere
            (nomFiliere,niveau) 
            values(?,?)
            ";
            //
            $req=$pdo->prepare($sql);
            $req->execute([
                $nomFiliere,
                $niveau
            ]);
            //
            // Vérifier l insertion en base de donnees
            if($req->rowCount()!=0){
                // ok
                RAZmessages();
                //
                // Redirection sur la liste des filières
                header('location:filieres.php');                                
            }else{
                // KO: erreur d insertion sql
                $message_erreur = "Une erreur SQL s'est produite lors de l'ajout d'une catégorie!";
                $_SESSION['message']['text'] = $message_erreur;
                $_SESSION['message']['type'] = "danger";
                header('location:filiereNouvelle.php');
            }
        }else{
            // KO: il manque une donnees
            $message_erreur = "nomFiliere, niveau sont obligatoires!";
            $_SESSION['message']['text'] = $message_erreur;
            $_SESSION['message']['type'] = "danger";
            header('location:filiereNouvelle.php');
        }  
    }
//
// ============================================================================================
//                     FONCTIONS
// ============================================================================================
//
// RAZmessages() --------------------------------------------------
function RAZmessages(){
    // RAZ: effacer les messages $_SESSION
    $_SESSION['message']['text'] = null;
    $_SESSION['message']['type'] = null;
    $_SESSION['filiere_ajouter'] = 0;
}