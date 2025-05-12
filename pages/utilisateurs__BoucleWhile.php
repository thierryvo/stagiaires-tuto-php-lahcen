<?php
    require_once('role.php');// Vérification LOGIN + Vérifier si on est bien: ADMIN
    require_once("connexiondb.php");
    $pageutilisateurs = true;
    // BACK ------------------------------------------------------------------------------------------------------------------- BACK
    // ZONE INPUT login: Recherche par, login de l utilisateur like %% --------------------------------------------------- login GET
    $login="";
    if(isset($_GET['login'])){
        // login
        $login = $_GET['login'];
    }

    // PAGINATION
    $size=isset($_GET['size'])?$_GET['size']:3;
    $page=isset($_GET['page'])?$_GET['page']:1;
    $offset=($page-1)*$size;
   
    // PREPARATION requete sql en fonction des critères de Recherches ci-dessus
    $requeteUser="
        SELECT * 
        FROM   utilisateur 
        WHERE  login like '%$login%'
    ";

    // LECTURE des Utilisateurs ---------------------
    $req = $pdo->prepare($requeteUser);
    $req->execute();
    $utilisateurs = $req->fetchAll(PDO::FETCH_ASSOC);
    //
    // COMPTAGE pour PAGINATION
    $requeteCount="select count(*) countUser from utilisateur";
    $resultatCount=$pdo->query($requeteCount);
    $tabCount=$resultatCount->fetch();
    $nbrUser=$tabCount['countUser'];
    $reste=$nbrUser % $size;   
    if($reste===0) 
        $nbrPage=$nbrUser/$size;   
    else
        $nbrPage=floor($nbrUser/$size)+1;  


// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT        
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <?php include '../include/head.php' ?>        
        <title>Gestion des utilisateurs</title>        
    </head>
    <body>
        <?php include("menu.php"); ?>        
        <div class="container py-2 margetop60">
            <!-- BLOC (PANEL) CRITERES de RECHERCHE -->
            <div class="panel panel-success">
				<div class="panel-heading">Rechercher des utilisateurs</div>
				<div class="panel-body">

                    <!-- FORMULAIRE de SAISIE des FILTRES:     login -->
					<form method="get" action="utilisateurs.php" class="form-inline">
                        <!-- login -->
						<div class="form-group">                            
                            <input name="login" placeholder="Login"
                                   value="<?= $login ?>"
                                   type="text" class="form-control" />                                   
                        </div>
                        <!-- BOUTON Chercher -->	
				        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-search"></span>
                            Chercher...
                        </button> 
					</form>
				</div>
			</div>
            

            <!-- BLOC (PANEL) LISTE des utilisateurs -->
            <div class="panel panel-primary">
                <div class="panel-heading">Liste des utilisateurs (<?php echo $nbrUser ?> utilisateurs)</div>
                <div class="panel-body">
                    <?php
                    // =========== TABLE pour afficher les utilisateurs ============
                    // toujours tester SI ce n'est pas vide & si c'est bien un tableau 
                    if(!empty($utilisateurs) && is_array($utilisateurs)){
                    ?>                
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>login</th> 
                                <th>Email</th> 
                                <th>Role</th> 
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>



                        

                            <?php while($user=$resultatUser->fetch()){ ?>
                                <tr class="<?php echo $user['etat']==1?'success':'danger'?>">
                                    <td><?php echo $user['login'] ?> </td>
                                    <td><?php echo $user['email'] ?> </td>
                                    <td><?php echo $user['role'] ?> </td>  
                                   <td>
                                        <a href="editerUtilisateur.php?idUser=<?php echo $user['iduser'] ?>" title="Editer">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                        &nbsp;&nbsp;
                                        <a onclick="return confirm('Etes vous sur de vouloir supprimer cet utilisateur')"
                                            href="supprimerUtilisateur.php?idUser=<?php echo $user['iduser'] ?>" title="Supprimer">
                                                <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                        &nbsp;&nbsp;
                <a href="activerUtilisateur.php?idUser=<?php echo $user['iduser'] ?>&etat=<?php echo $user['etat']  ?>" title="Activer">  
                                                <?php  
                                                    if($user['etat']==1)
                                                        echo '<span class="glyphicon glyphicon-remove"></span>';
                                                    else 
                                                        echo '<span class="glyphicon glyphicon-ok"></span>';
                                                ?>
                                            </a>
                                        </td>       
                                </tr>
                             <?php } ?>















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
                                <a href="utilisateurs.php?page=<?php echo $i;?>&login=<?php echo $login ?>">
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