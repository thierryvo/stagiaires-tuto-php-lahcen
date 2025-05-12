<?php session_start();
require_once("connexiondb.php");
require_once("../include/les_fonctions/fonctions.php");
$_SESSION['message']['text'] = null;
$_SESSION['message']['type'] = null;
$tabValidationErrors = array();
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES du formulaire: création compte utilisateur ----------------------------------------------------------------------- POST
// formulaire ci-dessous en front
if(
    isset($_POST['creerunnouveaucompteutilisateur']) AND 
    isset($_POST['login']) AND 
    isset($_POST['passe1']) AND 
    isset($_POST['passe2']) AND 
    isset($_POST['email']) ){
    // donnees
    $login = trim(htmlspecialchars($_POST['login']));
    $passe1 = trim(htmlspecialchars($_POST['passe1']));
    $passe2 = trim(htmlspecialchars($_POST['passe2']));
    $email = trim(htmlspecialchars($_POST['email']));

    // CONTROLE des données:
    // =====================
    // login:
    if (strlen($login) < 4) {
        $tabValidationErrors[] = "Erreur!!! Le login doit contenir au moins 4 caratères";
    }
    // Mot de passe:
    if (empty($passe1) || empty($passe2)) {
        $tabValidationErrors[] = "Erreur!!! Les deux mots de passe sont obligatoires";
    }
    if ($passe1 !== $passe2) {
        $tabValidationErrors[] = "Erreur!!! les deux mots de passe ne sont pas identiques";
    }
    // email
    $filtreDuEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($filtreDuEmail != true) {
        $tabValidationErrors[] = "Erreur!!! Cette email est NON valide";
    }
    // SI AUCUNE ERREUR
    if(empty($tabValidationErrors)){
        // RECHERCHE si le user existe déjà
        if (
            rechercher_par_login($login) == 0 AND 
            rechercher_par_email($email) == 0) {
            // ok: user est bien nouveau
            // SQL CREATION du nouveau utilisateur en base de données ============================================== (1)
            $passe=md5($passe1);
            $sql="
            INSERT INTO utilisateur
            (login,email,role,etat,pwd) 
            VALUES(?,?,?,?,?)
            ";
            $req=$pdo->prepare($sql);           
            // sql sous try catch:
            try {
                $req->execute([
                    $login,
                    $email,
                    "VISITEUR",
                    1,
                    $passe
                ]);
                // Vérifier l'update en base de donnees
                if($req->rowCount() != 0){
                    // ok                                     
                    $msg = "Félicitation, votre compte ($login/$email) est crée, mais temporairement inactif jusqu'a activation par l'admin";
                    $_SESSION['message']['text'] = $msg;
                    $_SESSION['message']['type'] = "succes";
                    //
                    // Redirection sur le login
                    header('location:login.php');                    
                    // ============================================================================================== (1)
                }else{
                    // zéro Mise a jour
                    $msg = "AUCUNE mise à jour BdD a été Nécessaire.";                    
                    $_SESSION['message']['text'] = $msg;
                    $_SESSION['message']['type'] = "succes";
                    //
                    // Redirection sur le login
                    header('location:login.php');                                     
                }            

            } catch (Exception $e) {    
                // ARRET exécution par die avec un message
                $mess = "Une erreur SQL s'est produite lors de la création du NOUVEAU utilisateur, sqlmsg e = " .$e->getMessage();
                $tabValidationErrors[] = $mess;
                $_SESSION['message']['type'] = "danger";
            }//FIN try catch
        } else {
            // ko: Ce user existe déjà
            $tabValidationErrors[] = 'Désolé Ce user existe déjà, login ou email déjà existant';
            $_SESSION['message']['type'] = "danger";
        }//FINSI user existe déjà
    }else{
        $_SESSION['message']['type'] = "danger";
    }//FINSI aucune erreur
}//FINSI isset



// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
?>
<!DOCTYPE HTML>
<html>
<head>
<?php require_once '../include/head.php' ?>
    <title> Nouvel utilisateur </title>
</head>
<body>

<div class="container col-md-6 col-md-offset-3">
    <h1 class="text-center"> </h1>
    <!-- BLOC (PANEL) Créer un compte -->
    <div class="panel panel-primary margetop60">
        <div class="panel-heading">Création d'un nouveau compte utilisateur :</div>
        <div class="panel-body">
            
            <!-- FORMULAIRE de SAISIE --- Nouveau compte utilisateur -->
            <form action="utilisateurNouveau.php" method="post" class="form">
                <!-- login -->
                <div class="input-container">
                    <input name="login" placeholder="Nom d'utilisateur (Login)"
                           value="<?= !empty($login)?$login:"" ?>"
                           type="text" class="form-control" autocomplete="off"
                           title="Le login doit contenir au moins 4 caractères..." />
                </div>
                <!-- passe1 : Mot de passe n° 1 -->
                <div class="input-container">
                    <input name="passe1" placeholder="Mot de passe"
                           value="<?= !empty($passe1)?$passe1:"" ?>"
                           type="password" class="form-control" autocomplete="new-password"                   
                           title="Le Mot de passe doit contenir au moins 3 caractères..." />
                </div>
                <!-- passe2 : Mot de passe n° 2   qui sert à confirmet le mot de passe -->
                <div class="input-container">
                    <input name="passe2" placeholder="Confirmer le même mot de passe"
                           value="<?= !empty($passe2)?$passe2:"" ?>"
                           type="password" class="form-control" autocomplete="new-password"
                           title="Le Mot de passe doit être obligatoirement confirmé à l'identique" />
                </div>
                <!-- email -->
                <div class="input-container">
                    <input name="email" placeholder="Votre email pour vérification"
                           value="<?= !empty($email)?$email:"" ?>"
                           type="text" class="form-control" autocomplete="off" 
                           title="L'email est necessaire pour vérification du compte" />
                </div>
                <!-- BOUTON : ENREGISTER -->
                <input type="submit" class="btn btn-primary" value="ENREGISTRER" name="creerunnouveaucompteutilisateur">
            </form>


            <!-- GESTION DES Messages d'ERREURS -- en boucle si plusieurs -->
            <br>
            <?php if(!empty($_SESSION['message']['type']) AND $_SESSION['message']['type'] == "danger" ){
                foreach ($tabValidationErrors as $itemerreur) { ?>
                    <div class="alert alert-danger"><?= $itemerreur ?></div> <?php
                }
            }?>
        </div>
    </div>
</div>
</body>
</html>