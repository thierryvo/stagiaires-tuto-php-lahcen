<?php
require_once('identifier.php');// Vérification LOGIN
require_once('connexiondb.php');
$pagestagiaires = true;
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES du formulaire: MODIFIER une filiaire ----------------------------------------------------------------------------- POST
// formulaire ci-dessous en front
if(
    isset($_POST['modifierunstagiaire']) AND
    isset($_POST['idStagiaire']) AND
    isset($_POST['idFiliere']) AND
    isset($_POST['nom']) AND
    isset($_POST['prenom']) AND
    isset($_POST['civilite']) AND
    isset($_FILES['imagestagiaire']) ){
    // donnees
    $idStagiaire = $_POST['idStagiaire'];
    $idFiliere = $_POST['idFiliere'];
    $nom = trim(htmlspecialchars($_POST['nom']));
    $prenom = trim(htmlspecialchars($_POST['prenom']));
    $civilite = trim(htmlspecialchars($_POST['civilite']));
    $dateISO = date('YmdHis'); // date format aaaammjjhhmmss ou date('Y-m-d H:i:s') = aaaa-mm-jj hh:mm:ss
    $image_nom = $_POST['nom_cacher'];       // ancien nom stocké en base de données
    $image_chemin = $_POST['chemin_cacher']; // ancine chemin stocké en base de données    
    //
    // images -------------------------------------------------------------------------------------------- images
    // FAIRE le 'upload' de l'image stagiaire                   En MODIFICATION: on repart de zéro - MODIFICATION
    if(
        !empty($_FILES['imagestagiaire']['name']) AND 
        !empty($_FILES['imagestagiaire']['size']) AND 
        $_POST["MAX_FILE_SIZE"] >= $_FILES["imagestagiaire"]["size"]){
        // gestion dossier images
        if(!is_dir("../assets/images")){
            // CREER le dossier
            mkdir("../assets/images");
        }
        // PREREQUIS *******
        // ON Supprime toutes les images du stagiaire: pour les re-créer   voir exemple dans: mini-projet
        // CAR: on ne saurra jamais qui est qui dans les timeStamp !
        // sur le DOSSIER du SERVEUR------------------------------------
        unlink($image_chemin); // suppression
        //
        //
        // gestion du nom de l'extension image
        $image_nom = null;
        $image_chemin = null;            
        $extension=pathinfo($_FILES["imagestagiaire"]["name"], PATHINFO_EXTENSION);//extrait extension
        if(in_array($extension, ["jpg", "jpeg", "png"])){
            // ok
            // gestion du upload lui-même = déplacer l image vers le dossier:  assets/images
            $path_image = "../assets/images/".$dateISO.$_FILES["imagestagiaire"]["name"];
            $upload = move_uploaded_file(
                $_FILES["imagestagiaire"]["tmp_name"],
                $path_image
            );
            if($upload){
                // ok: l image a bien été uploadé dans le dossier image
                $image_nom = $_FILES["imagestagiaire"]["name"];
                $image_chemin = $path_image;
                // --------------------------------------------------------------------------------------- images
            }else{
                // ko: l image n'a pas été uploadé !
                // nom & chemin image initialisé à null
            }                
        }else{
            // ko: mauvaise extension
            // nom & chemin image initialisé à null
            $message_erreur = "KO Mauvaise extension de l'image, nom & chemin image à null.";
            $_SESSION['message']['text'] = $message_erreur;
            $_SESSION['message']['type'] = "danger";                  
        }
    }else{
        // ko: pas d'image saisie
        // nom & chemin image initialisé à null
    } 

    if (!empty($idFiliere) && !empty($idStagiaire) && !empty($nom) && !empty($prenom)) {
        // SQL: MODIFIER un Stagiaire ======================================================================= (1)
        $sql="
        UPDATE stagiaire
        SET    idFiliere=?,
               nom=?,
               prenom=?,
               civilite=?,
               photoNom=?,
               photoChemin=?
        WHERE  idStagiaire=?
        ";
        $req = $pdo->prepare($sql);
        // sql sous try catch:
        try {        
            $req->execute([
                $idFiliere,
                $nom,
                $prenom,
                $civilite,
                $image_nom, 
                $image_chemin,
                $idStagiaire
            ]);
            // Vérifier l'update en base de donnees
            if($req->rowCount()!=0){
                // ok
                RAZmessages();
                //
                // Redirection sur la liste des filières
                header('location:stagiaires.php');
                // ============================================================================================== (1)
            }else{
                // zéro Mise a jour
                $message_erreur = "AUCUNE mise à jour BdD a été Nécessaire.";
                $_SESSION['message']['text'] = $message_erreur;
                $_SESSION['message']['type'] = "success";       
                $url="location:stagiaireEditer.php?idStagiaire=".(string)$idStagiaire;
                header($url);                     
            }            

        } catch (Exception $e) {    
            // ARRET exécution par die avec un message
            $mess = "Une erreur SQL s'est produite lors de la modification d'un Stagiaire, sqlmsg e = " .$e->getMessage();
            $_SESSION['message']['text'] = $mess;
            $_SESSION['message']['type'] = "danger";
        }//FIN try catch

    } else {
        // ko: il manque une donnée
        $message_erreur = "ID Stagiaire, ID Filière, Nom, prenom sont obligatoires.";
        $_SESSION['message']['text'] = $message_erreur;
        $_SESSION['message']['type'] = "danger";

        $url="location:stagiaireEditer.php?idStagiaire=".(string)$idStagiaire;
        header($url);
    }            
}
    
    
// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
// Gestion du compteur de PAGE pour: Effacer les messages
if($_SESSION['stagiaire_modifier'] === 0){ RAZmessages(); }
$nb = $_SESSION['stagiaire_modifier'];
$nb = $nb + 1;
$_SESSION['stagiaire_modifier'] = $nb;
// ID Stagiaire --------------------------------------------------------------------------------------------------------- ID GET
if (isset($_GET['idStagiaire'])) {
    $idStagiaire = htmlspecialchars($_GET['idStagiaire']);
    //
    // SQL pour: récupérer la filière à modifier => A: afficher dans le formulaire
    $sql="
    SELECT * 
    FROM stagiaire
    WHERE idStagiaire=?
    ";        
    $req = $pdo->prepare($sql);
    $req->execute([$idStagiaire]);
    $stagiaire = $req->fetch(PDO::FETCH_ASSOC);
    $civilite = $stagiaire['civilite'];
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
                <div class="panel-heading">Edition d'un Stagiaire :</div>
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

                    <!-- FORMULAIRE de MODIFICATION d un Stagiaire ----------------------------------------------- -->  
                    <form action="stagiaireEditer" method="post" class="form" enctype="multipart/form-data">
                        <!-- ID caché           + nom + chemin -->
                        <input type="hidden" name="idStagiaire" value="<?php echo $stagiaire['idStagiaire'] ?>" />
                        <input type="hidden" name="nom_cacher" value="<?php echo $stagiaire['photoNom'] ?>" />
                        <input type="hidden" name="chemin_cacher" value="<?php echo $stagiaire['photoChemin'] ?>" />
                        <!-- taille Maximum de l image autorisé, en varaiable caché -->
                        <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" Value="1000000" />                          
                        
                        <!-- nom -->
                        <div class="form-group">
                            <label for="nom">Nom du stagiaire:</label>
                            <input name="nom"  placeholder="Nom du stagiaire"
                                   value="<?= $stagiaire['nom'] ?>"
                                   type="text" class="form-control" />
                        </div>
                        <!-- prenom -->
                        <div class="form-group">
                            <label for="prenom">Prenom du stagiaire:</label>
                            <input name="prenom"  placeholder="Prenom du stagiaire"
                                   value="<?= $stagiaire['prenom'] ?>"
                                   type="text" class="form-control" />
                        </div>       
                        <!-- civilite -->
                        <div class="form-group">
                        <label for="civilite">Civilité :</label>
                        <div class="radio">
                            <label><input type="radio" name="civilite" value="F"
                                          <?= $civilite=="F"?"checked":"" ?> checked/>F pour Femme</label>
                            <br/>
                            <label><input type="radio" name="civilite" value="M"
                                          <?= $civilite=="M"?"checked":"" ?>/>M pour Masculin</label>
                            <br/>
                        </div>
                        </div>
                        <!-- image -->
                        <label class="form-label">Image Stockée</label>
                        <img width="25" class="img img-fluid" src="<?= $stagiaire['photoChemin'] ?>" alt="<?= $stagiaire['photoNom'] ?>" />
                        <br>
                        <!-- imagestagiaire  ****** de type file  -->
                        <label for="imagestagiaire" class="form-label">Nouvelle image</label>
                        <input type="file" class="form-control" name="imagestagiaire" value="<?= $stagiaire['photoNom'] ?>" />
                        <br>                        
                        
                        <!-- idFiliere    FK stagiaire vers filiere -->
                        <!-- SELECTEUR de la filière: un Stagiaire doit appartenir à un Filière -->
                        <div class="form-group">
                        <label for="idFiliere" class="form-label">Filière</label>
                        <select name="idFiliere" id="idFiliere" class="form-control">
                            <option value="">Choisir une filière</option>
                            <?php                                 
                            // On commence par récupérer toutes les filières
                            $filieres = $pdo->query('SELECT * FROM filiere')->fetchAll(PDO::FETCH_ASSOC);            
                            //
                            // toujours tester SI ce n'est pas vide & si c'est bien un tableau
                            if(!empty($filieres) && is_array($filieres)){
                                // BOUCLE sur le tableau des filieres
                                foreach ($filieres as $key => $value) {
                                    ?>
                                    <option value="<?= $value['idFiliere'] ?>"
                                            <?= $value['idFiliere'] == $stagiaire['idFiliere']?"selected":"" ?> >
                                        <?= $value['nomFiliere'] ." - (id=". $value['idFiliere'] .")" ?>
                                    </option>
                                    <?php
                                }
                                // FIN BOUCLE
                            }
                            ?>
                        </select>
                        </div>
                        <!-- BOUTON MODIFIER -->
				        <button type="submit" class="btn btn-success" name="modifierunstagiaire">
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
    $_SESSION['stagiaire_modifier'] = 0;
}