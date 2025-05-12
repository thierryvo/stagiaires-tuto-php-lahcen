<?php
require_once('../pages/identifier.php');// Vérification LOGIN
require('../pages/connexiondb.php');
require("../include/les_fonctions/fonctions.php");
require('fpdf.php');
//$pdo = new PDO("mysql:host=localhost;dbname=ecoledb", "root", "");
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONE INPUT idStagiaire & as: en get car ça provient d'une url ----------------------------------------------- idStagiaire GET
if(
    isset($_GET['idStagiaire']) AND
    isset($_GET['anneeScolaire']) ){
    // donnees
    $idStagiaire = $_GET['idStagiaire'];
    $anneeScolaire = $_GET['anneeScolaire'];
    $anneeScolaire = annee_scolaire_actuelle();

    // Lecture table: stagiaire
    $req = $pdo->query("SELECT * FROM stagiaire WHERE idStagiaire=$idStagiaire");
    $stagiaire = $req->fetch(PDO::FETCH_ASSOC);

    $nom_prenom = strtoupper($stagiaire['nom'] . "  " . $stagiaire['prenom']);
    $date_naiss = dateEnToDateFr($stagiaire['date_naissance']);
    $lieu_naiss = strtoupper($stagiaire['lieu_naissance']);
    $cin = strtoupper($stagiaire['cin']);
    $date_insc = dateEnToDateFr($stagiaire['inscription_date']);
    $num_insc = strtoupper($stagiaire['inscription_numero']);    

    // Lecture table: scolarite
    // $sql="
    //     SELECT id_stagiaire,annee_scolaire,classe,nom as Nom_Filiere,niveau_diplome
    //     FROM scolarite,filiere
    //     WHERE filiere.id=scolarite.id_filiere
    //     AND   id_stagiaire=?
    //     AND   annee_scolaire=?
    // ";
    // $req = $pdo->prepare($sql);
    // $req->execute([
    //     $idStagiaire,
    //     $anneeScolaire
    // ]);
    // $scolarite = $req->fetchAll(PDO::FETCH_ASSOC);

    // $filiere = strtoupper($scolarite['Nom_Filiere']);
    // $niveau = strtoupper($scolarite['niveau_diplome']);
    // $classe = strtoupper($scolarite['classe']);

    // Pour tester
    $filiere = "La filiere N1";
    $classe = "La Classe A1";
    $niveau = "Niveau N1";


    // ================================ PDF ===========================================
    //Création d'un nouveau doc pdf (Portrait, en mm , taille A5)  / (A5 = 1/2 A4)
    $pdf = new FPDF('P', 'mm', 'A5');
    //Ajouter une nouvelle page
    $pdf->AddPage();

    // entete: c'est une image, AJOUTER une image
    $pdf->Image('en-tete.png', 10, 5, 130, 20);
    // Saut de ligne: de hauteur 18
    $pdf->Ln(18);


    // Police Arial gras 16
    $pdf->SetFont('Arial', 'B', 16);

    // Titre arg16                                                    TB avec bordure Top & Bottom - LTRB c'est un cadre
    $pdf->Cell(0, 10, 'ATTESTATION DE SCOLARITE', 'TB', 1, 'C');
    $pdf->Cell(0, 10, 'N°:', 0, 1, 'C');
    // Saut de ligne
    $pdf->Ln(5);

    // Début en police Arial normale taille 10
    $pdf->SetFont('Arial', '', 10);
    // h hauteur 7 & retrait pour aligner le texte sous forme de paragraphe décalé
    $h = 7;
    $retrait = "      ";

    $pdf->Write($h, "Je soussigné, Directeur de l'établissement CLEVER SCHOOL 2 PRIVEE EL ATTAOUIA Certifie que : \n");
    $pdf->Write($h, $retrait . "L'élève : ");

    //Ecriture en Gras-Italique-Souligné(U) --- B = Gras  I = Italique    U = Souligné
    $pdf->SetFont('', 'BIU');
    $pdf->Write($h, $nom_prenom . "\n");

    //Ecriture normal
    $pdf->SetFont('', '');
    $pdf->Write($h, $retrait . "Né (e) Le : " . $date_naiss . " À : " . $lieu_naiss . "\n");
    $pdf->Write($h, $retrait . "CIN N° : " . $cin . " \n");
    $pdf->Write($h, $retrait . "Inscrit (e) le : " . $date_insc . " Sous le N° : " . $num_insc . " \n");
    $pdf->Write($h, $retrait . "Filière :  " . $filiere . " \n");
    $pdf->Write($h, $retrait . "Niveau de formation :  " . $niveau . "  \n");
    $pdf->Write($h, $retrait . "Classe :  " . $classe . " \n");
    $pdf->Write($h, $retrait . "Année de formation :  " . $anneeScolaire . "  \n");
    $pdf->Write($h, "Poursuit ses étude en  " . $classe . "   et cela pour l'année scolaire en cours  " . $anneeScolaire . "  \n");
    $pdf->Write($h, "La présente attestation est délivrée à l'intéressé Pour servir et valoir ce que de droit. \n");
    $pdf->Cell(0, 5, 'Fait à El Attaouia Le :' . date('d/m/Y'), 0, 1, 'C');

    // Cellule : En-tête du tableau en bas
    // Décalage de 20 mm à droite (la première colone de gauche est une cellule vide) largeur 20
    // puis ensuite un cadre de 80 de largeur
    $pdf->Cell(20);
    $pdf->Cell(80, 8, "Le directeur pédagogique de l'établissement", 1, 1, 'C');

    // Décalage de 20 mm à droite (la première colone de gauche est une cellule vide) - largeur 20
    // puis ensuite un cadre de 80 de largeur         répété n fois
    $pdf->Cell(20);
    $pdf->Cell(80, 5, "Mr LAHCEN ABOUSALIH", 'LR', 1, 'C');
    $pdf->Cell(20);
    $pdf->Cell(80, 5, ' ', 'LR', 1, 'C'); // LR Left-Right
    $pdf->Cell(20);
    $pdf->Cell(80, 5, ' ', 'LR', 1, 'C');
    $pdf->Cell(20);
    $pdf->Cell(80, 5, ' ', 'LR', 1, 'C');
    $pdf->Cell(20);
    $pdf->Cell(80, 5, ' ', 'LRB', 1, 'C'); // LRB : Left-Right-Bottom (Bas)


    //Afficher le pdf ====================================================================================
    $pdf->Output('', '', true);    


}//FINSI: isset