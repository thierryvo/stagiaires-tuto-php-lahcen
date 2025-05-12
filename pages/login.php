<?php session_start();
require_once('connexiondb.php');
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES du formulaire: Se Connecter -------------------------------------------------------------------------- SeConnecter POST
// formulaire ci-dessous en front
if(
    isset($_POST['seconnecter']) AND    
    isset($_POST['login']) AND
    isset($_POST['pwd']) ){
    // donnees
    $login = trim(htmlspecialchars($_POST['login']));
    $pwd = trim(htmlspecialchars($_POST['pwd']));

    // SQL de récupération du user pour CONNEXION ======================================================= (1)
    $sql="
    SELECT iduser,login,email,role,etat 
    FROM   utilisateur 
    WHERE  login=? 
    AND    pwd=?
    ";
    $pass = MD5($pwd);
    $req = $pdo->prepare($sql);
    $req->execute([
        $login,        
        $pass
    ]);
    $user=$req->fetch(PDO::FETCH_ASSOC);
    if($user){
        // ok: cet user existe bien
        // vérifier l'état du user (Activé/Désactivé)        
        if($user['etat']==1){
            // ok: user est bien activé
            // suite: token + informations
            $iduser=$user['iduser'];
            $sql=" SELECT * FROM utilisateur_token WHERE iduser=?";
            $req = $pdo->prepare($sql);
            $req->execute([$iduser]);
            $retour=$req->fetch(PDO::FETCH_ASSOC);
            //
            // token
            $letoken=bin2hex(random_bytes(16)); // random en bytes convertit en hexadécimal
            $dateISO = date('Y-m-d H:i:s'); // date format aaaammjjhhmmss ou date('Y-m-d H:i:s') = aaaa-mm-jj hh:mm:ss
            $compteur = 0;
            if($retour){
                // trouvé --- update utilisateur_token ================================================= (2)
                $compteur = $retour['connexion_compteur'] + 1;
                $sql="
                    UPDATE utilisateur_token
                    SET    email=?,
                           token=?,
                           isConnecter=?,
                           connexion_compteur=?,
                           connexion_derniere=?
                    WHERE  iduser=?
                ";
                $req = $pdo->prepare($sql);
            }else{
                // NON trouvé --- insert utilisateur_token ============================================= (2)
                $compteur = 1;
                $sql="
                    INSERT INTO utilisateur_token
                    (email,token,isConnecter,connexion_compteur,connexion_derniere,iduser)
                    VALUES(?,?,?,?,?,?)
                ";
                $req = $pdo->prepare($sql);
            }
            // sql sous try catch:
            try {        
                $req->execute([
                    $user['email'],
                    $letoken,
                    1,
                    $compteur,
                    $dateISO,
                    $iduser
                ]);
                // Vérifier l'update en base de donnees
                if($req->rowCount()!=0){
                    // ok
                    // CONNEXION = je met le user en $_SESSION
                    $tabUtilisateur = [
                        'iduser' => $iduser,
                        'login' => $user['login'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'etat' => $user['etat'],
                        'token' => $letoken
                    ];
                    $_SESSION['user']=$tabUtilisateur;// = User + token Connecté
                    //
                    // Mise à zéro des variables compteur pour gérer les messages
                    $_SESSION['message']['text'] = null;
                    $_SESSION['message']['type'] = null;
                    $_SESSION['filiere_ajouter'] = 0;
                    $_SESSION['filiere_modifier'] = 0;
                    $_SESSION['stagiaire_ajouter'] = 0;
                    $_SESSION['stagiaire_modifier'] = 0;          
                    $_SESSION['utilisateur_modifier'] = 0;
                    // redirection vers la PAGE: index            
                    header('location:../index.php');    
                    // OK ======================================================================================== (1)
                    // =========================================================================================== (2)
                }else{
                    // zéro Mise a jour
                    $msg = "AUCUNE mise à jour BdD a été Nécessaire!!!???";
                    $_SESSION['message']['text'] = $msg;
                    $_SESSION['message']['type'] = "success";                                         
                }//FINSI verifier
            } catch (Exception $e) {    
                // ARRET exécution par die avec un message
                $mess = "Une erreur SQL s'est produite lors de la modification de l'Utilisateur, sqlmsg e = " .$e->getMessage();
                $_SESSION['message']['text'] = $mess;
                $_SESSION['message']['type'] = "danger";
            }//FIN try catch            
        }else{
            // ko: le user est : Désactivé
            $mess = "Votre compte est désactivé !!! Veuillez contacter l'administrateur";
            $_SESSION['message']['text'] = $mess;
            $_SESSION['message']['type'] = "danger";            
        }//FINSI activé désactivé
    }else{
        // ko: ERREUR de login
        $mess = "Login ou mot de passe incorrecte!!!!!";
        $_SESSION['message']['text'] = $mess;
        $_SESSION['message']['type'] = "danger";
    }//FINSI user eiste
}//FINSI isset seconnecter -------------------------------------------------------------------------------- FIN SeConnecter POST





// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
?>
<! DOCTYPE HTML>
<HTML>
<head>
    <?php include '../include/head.php' ?>
    <title>Se connecter</title>
</head>
<body>
<div class="container col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
    <!-- BLOC (PANEL) Se Connecter -->
    <div class="panel panel-primary margetop60">
        <div class="panel-heading">Se connecter :</div>
        <div class="panel-body">   
            
            <!-- SI Nécessaire: Message ERREUR                             -->
            <!-- Afficher les messages (session) juste en dessous du titre -->
            <?php            
            if(!empty($_SESSION['message']['text'])){
                $letype = $_SESSION['message']['type']; // danger ou success
                ?>
                <!-- class  alert alert-danger      ou bien alert alert-success -->
                <div class="alert alert-<?= $letype=="danger"?"danger":"success" ?>" role="alert">
                    <?= $_SESSION['message']['text'] ?>
                </div>
            <?php
            }
            ?>               
            
            <!-- FORMULAIRE de CONNEXION --- seconnecter, voir sur le Bouton -->
            <form method="post" action="login.php" class="form">
                <!-- Login -->
                <div class="form-group">
                    <label for="login">Login :</label>
                    <input name="login" placeholder="Login"
                           type="text" class="form-control" autocomplete="off" />
                </div>
                <!-- pwd = Mot de passe -->
                <div class="form-group">
                    <label for="pwd">Mot de passe :</label>
                    <input name="pwd" placeholder="Mot de passe" 
                           type="password" class="form-control"/>
                </div>
                <!-- BOUTON : Se connecter -----------------  -->
                <button type="submit" class="btn btn-success" name="seconnecter">
                    <span class="glyphicon glyphicon-log-in"></span>
                    Se connecter
                </button>

                <!-- DEUX lien supplémentaires en BAS (côte-à-côte) -->
                <p class="text-right">
                    <!-- LIEN vers: Mot de passe oublié -->
                    <a href="loginInitialiserPwd.php">Mot de passe Oublié</a>

                    <!-- LIEN vers: CREER un compte -->
                    &nbsp &nbsp
                    <a href="utilisateurNouveau.php">Créer un compte</a>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</HTML>