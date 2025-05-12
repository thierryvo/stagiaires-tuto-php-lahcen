<?php
    require_once('identifier.php'); // Vérification LOGIN
    require_once("connexiondb.php");
    $pagefilieres = true;
    RAZmessages();
    // BACK ------------------------------------------------------------------------------------------------------------------- BACK
    // ZONE INPUT nomf: Recherche par, nom de la filière  like %% --------------------------------------------------------- nomf GET
    $nomf="";
    if(isset($_GET['nomF'])){
        // nom de la filière
        $nomf = $_GET['nomF'];
    }
    // ZONE SELECT niveau: Recherche par, niveau like %% ---------------------------------------------------------------- niveau GET
    if(isset($_GET['niveau'])){
        // nom de la filière
        $niveau = $_GET['niveau'];
    }else{
        $niveau="all";
    }

    // PAGINATION                            6 par page
    $size=isset($_GET['size'])?$_GET['size']:6;
    $page=isset($_GET['page'])?$_GET['page']:1;
    $offset=($page-1)*$size;
    
    // PREPARATION requete sql en fonction des critères de Recherches ci-dessus
    if($niveau=="all"){
        // TOUS les niveaux
        // like %nomf%
        $requete="
            SELECT * 
            FROM   filiere as f, niveau as n
            WHERE  f.niveau = n.codeNiveau 
            AND    f.nomFiliere like '%$nomf%'
            limit $size
            offset $offset";
        
        $requeteCount="select count(*) countF from filiere
                where nomFiliere like '%$nomf%'";
    }else{
        // SINON
        // Un niveau se sélectionné
        // like %nomf%  AND niveau=niveau
         $requete="
            SELECT * 
            FROM   filiere as f, niveau as n
            WHERE  f.niveau = n.codeNiveau 
            AND    f.nomFiliere like '%$nomf%'
            AND    f.niveau='$niveau'
            limit $size
            offset $offset";
        
        $requeteCount="select count(*) countF from filiere
                where nomFiliere like '%$nomf%'
                and niveau='$niveau'";
    }
    //
    // Lecture des filières --------------
    $req = $pdo->prepare($requete);
    $req->execute();
    $filieres = $req->fetchAll(PDO::FETCH_ASSOC);
    //
    // COMPTAFE pour PAGINATION
    $req=$pdo->query($requeteCount);
    $tabCount=$req->fetch();
    $nbrFiliere=$tabCount['countF'];
    $reste=$nbrFiliere % $size;   // % operateur modulo: le reste de la division 
                                 //euclidienne de $nbrFiliere par $size
    if($reste===0) //$nbrFiliere est un multiple de $size
        $nbrPage=$nbrFiliere/$size;   
    else
        $nbrPage=floor($nbrFiliere/$size)+1;  // floor : la partie entière d'un nombre décimal


// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
?>
<!DOCTYPE HTML>
<HTML>
    <head>
    <?php include '../include/head.php' ?>
    <title>Gestion des filières</title>
    </head>
    <body>
        <?php include("menu.php"); ?>
        <div class="container py-2 margetop60">
            <!-- BLOC (PANEL) CRITERES de RECHERCHE -->
            <div class="panel panel-success">
				<div class="panel-heading">Rechercher des filières</div>
				<div class="panel-body">
                    
					<!-- FORMULAIRE de SAISIE des FILTRES     nomf nom filière  + niveau +++ CREATION Nouvelle Filière -->
					<form method="get" action="filieres.php" class="form-inline">
                        <!-- nomf : nom de la filière -->
						<div class="form-group">
                            <input name="nomF" placeholder="Nom de la filière"
                                   value="<?= $nomf ?>"
                                   class="form-control" type="text" />                                   
                        </div>
                        <!-- niveau -->
                        <!-- SELECTEUR à partir d'une liste en dure ----- onchange soumet ce form ---          onchange -->
                        <label for="niveau">Niveau:</label>
			            <select name="niveau" class="form-control" id="niveau"
                                onchange="this.form.submit()">
                            <option value="all" <?= $niveau==="all"?"selected":"" ?>>Tous les niveaux</option>
                            <option value="Q"   <?= $niveau==="Q"?"selected":"" ?>>Qualification</option>
                            <option value="T"   <?= $niveau==="T"?"selected":"" ?>>Technicien</option>
                            <option value="TS"  <?= $niveau==="TS"?"selected":"" ?>>Technicien Spécialisé</option>
                            <option value="L"   <?= $niveau==="L"?"selected":"" ?>>Licence</option>
                            <option value="M"   <?= $niveau==="M"?"selected":"" ?>>Master</option> 
			            </select>
			            <!-- BOUTON Chercher -->
				        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-search"></span>
                            Chercher...
                        </button> 
                        
                        <!-- CREER une NOUVELLE FILIERE ----------------------------------------------- -->
                        &nbsp;&nbsp;
                       	<?php if ($_SESSION['user']['role']=='ADMIN') {?>
                            <a href="filiereAjouter.php">                            
                                <span class="glyphicon glyphicon-plus"></span>                                
                                Nouvelle filière                                
                            </a>                            
                        <?php } ?>                         
					</form>

				</div>
			</div>
            


            <!-- BLOC (PANEL) LISTE des filières -->
            <div class="panel panel-primary">
                <div class="panel-heading">Liste des filières (<?php echo $nbrFiliere ?> Filières)</div>
                <div class="panel-body">                    
                    <?php
                    // =========== TABLE pour afficher les filières ============
                    // toujours tester SI ce n'est pas vide & si c'est bien un tableau 
                    if(!empty($filieres) && is_array($filieres)){
                    ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id filière</th>
                                <th>Nom filière</th>
                                <th>Niveau</th>
                                <!-- ACTIONS réservé à ADMIN -->
                                <?php if ($_SESSION['user']['role']== 'ADMIN') {?>
                                	<th>Actions</th>
                                <?php }?>
                            </tr>
                        </thead>                        
                        <tbody>
                            <!-- BOUCLE sur la liste des filieres --------------------------------------------------------------------------- DO -->                            
                            <?php                
                                // BOUCLE sur le tableau articles
                                foreach ($filieres as $item) {
                                    ?>
                                    <tr>
                                        <td><?= $item['idFiliere'] ?> </td>
                                        <td><?= $item['nomFiliere'] ?> </td>
                                        <td><?= $item['libelleNiveau'] ?> </td> 
                                        <!-- ACTIONS    réservé au rôle: ADMIN -->
                                        <?php if ($_SESSION['user']['role']== 'ADMIN') {?>
                                            <td>
                                                <!-- Editer la filière (modifier) -->
                                                <a href="filiereEditer.php?idFiliere=<?= $item['idFiliere'] ?>" title="Editer">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                                &nbsp;
                                                <!-- Supprimer la filière -- Avec: Fenêtre de confirmation -->
                                                <a onclick="return confirm('Etes vous sur de vouloir supprimer la filière')"
                                                    href="filiereSupprimer.php?idFiliere=<?= $item['idFiliere'] ?>" title="Supprimer">
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </td>
                                        <?php }?>
                                        
                                    </tr>
                                    <?php                        
                                }
                                // FIN BOUCLE                        
                            ?>
                       </tbody>
                    </table>
                    <?php
                    }else{
                        echo "La liste est vide!";
                    }
                    ?>


                <!-- PAGINATION -->
                <div>                    
                    <ul class="pagination">
                        <?php for($i=1;$i<=$nbrPage;$i++){ ?>
                            <li class="<?php if($i==$page) echo 'active' ?>"> 
                                <!-- ATTENTION: url doit contenir, page, nomF, niveau -->
                                <a href="filieres.php?page=<?php echo $i;?>&nomF=<?php echo $nomf ?>&niveau=<?php echo $niveau ?>">
                                    <?php echo $i; ?>
                                </a> 
                             </li>
                        <?php } ?>
                    </ul>
                </div>

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
    $_SESSION['filiere_modifier'] = 0;
}