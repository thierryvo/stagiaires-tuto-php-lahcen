<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
		    <!-- index -->
			<a href="../index.php" class="navbar-brand">Gestion des stagiaires</a>			
		</div>
		<ul class="nav navbar-nav">
			<!-- Les stagiaires -->
			<li class="<?= !empty($pagestagiaires) ? 'active' : '' ?>">
			<a href="stagiaires.php">
                    <i class="fa fa-vcard"></i>
                    &nbsp Les Stagiaires
            </a>
            </li>
			<!-- Les filières -->
			<li class="<?= !empty($pagefilieres) ? 'active' : '' ?>">
			<a href="filieres.php">
                    <i class="fa fa-tags"></i>
                    &nbsp Les Filières
            </a>
            </li>

			<!-- blanche -->
			<li class="<?= !empty($pageblanche) ? 'active' : '' ?>">
			<a href="pageBlanche.php">
                    <i class="fa fa-tags"></i>
                    &nbsp PAGE BLANCHE
            </a>
            </li>			

			<!-- Les utilisateurs    uniquement SI ADMIN-->
			<?php if ($_SESSION['user']['role']=='ADMIN') {?>					
				<li class="<?= !empty($pageutilisateurs) ? 'active' : '' ?>">
				<a href="Utilisateurs.php">
                        <i class="fa fa-users"></i>
                        &nbsp Les utilisateurs
                </a>
                </li>				
			<?php }?>			
		</ul>
		

		<!-- PARTIE Droite du MENNU -->
		<ul class="nav navbar-nav navbar-right">
			<!-- MODIFIER (Editer) un utilisateur en cliquant sur son Nom: thi.voz ou admin -->
			<li>				
			<a href="utilisateurEditer.php?iduser=<?php echo $_SESSION['user']['iduser'] ?>">
                    <i class="fa fa-user-circle-o"></i>
					<?php echo  ' '.$_SESSION['user']['login']?>
			</a> 
			</li>
			<!-- SE Deconnecter -->			
			<li>				
			<a href="seDeconnecter.php">
                    <i class="fa fa-sign-out"></i>
					&nbsp Se déconnecter
			</a>
			</li>							
		</ul>		
	</div>
</nav>