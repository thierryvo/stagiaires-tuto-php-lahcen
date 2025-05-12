<?php
require_once('identifier.php');// Vérification LOGIN
include("../include/les_fonctions/fonctions.php");
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONE INPUT idStagiaire & as: en get car ça provient d'une url ----------------------------------------------- idStagiaire GET
if(
    isset($_GET['idStagiaire']) AND
    isset($_GET['anneeScolaire']) ){
    // donnees
    $idStagiaire=$_GET['idStagiaire'];
    $anneeScolaire=$_GET['anneeScolaire'];
    if($anneeScolaire==0){ $anneeScolaire=annee_scolaire_actuelle(); }
}//FINSI isset



// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include '../include/head.php' ?>
		<title>  Les Documents et les Attestations </title> 
	</head>				
	<body>
        <?php require("menu.php"); ?>
		<div class="container col-md-6 col-md-offset-3 margetop60">
			<h2>Séléctionner le document à imprimer</h2>
			<div class="panel panel-primary">
				<div class="panel-body text-center">
					<!-- ATTESTATION -->
					<a class="btn btn-primary" 	href="att_inscription.php">
                        Attestation d'inscription
                    </a>
					<!-- ATTESTATION de Scolarité -->
					<a class="btn btn-success" 
					   href="../fpdf/att_scolarite.php?idStagiaire=<?= $idStagiaire ?>&anneeScolaire=<?= $anneeScolaire ?>">
						Attestation de scolarité
					</a>
					<!-- ATTESTATION -->
					<a class="btn btn-info" href="demande_stage.php">
                        Demande de Stage
                    </a>
				
				</div>
			</div>			
		</div>
	</body>	
</html>
