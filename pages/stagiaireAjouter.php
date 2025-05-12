<?php 
    require_once('identifier.php');// Vérification LOGIN
    require_once('connexiondb.php');
    $pagestagiaires = true;
    // BACK ------------------------------------------------------------------------------------------------------------------- BACK
    // ZONES du formulaire: CREER une nouveau Stagiaire ----------------------------------------------------------------------- POST
    // formulaire ci-dessous en front
    if(
        isset($_POST['creerunnouveaustagiaire']) AND        
        isset($_POST['nom']) AND
        isset($_POST['prenom']) AND
        isset($_POST['civilite']) AND
        isset($_POST['idFiliere']) AND  
        isset($_FILES['imagestagiaire']) ){
        // donnees
        $nom = trim(htmlspecialchars($_POST['nom']));
        $prenom = trim(htmlspecialchars($_POST['prenom']));
        $civilite = trim(htmlspecialchars($_POST['civilite']));
        $idFiliere = $_POST['idFiliere'];
        $dateISO = date('YmdHis'); // date format aaaammjjhhmmss ou date('Y-m-d H:i:s') = aaaa-mm-jj hh:mm:ss
        $image_nom = null;
        $image_chemin = null;
        //
        // images -------------------------------------------------------------------------------------------- images
        // FAIRE le 'upload' de l'image stagiaire
        if(
            !empty($_FILES['imagestagiaire']['name']) AND 
            !empty($_FILES['imagestagiaire']['size']) AND 
            $_POST["MAX_FILE_SIZE"] >= $_FILES["imagestagiaire"]["size"]){
            // gestion dossier images
            if(!is_dir("../assets/images")){
                // CREER le dossier
                mkdir("../assets/images");
            }
            // gestion du nom de l'extension image
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
                    $message_erreur = "KO image n a pas été uploader, pb imagestagiaire!";
                    $_SESSION['message']['text'] = $message_erreur;
                    $_SESSION['message']['type'] = "danger";                      
                }                
            }else{
                // ko: mauvaise extension
                // nom & chemin image initialisé à null
                $message_erreur = "KO Mauvaise extension de l'image, nom & chemin image à null.";
                $_SESSION['message']['text'] = $message_erreur;
                $_SESSION['message']['type'] = "danger";                  
            }
        }else{
            // ko: pas d'image saisie - NON BLOQUANT
            // nom & chemin image initialisé à null
        }
        
        if(!empty($nom) && !empty($prenom) && !empty($idFiliere)){
            // SQL pour insérer un stagiaire ================================================== (1)
            $sql="
            INSERT INTO stagiaire
            (idFiliere,nom,prenom,civilite,photoNom,photoChemin) 
            values(?,?,?,?,?,?)
            ";
            //
            $req=$pdo->prepare($sql);
            $req->execute([
                $idFiliere,
                $nom,
                $prenom,
                $civilite,
                $image_nom,
                $image_chemin
            ]);
            //
            // Vérifier l insertion en base de donnees
            if($req->rowCount()!=0){
                // ok
                // Redirection sur la liste des stagiaires
                RAZmessages();
                header('location:stagiaires.php');
                // ============================================================================ (1)            
            }else{
                // KO: erreur d insertion sql
                $message_erreur = "Une erreur SQL s'est produite lors de l'ajout d'un stagiaire!";
                $_SESSION['message']['text'] = $message_erreur;
                $_SESSION['message']['type'] = "danger";                
            }
        }else{
            // KO: il manque une donnees
            $message_erreur = "Nom, prénom, ID de la filière sont obligatoires!";
            $_SESSION['message']['text'] = $message_erreur;
            $_SESSION['message']['type'] = "danger";            
        }  
    } 


// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
// Gestion du compteur de PAGE pour: Effacer les messages
if($_SESSION['stagiaire_ajouter'] === 0){ RAZmessages(); }
$nb = $_SESSION['stagiaire_ajouter'];
$nb = $nb + 1;
$_SESSION['stagiaire_ajouter'] = $nb; 
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <?php include '../include/head.php' ?>
        <title>Nouveau stagiaire</title>
    </head>
    <body>
        <?php include("menu.php"); ?>        
        <div class="container py-2 margetop60">
                       
             <!-- BLOC (PANEL) SAISIE -->
             <div class="panel panel-primary">
                <div class="panel-heading">Veuillez saisir les données du nouveau stagaiaire</div>
                <div class="panel-body">

                    <!-- SI Nécessaire: Message ERREUR                             -->
                    <!-- Afficher les messages (session) juste en dessous du titre -->
                    <?php
                    if(!empty($_SESSION['message']['text'])){
                        ?>
                        <div class="alert alert-danger" role="alert">                    
                            <?= $_SESSION['message']['text'] ?>
                        </div>
                    <?php
                    }
                    ?>                

                    <!-- FORMULAIRE de SAISIE d un stagiaire -->                    
                    <form action="stagiaireAjouter" method="post" class="form" enctype="multipart/form-data">
                        <!-- taille Maximum de l image autorisé, en varaiable caché -->
                        <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" Value="1000000" />                           

						<!-- nom -->
                        <div class="form-group">                            
                            <label for="nom">Nom du stagiaire:</label>
                            <input name="nom" placeholder="Nom du stagiaire"
                                   type="text" class="form-control" />
                        </div>   
						<!-- prenom -->
                        <div class="form-group">                            
                            <label for="prenom">Prénom du stagiaire:</label>
                            <input name="prenom" placeholder="Prenom du stagiaire"
                                   type="text" class="form-control" />
                        </div>
                        <!-- Civilité = F ou M -->
                        <div class="form-group">
                            <label for="civilite">Civilité :</label>
                            <div class="radio">
                                <label><input type="radio" name="civilite" value="F" checked/> F  pour Femme </label><br>
                                <label><input type="radio" name="civilite" value="M"/> M  pour Masculin </label>
                            </div>
                        </div>

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
                                    <option value="<?= $value['idFiliere'] ?>">
                                        <?= $value['nomFiliere'] ." - (id=". $value['idFiliere'] .")" ?>
                                    </option>
                                    <?php                        
                                }
                                // FIN BOUCLE
                            }
                            ?>
                        </select>
                        </div>        

                        <!-- imagestagiaire -->
                        <div class="form-group">
                            <label for="imagestagiaire" class="form-label">Image</label>
                            <input type="file" class="form-control" name="imagestagiaire" />
                        </div>                         

                        <!-- BOUTON ENREGISTRER -->
				        <button type="submit" class="btn btn-success" name="creerunnouveaustagiaire">
                            <span class="glyphicon glyphicon-save"></span>
                            ENREGISTRER
                        </button>                       
					</form>

                </div>
            </div>
            
        </div>      
    </body>
</HTML>
<?php
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
    $_SESSION['stagiaire_ajouter'] = 0;
}