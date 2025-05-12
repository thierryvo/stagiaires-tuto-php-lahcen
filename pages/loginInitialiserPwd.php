<?php
require_once('connexiondb.php');
require_once('../include//les_fonctions/fonctions.php');
require_once('_mailer.php');
$_SESSION['message']['text'] = null;
$_SESSION['message']['type'] = null;
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES du formulaire: email de récupération ----------------------------------------------------------------------------- POST
// formulaire ci-dessous en front
if(isset($_POST['email']) ){
    // donnees
    $email = trim(htmlspecialchars($_POST['email']));

    // lecture user: via son email
    $user = rechercher_user_par_email($email);
    if ($user) {
        // ok:
        // Mettre le mot de passe à jour avec en DURE 4 zéros: '0000'
        $iduser = $user['iduser'];
        $pwd="0000";// 4 zéros en DURE
        $password = MD5($pwd);
        $sql="
        UPDATE utilisateur 
        SET    pwd=?
        WHERE  iduser=?
        ";
        $req = $pdo->prepare($sql);
        $req->execute([
            $password,
            $iduser
        ]);
        //
        // recupérer le token        
        $sql="
        SELECT token FROM utilisateur_token WHERE iduser=? AND email=?";
        $req = $pdo->prepare($sql);
        $req->execute([
            $iduser,
            $email
        ]);
        $tabtoken = $req->fetch(PDO::FETCH_ASSOC);
        $token = $tabtoken['token'];
        //
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
        //
        //
        // Préparation données pour un email 
        $to = $email; // Destinataire (moi): thi.voz@gmail.com
        $objet = "Initialisation de votre mot de passe";
        $content = "Votre nouveau mot de passe est 0000 (quatre zéros), veuillez le modifier à la prochine ouverture de session";
        $entetes = "From: GesStag" . "\r\n" . "CC: site.gestion.des.stagiares.2025@gmail.com";
        //
        // ENVOIE MAIL:
        $status = reset_pass_perdu($email, $token, $iduser); // par envoie d'un Email


                            // mail(               ne fonctione pas
                            //     $to, 
                            //     $objet, 
                            //     $content, 
                            //     $entetes
                            // );
        // ok
        $mess = "Un message contenant votre nouveau mot de passe (0000) a été envoyé sur votre adresse Email.";
        $_SESSION['message']['text'] = $mess;
        $_SESSION['message']['type'] = "success";
    } else {
        // ko: cet user n'existe pas
        $mess = "Cet email est incorrect!!!";
        $_SESSION['message']['text'] = $mess;
        $_SESSION['message']['type'] = "danger";    
    }//FINSI user existe
}//FINSI isset email



// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
?>
<!DOCTYPE HTML>
<html>
<head>
    <?php include '../include/head.php' ?>
    <title>Initiliser votre mot de passe</title>
</head>
<body>
<div class="container col-md-6 col-md-offset-3">
    <br>
    <!-- BLOC (PANEL) Init pwd -->
    <div class="panel panel-primary ">
        <div class="panel-heading">Initiliser votre mot de passe</div>
        <div class="panel-body">
            <!-- FORMULAIRE de SAISIE --- email de récupération -->
            <form method="post" class="form">
                <!-- email -->
                <div class="form-group">
                <label for="email" class="control-label">Veuillez saisir votre email de récuperation</label>
                <input type="email" name="email" class="form-control"/>
                </div>
                <!-- BOUTON : Initialiser pwd -->
                <button type="submit" class="btn btn-success">Initialiser le mot de passe</button>
            </form>


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
            //
            if ($letype == "danger") {                
                header("refresh:3;url=loginInitialiserPwd.php");
                exit();
            } else if ($letype == "success") {                
                header("refresh:2;url=login.php");
                exit();
            }


            }
            ?>
        </div>
    </div>

</div>
</body>
</html>