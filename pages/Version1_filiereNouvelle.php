<?php 
    require_once('identifier.php');// Vérification LOGIN

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
        <tit>Nouvelle filière</tit>
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
                    <!-- VOIR si la solution du Projet eCommer n est pas mieux adaptéééééééééééééééééééééééééééééééééééééééééééééééééééééé -->  
                    <form method="post" action="filiereInsert.php" class="form">
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
				        <button type="submit" class="btn btn-success">
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