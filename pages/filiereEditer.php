<?php
require_once('identifier.php');// Vérification LOGIN
require_once('connexiondb.php');
$pagefilieres = true;
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES du formulaire: MODIFIER une filiaire ----------------------------------------------------------------------------- POST
// formulaire ci-dessous en front
if(
    isset($_POST['modifierunefiliere']) AND
    isset($_POST['idFiliere']) AND
    isset($_POST['nomFiliere']) AND
    isset($_POST['niveau'])){
    // donnees
    $idFiliere = $_POST['idFiliere'];
    $nomFiliere = trim(htmlspecialchars($_POST['nomFiliere']));
    $niveau = trim(htmlspecialchars($_POST['niveau']));

    if (!empty($nomFiliere) && !empty($niveau)) {
        // SQL: MODIFIER une filière ===================================================== (1)
        $sql="
        UPDATE filiere
        SET    nomFiliere=?,
               niveau=?
        WHERE  idFiliere=?
        ";
        $req = $pdo->prepare($sql);  
        // SQL sous try-catch
        try {
            //code...
            $req->execute([
                $nomFiliere,
                $niveau, 
                $idFiliere
            ]);
            // Vérifier l'update en base de donnees
            if($req->rowCount()!=0){
                // ok
                RAZmessages();
                //
                // Redirection sur la liste des filières
                header('location:filieres.php');                                
            }else{
                // zéro Mise a jour
                $mess = "AUCUNE mise à jour BdD a été Nécessaire.";
                $_SESSION['message']['text'] = $mess;
                $_SESSION['message']['type'] = "success";        
                $url="location:filiereEditer.php?idFiliere=".(string)$idFiliere;
                header($url);                     
            }//FINSI rowcount
        } catch (Exception $e ) {
            // ARRET exécution par die avec un message
            $mess = "Une erreur SQL s'est produite lors de la modification d'une Filière, sqlmsg e = " .$e->getMessage();
            $_SESSION['message']['text'] = $mess;
            $_SESSION['message']['type'] = "danger";            
        }//FIN try catch
    } else {
        // ko: il manque une donnée
        $message_erreur = "Nom filière, niveau sont obligatoires.";
        $_SESSION['message']['text'] = $message_erreur;
        $_SESSION['message']['type'] = "danger";        
    }//FINSI zone manquante         
}//FINSI isset Editer une filière
    
    
// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
// Gestion du compteur de PAGE pour: Effacer les messages
if($_SESSION['filiere_modifier'] === 0){ RAZmessages(); }
$nb = $_SESSION['filiere_modifier'];
$nb = $nb + 1;
$_SESSION['filiere_modifier'] = $nb;
// ID Filiere ----------------------------------------------------------------------------------------------------------- ID GET
if (isset($_GET['idFiliere'])) {
    $idFiliere = htmlspecialchars($_GET['idFiliere']);
    //
    // SQL pour: récupérer la filière à modifier => A: afficher dans le formulaire
    $sql="
    SELECT * 
    FROM filiere
    WHERE idFiliere=?
    ";        
    $req = $pdo->prepare($sql);
    $req->execute([$idFiliere]);
    $filiere = $req->fetch(PDO::FETCH_ASSOC);
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <?php include '../include/head.php' ?>
        <title>Edition d'une filière</title>        
    </head>
    <body>
        <?php include("menu.php"); ?>        
        <div class="container py-2 margetop60">
                       
            <!-- BLOC (PANEL) MODIFIER -->
             <div class="panel panel-primary margetop60">
                <div class="panel-heading">Edition de la filière :</div>
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

                    <!-- FORMULAIRE de MODIFICATION d une filière ----------------------------------------------- -->  
                    <form action="" method="post" class="form">
                        <!-- ID caché -->
                        <input type="hidden" class="form-control" name="idFiliere" value="<?= $filiere['idFiliere'] ?>" />             
                        <!-- nomFiliere : nom de la filière -->
                        <div class="form-group">
                            <label for="nomFiliere">Nom de la filière:</label>
                            <input name="nomFiliere"  placeholder="Nom de la filière"
                                   value="<?= $filiere['nomFiliere'] ?>"
                                   type="text" class="form-control" />
                        </div>
                        <!-- niveau -->
                        <!-- SELECTEUR à partir d'une liste en dure -->
                        <div class="form-group">
                            <label for="niveau">Niveau:</label>
				            <select name="niveau" class="form-control" id="niveau">
                                <option value="Q" <?= $filiere['niveau']=='Q'?"selected":'' ?>>Qualification</option>
                                <option value="T" <?= $filiere['niveau']=='T'?"selected":'' ?>>Technicien</option>
                                <option value="TS"<?= $filiere['niveau']=='TS'?"selected":'' ?>>Technicien Spécialisé</option>
                                <option value="L" <?= $filiere['niveau']=='L'?"selected":'' ?>>Licence</option>
                                <option value="M" <?= $filiere['niveau']=='M'?"selected":'' ?>>Master</option> 
				            </select>
                        </div>
                        <!-- BOUTON MODIFIER -->
				        <button type="submit" class="btn btn-success" name="modifierunefiliere">
                            <span class="glyphicon glyphicon-save"></span>
                            MODIFIER
                        </button> 
                      
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
    $_SESSION['filiere_modifier'] = 0;
}