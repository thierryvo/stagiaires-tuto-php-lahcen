<?php 
    require_once('identifier.php');// Vérification LOGIN
    require_once('connexiondb.php');
    $pagefilieres = true;
    // BACK ------------------------------------------------------------------------------------------------------------------- BACK
    // ZONES du formulaire: CREER une nouvelle filiaire ----------------------------------------------------------------------- POST
    // formulaire ci-dessous en front
    if(
        isset($_POST['creerunenouvellefiliere']) AND
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
                // Redirection sur la liste des filières                
                RAZmessages();
                header('location:filieres.php');
            }else{
                // KO: erreur d insertion sql
                $message_erreur = "Une erreur SQL s'est produite lors de l'ajout d'une catégorie!";
                $_SESSION['message']['text'] = $message_erreur;
                $_SESSION['message']['type'] = "danger";                
            }
        }else{
            // KO: il manque une donnees
            $message_erreur = "Nom de la filière, niveau sont obligatoires!";
            $_SESSION['message']['text'] = $message_erreur;
            $_SESSION['message']['type'] = "danger";            
        }  
    }    


// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
// Gestion du compteur de PAGE pour: Effacer les messages
if($_SESSION['filiere_ajouter'] === 0){ RAZmessages(); }
$nb = $_SESSION['filiere_ajouter'];
$nb = $nb + 1;
$_SESSION['filiere_ajouter'] = $nb; 
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <?php include '../include/head.php' ?>
        <title>Nouvelle filière</title>
    </head>
    <body>
        <?php include("menu.php"); ?>        
        <div class="container py-2 margetop60">
                       
             <!-- BLOC (PANEL) SAISIE -->
             <div class="panel panel-primary">
                <div class="panel-heading">Veuillez saisir les données de la nouvelle filère</div>
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

                    <!-- FORMULAIRE de SAISIE d une filière -->                    
                    <form action="" method="post" class="form">
						<!-- nomFiliere : nom de la filière -->
                        <div class="form-group">                            
                            <label for="nomFiliere">Nom de la filière:</label>
                            <input name="nomFiliere" placeholder="Nom de la filière"
                                   type="text" class="form-control" />
                        </div>
                        <!-- niveau -->
                        <!-- SELECTEUR à partir d'une liste en dure -->
                        <div class="form-group">
                            <label for="niveau">Niveau:</label>
				            <select name="niveau" id="niveau" class="form-control">
                                <option value="Q">Qualification</option>
                                <option value="T">Technicien</option>
                                <option value="TS" selected>Technicien Spécialisé</option>
                                <option value="L">Licence</option>
                                <option value="M">Master</option> 
				            </select>
                        </div>
                        <!-- BOUTON ENREGISTRER -->
				        <button type="submit" class="btn btn-success" name="creerunenouvellefiliere">
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
    $_SESSION['filiere_ajouter'] = 0;
}