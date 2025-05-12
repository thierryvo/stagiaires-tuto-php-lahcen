<?php
require_once('identifier.php');// Vérification LOGIN
require_once('connexiondb.php');
$pageutilisateurs = true;
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES du formulaire: MODIFIER un User ---------------------------------------------------------------------------------- POST
// formulaire ci-dessous en front
if(
    isset($_POST['modifierunutilisateur']) AND
    isset($_POST['iduser']) AND
    isset($_POST['login']) AND
    isset($_POST['email']) ){
    // donnees
    $iduser = $_POST['iduser'];    
    $login = trim(htmlspecialchars($_POST['login']));
    $email = trim(htmlspecialchars($_POST['email']));
    
    if (!empty($iduser) && !empty($login) && !empty($email)) {
        // SQL: MODIFIER un Utilisateur ======================================================================= (1)
        $sql="
        UPDATE utilisateur
        SET    login=?,
               email=?               
        WHERE  iduser=?
        ";
        $req = $pdo->prepare($sql);
        // sql sous try catch:
        try {        
            $req->execute([
                $login,
                $email,                
                $iduser
            ]);
            // Vérifier l'update en base de donnees
            if($req->rowCount()!=0){
                // ok
                RAZmessages();
                //
                // Redirection sur la liste des filières
                header('location:utilisateurs.php');
                // =========================================================================================== (1)
            }else{
                // zéro Mise a jour
                $message_erreur = "AUCUNE mise à jour BdD a été Nécessaire.";
                $_SESSION['message']['text'] = $message_erreur;
                $_SESSION['message']['type'] = "success";       
                $url="location:utilisateurEditer.php?iduser=".(string)$iduser;
                header($url);                     
            }            

        } catch (Exception $e) {    
            // ARRET exécution par die avec un message
            $mess = "Une erreur SQL s'est produite lors de la modification de l'Utilisateur, sqlmsg e = " .$e->getMessage();
            $_SESSION['message']['text'] = $mess;
            $_SESSION['message']['type'] = "danger";
        }//FIN try catch

    } else {
        // ko: il manque une donnée
        $message_erreur = "ID User, login email sont obligatoires.";
        $_SESSION['message']['text'] = $message_erreur;
        $_SESSION['message']['type'] = "danger";

        $url="location:utilisateurEditer.php?iduser=".(string)$iduser;
        header($url);
    }            
}
    
    
// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
// Gestion du compteur de PAGE pour: Effacer les messages
if($_SESSION['utilisateur_modifier'] === 0){ RAZmessages(); }
$nb = $_SESSION['utilisateur_modifier'];
$nb = $nb + 1;
$_SESSION['utilisateur_modifier'] = $nb;
// ID User -------------------------------------------------------------------------------------------------------------- ID GET
if (isset($_GET['iduser'])) {
    $iduser = htmlspecialchars($_GET['iduser']);
    //
    // SQL pour: récupérer le User à modifier => A: afficher dans le formulaire
    $sql="
    SELECT * 
    FROM utilisateur
    WHERE iduser=?
    ";        
    $req = $pdo->prepare($sql);
    $req->execute([$iduser]);
    $user = $req->fetch(PDO::FETCH_ASSOC);
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <?php include '../include/head.php' ?>
        <title>Edition d'un utilisateur</title>        
    </head>
    <body>
        <?php include("menu.php"); ?>        
        <div class="container py-2 margetop60">
                       
            <!-- BLOC (PANEL) MODIFIER -->
             <div class="panel panel-primary">
                <div class="panel-heading">Edition d'un utilisateur :</div>
                <div class="panel-body">

                    <!-- SI Nécessaire: Message ERREUR                             -->
                    <!-- Afficher les messages (session) juste en dessous du titre -->
                    <?php
                    $letype = $_SESSION['message']['type']; // danger ou success
                    if(!empty($_SESSION['message']['text'])){
                        ?>
                        <!-- class  alert alert-danger      ou bien alert alert-success -->
                        <div class="alert alert-<?= $letype=="danger"?"danger":"success" ?>" role="alert">
                            <?= $_SESSION['message']['text'] ?>
                        </div>
                    <?php
                    }
                    ?>                

                    <!-- FORMULAIRE de MODIFICATION d un User -------------------------------------------------- -->  
                    <form action="utilisateurEditer" method="post" class="form">
                        <!-- ID caché           + nom + chemin -->
                        <input type="hidden" name="iduser" value="<?= $user['iduser'] ?>" />
                                                
                        <!-- login -->
                        <div class="form-group">
                            <label for="login">Login :</label>
                            <input name="login"  placeholder="Login"
                                   value="<?= $user['login'] ?>"
                                   type="text" class="form-control" />
                        </div>
                        <!-- email -->
                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input name="email"  placeholder="Email"
                                   value="<?= $user['email'] ?>"
                                   type="email" class="form-control" />
                        </div>       
                                         
                        <!-- BOUTON MODIFIER -->
				        <button type="submit" class="btn btn-success" name="modifierunutilisateur">
                            <span class="glyphicon glyphicon-save"></span>
                            MODIFIER
                        </button> 

                        <!-- UN lien supplémentaires en BAS  -->
                        <p class="text-center">
                            <!-- LIEN vers: CHANGER Mot de passe -->
                            <a href="loginChangerPwd.php">Changer le mot de passe</a>                            
                        </p>                        
                      
					</form>
                </div>
            </div>   
        </div>      
    </body>
</HTML>
<?php
    }
    // FIN SI GET ID ---------------------------------------------------------------------------------------- GET ID
//
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
    $_SESSION['utilisateur_modifier'] = 0;
}