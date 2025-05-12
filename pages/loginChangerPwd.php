<?php session_start();
require_once ('connexiondb.php');
require_once('../include//les_fonctions/fonctions.php');
$_SESSION['message']['text'] = null;
$_SESSION['message']['type'] = null;
$autreCas="OUI";
$tabValidationErrors = array();
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES du formulaire: CHANGEMENT de mot de passe  ----------------------------------------------------------------------- POST
// formulaire ci-dessous en front
if(
    isset($_POST['changerlepassword']) AND 
    isset($_POST['oldpwd']) AND 
    isset($_POST['newpwd']) AND 
    isset($_POST['newpwd2']) ){
    // donnees
    $autreCas="NON";
    $oldpwd = trim(htmlspecialchars($_POST['oldpwd']));
    $newpwd = trim(htmlspecialchars($_POST['newpwd']));
    $newpwd2 = trim(htmlspecialchars($_POST['newpwd2']));
    $iduser=$_SESSION['user']['iduser'];

    // Vérifier la connexion:
    // SI user NON connecté: 
    // Redirection sur la PAGE de connexion
    if(!isset($_SESSION['user'])) {
        header('location:login.php');
        exit();
    }

    // CONTROLE des données:
    // =====================
    // ancien/nouveau mot de passe:
    if (empty($oldpwd) || empty($newpwd) || empty($newpwd2)) {
        $tabValidationErrors[] = "Erreur!!! Les trois mots de passe sont obligatoires";
    }    
    // nouveau mot de passe
    if (strlen($newpwd) < 4) {
        $tabValidationErrors[] = "Erreur!!! Le NOUVEAU mot de passe doit contenir au moins 4 caratères";
    }
    // confirmer mot de passe
    if ($newpwd !== $newpwd2) {
        $tabValidationErrors[] = "Erreur!!! les deux NOUVEAUX mots de passe ne sont pas identiques";
    }
    // SI AUCUNE ERREUR
    if(empty($tabValidationErrors)){
        // SQL Lecture utilisateur avec: iduser, mot de passe (old) ================================= (1)
        $pwd=MD5($oldpwd);
        $sql="
        SELECT * 
        FROM   utilisateur 
        WHERE  iduser=?
        AND    pwd=?
        ";
        $req = $pdo->prepare($sql);
        $req->execute([
            $iduser,
            $pwd
        ]);
        $user=$req->fetch(PDO::FETCH_ASSOC);
        if($user){
            // ok
            // SQL Mettre à jour l'utilisateur avec: NOUVEAU mot de passe (new) ===================== (2)
            $pwd=MD5($newpwd);
            $sql="
            UPDATE utilisateur 
            SET    pwd=?
            WHERE  iduser=?
            ";
            $req = $pdo->prepare($sql);
            // sql sous try catch
            try {
                $req->execute([            
                    $pwd,
                    $iduser
                ]);
                // Vérifier l'update en base de donnees
                if($req->rowCount() != 0){
                    // ok      
                    $msg = "Félicitation, votre Mot de passe a été modifié avec succés";
                    $_SESSION['message']['text'] = $msg;
                    $_SESSION['message']['type'] = "succes";
                    //
                    // Redirection sur le login
                    header('location:login.php');        
                    // ============================================================================== (2)
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
                $msg = "Une erreur SQL s'est produite lors du changement mot de passe utilisateur, sqlmsg e = " .$e->getMessage();
                $tabValidationErrors[] = $msg;
                $_SESSION['message']['text'] = $msg;
                $_SESSION['message']['type'] = "danger";               
            }//FIN try catch
        }else{
            // ko
            $msg = "L'ancien mot de passe est incorrect !!!!";
            $tabValidationErrors[] = $msg;
            $_SESSION['message']['text'] = $msg;
            $_SESSION['message']['type'] = "danger";   
        }//FINSI user corret
    }else{
        $_SESSION['message']['type'] = "danger";
    }//FINSI aucune erreur  
}//FINSI isset enPOST


// ZONES en get issu de l url dans le mail de changement mot de passe  ----------------------------------------------------- GET
// formulaire ci-dessous en front
if(
    isset($_GET['email']) AND 
    isset($_GET['token']) AND 
    isset($_GET['iduser']) ){
    // donnee
    $autreCas="NON";
    $email = trim(htmlspecialchars($_GET['email']));
    $token = trim(htmlspecialchars($_GET['token']));
    $iduser = trim(htmlspecialchars($_GET['iduser']));
    $user = rechercher_user_par_email($email);
    $unToken = rechercher_token_en_base($email);
    if($user AND $token == $unToken){
        // ok
        // il faut stoker l'ancien passe qu'il a perdu et c'est 0000 quatre zéro
        // Et connecter le user en session
        $oldpwd = "0000";
        // CONNEXION = je met le user en $_SESSION
        $tabUtilisateur = [
            'iduser' => $iduser,
            'login' => $user['login'],
            'email' => $user['email'],
            'role' => $user['role'],
            'etat' => $user['etat'],
            'token' => $token
        ];
        $_SESSION['user']=$tabUtilisateur;// = User + token Connecté

    }else{
        // ko
        header('location:login.php');
        exit();
    }//FINSI user vérifié
}//FINSI isset en GET




// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
if($autreCas=="OUI"){
    // Vérifier la connexion
    // SI user NON connecté: 
    // Redirection sur la PAGE de connexion
    if(!isset($_SESSION['user'])) {
        header('location:login.php');
        exit();
    }    
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <?php include '../include/head.php' ?>
    <title>Changement de mot de passe</title>    
</head>
<body>
<div class="container editpwd-page">
    <!-- BLOC (PANEL) Changement pwd -->
    <div class="panel panel-primary margetop30">
        <div class="panel-heading">Changement de mot de passe</div>
        <div class="panel-body">
            <h2 class="text-center"> Compte :<?php echo $_SESSION['user']['login'] ?>    </h2>
            <!-- FORMULAIRE de SAISIE --- ancien/nouveau mot de passe -->

            <form action="loginChangerPwd.php" method="post" class="form-horizontal">
                <!-- oldpwd : Mot de passe actuel (old) -->
                <div class="input-container">
                    <input name="oldpwd" placeholder="Votre Mot de passe actuel  (ancien)"
                           value="<?= !empty($oldpwd)?$oldpwd:"" ?>"
                           type="password" class="form-control oldpwd" autocomplete="new-password"
                           title="Taper l'ancien Mot de passe pour vous identifier de manière certaine" />
                    <i class="fa fa-eye fa-2x show-old-pwd clickable"></i>
                </div>
                <br>
                <!-- newpwd : NOUVEAU Mot de passe (new) -->
                <div class="input-container">
                    <input name="newpwd" placeholder="Nouveau Mot de passe"
                           value="<?= !empty($newpwd)?$newpwd:"" ?>"
                           type="password" class="form-control newpwd" autocomplete="new-password"
                           title="Taper un nouveau Mot de passe pour votre sécurité" />
                    <i class="fa fa-eye fa-2x show-new-pwd clickable"></i>
                </div>
                <!-- newpwd2 : NOUVEAU Mot de passe (new) confirmer -->
                <div class="input-container">
                    <input name="newpwd2" placeholder="Confirmer le nouveau Mot de passe"
                           value="<?= !empty($newpwd2)?$newpwd2:"" ?>"
                           type="password" class="form-control newpwd" autocomplete="new-password"
                           title="Confirmer ce nouveau Mot de passe pour vérification" />
                    <i class="fa fa-eye fa-2x show-new-pwd clickable"></i>
                </div>                
                <!-- BOUTON ENREGISTRER -->
                <input type="submit" value="ENREGISTRER" class="btn btn-primary btn-block" name="changerlepassword" />
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

<?php include '../include/foot.php' ?>
</body>
</html>