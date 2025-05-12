<?php
    require_once('identifier.php');// Vérification LOGIN
    require_once("connexiondb.php");
    $pagestagiaires = true;
    RAZmessages();
    // BACK ------------------------------------------------------------------------------------------------------------------- BACK
    // ZONE INPUT nomf: Recherche par, nom de la filière  like %% ---------------------------------------------------- nomPrenom GET
    $nomPrenom="";
    if(isset($_GET['nomPrenom'])){
        // nom prenom
        $nomPrenom = $_GET['nomPrenom'];
    }
    // ZONE SELECT ID filiere: Recherche par, idFiliere --------------------------------------------------------------- idfiliere GET
    if(isset($_GET['idfiliere'])){
        // nom de la filière
        $idfiliere = $_GET['idfiliere'];
    }else{
        $idfiliere=0;
    }

    // PAGINATION                            5 par page
    $size=isset($_GET['size'])?$_GET['size']:5;
    $page=isset($_GET['page'])?$_GET['page']:1;
    $offset=($page-1)*$size;
    
    // PREPARATION requete sql en fonction des critères de Recherches ci-dessus
    if($idfiliere==0){
        // TOUTE filiere + selection nom ou prenom en like %%
        $requeteStagiaire="
            SELECT idStagiaire,nom,prenom,civilite,photoNom,photoChemin,nomFiliere
            FROM   stagiaire as s, filiere as f
            WHERE  s.idFiliere = f.idFiliere
            AND    (nom like '%$nomPrenom%' or prenom like '%$nomPrenom%')
            ORDER BY idStagiaire
            LIMIT  $size
            OFFSET $offset
        ";
        // Nombre de Stagiaire
        $requeteCount="select count(*) countS from stagiaire
                where nom like '%$nomPrenom%' or prenom like '%$nomPrenom%'";
    }else{
        // selection filiere + selection nom ou prenom en like %%
         $requeteStagiaire="
            SELECT idStagiaire,nom,prenom,civilite,photoNom,photoChemin,nomFiliere
            FROM   stagiaire as s, filiere as f
            WHERE  s.idFiliere = f.idFiliere
            AND    (nom like '%$nomPrenom%' or prenom like '%$nomPrenom%')
            AND    f.idFiliere=$idfiliere
            ORDER BY idStagiaire
            LIMIT  $size
            OFFSET $offset";
        // Nombre de Stagiaire
        $requeteCount="select count(*) countS from stagiaire
                where (nom like '%$nomPrenom%' or prenom like '%$nomPrenom%')
                and idFiliere=$idfiliere";
    }
    //
    // LECTURE des Stagiaires ---------------------
    $req = $pdo->prepare($requeteStagiaire);
    $req->execute();
    $stagiaires = $req->fetchAll(PDO::FETCH_ASSOC);
    //
    // COMPTAGE pour PAGINATION    
    $resultatCount=$pdo->query($requeteCount);
    $tabCount=$resultatCount->fetch();
    $nbrStagiaire=$tabCount['countS'];
    $reste=$nbrStagiaire % $size;   
    if($reste===0) 
        $nbrPage=$nbrStagiaire/$size;   
    else
        $nbrPage=floor($nbrStagiaire/$size)+1;  


// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT   
?>
<!DOCTYPE HTML>
<HTML>
    <head>
        <?php include '../include/head.php' ?>
        <title>Gestion des stagiaires</title>        
    </head>
    <body>
        <?php require("menu.php"); ?>        
        <div class="container py-2 margetop60">
            <!-- BLOC (PANEL) CRITERES de RECHERCHE -->
            <div class="panel panel-success">       
				<div class="panel-heading">Rechercher des stagiaires</div>				
				<div class="panel-body">

                    <!-- FORMULAIRE de SAISIE des FILTRES     nomPrenom  + Filière +++ CREATION Nouveau Stagaiaire -->
					<form method="get" action="stagiaires.php" class="form-inline">
                        <!-- nomPrenom : nom et/ou prénom du stagiaire -->
						<div class="form-group">						
                            <input name="nomPrenom" placeholder="Nom et prénom"                                   
                                   value="<?= $nomPrenom ?>"
                                   type="text" class="form-control" />
                        </div>

                        <!-- idFiliere : ID de la filière auquel est rataché le Stagiaire -->                        
                        <!-- SELECTEUR à partir de la liste des filières ----- onchange soumet ce form ---     onchange -->
                        <label for="idfiliere">Filière:</label>                            
                        <select name="idfiliere" id="idfiliere" class="form-control"
                                onchange="this.form.submit()">                                
                                <option value=0>Toutes les filières</option>                            
                            <?php                                 
                            // On commence par récupérer toutes les filieres
                            $filieres = $pdo->query('SELECT * FROM filiere')->fetchAll(PDO::FETCH_ASSOC);                            
                            // toujours tester SI ce n'est pas vide & si c'est bien un tableau
                            if(!empty($filieres) && is_array($filieres)){                
                                // BOUCLE sur le tableau des filieres
                                foreach ($filieres as $key => $value) {
                                    ?>
                                    <option value="<?= $value['idFiliere'] ?>"
                                            <?= $value['idFiliere']==$idfiliere?"selected":"" ?> >
                                        <?= $value['nomFiliere'] ." - (id=". $value['idFiliere'] .")" ?>
                                    </option>
                                    <?php                        
                                }
                                // FIN BOUCLE
                            }
                            ?>                             
                        </select>
                        <!-- BOUTON Chercher -->				            
				        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-search"></span>
                            Chercher...
                        </button> 
                        
                        <!-- CREER une NOUVEAU STAGIAIRE ----------------------------------------------- -->
                        &nbsp;&nbsp;
                         <?php if ($_SESSION['user']['role']== 'ADMIN') {?>                         
                            <a href="stagiaireAjouter.php">                            
                                <span class="glyphicon glyphicon-plus"></span>
                                Nouveau Stagiaire                                
                            </a>                            
                         <?php }?>
					</form>

				</div>
			</div>
            

            <!-- BLOC (PANEL) LISTE des stagiaires -->
            <div class="panel panel-primary">
                <div class="panel-heading">Liste des Stagiaires (<?php echo $nbrStagiaire ?> Stagiaires)</div>
                <div class="panel-body">
                    <?php
                    // =========== TABLE pour afficher les stagiaires ============
                    // toujours tester SI ce n'est pas vide & si c'est bien un tableau 
                    if(!empty($stagiaires) && is_array($stagiaires)){
                    ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id Stagiaire</th> <th>Nom</th> <th>Prénom</th> 
                                <th>Filière</th> <th>Photo</th>
                                <!-- ACTIONS réservé à ADMIN -->
                                <?php if ($_SESSION['user']['role']== 'ADMIN') {?>
                                	<th>Actions</th>
                                <?php }?>
                            </tr>
                        </thead>
                        
                        <tbody>                                                               
                            <?php                                            
                            // BOUCLE sur la liste des stagiaires -------------------------------------------------------- DO
                            foreach ($stagiaires as $item) {                                
                                ?>
                                <tr>
                                    <td><?= $item['idStagiaire'] ?> </td>
                                    <td><?= $item['nom'] ?> </td>
                                    <td><?= $item['prenom'] ?> </td>
                                    <td><?= $item['nomFiliere'] ?> </td>
                                    <td>
                                    <img src="<?= $item['photoChemin'] ?>" alt="<?= $item['photoNom'] ?>"
                                            width="30px" class="img-circle">
                                    </td>
                                    <!-- ACTIONS    réservé au rôle: ADMIN -->
                                    <?php if ($_SESSION['user']['role']== 'ADMIN') {?>
                                        <td>
                                            <!-- Editer le stagiaire (modifier) -->
                                            <a href="stagiaireEditer.php?idStagiaire=<?= $item['idStagiaire'] ?>" title="Editer">
                                            <span class="glyphicon glyphicon-edit"></span>
                                            </a>                      
                                            <!-- Supprimer le stagiaire -- Avec: Modal de confirmation -->
                                            &nbsp;
                                            <a onclick="return confirm('Etes vous sur de vouloir supprimer le stagiaire')"
                                                href="stagiaireSupprimer.php?idStagiaire=<?= $item['idStagiaire'] ?>" title="Supprimer">
                                                <span class="glyphicon glyphicon-trash"></span>   
                                            </a>
                                            <!-- Imprimer un Document -->
                                            <a href="stagiaireImprimer.php?idStagiaire=<?= $item['idStagiaire'] ?>&anneeScolaire=<?= $item['idStagiaire'] ?>" 
                                               title="Imprimer">
                                            <span class="glyphicon glyphicon-print"></span>
                                            </a>                                              
                                        </td>
                                    <?php }?>                                        
                                </tr>
                                <?php
                            }
                            // FIN BOUCLE sur la liste des stagiaires ------------------------------------------------- ENDDO
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
                                <!-- ATTENTION: url doit contenir, page, nomPrenom, idFiliere -->
                                <a href="stagiaires.php?page=<?php echo $i;?>&nomPrenom=<?php echo $nomPrenom ?>&idfiliere=<?php echo $idfiliere ?>">
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
    $_SESSION['stagiaire_ajouter'] = 0;
    $_SESSION['stagiaire_modifier'] = 0;
}