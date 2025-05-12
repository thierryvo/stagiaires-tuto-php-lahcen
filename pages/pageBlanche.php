<?php
    require_once('identifier.php'); // Vérification LOGIN
    require_once("connexiondb.php");
    $pageblanche = true;
    // BACK ------------------------------------------------------------------------------------------------------------------- BACK

    
// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
//  margetop60 : là c'est une class __ pour placer tout le container en dessous du MENU NAVBAR
//  style="margin-top: 60px" : là c'est un style
?>
<!DOCTYPE HTML>
<HTML>
    <head>
    <?php include '../include/head.php' ?>
    <title>PAGE BLANCHE</title>
    </head>
    <body>
        <?php include("menu.php"); ?>
        <div class="container py-2 margetop60">

            <!-- un panel vert - avec entet & body -->
            <div class="panel panel-success">
                <div class="panel-heading">Entete:  Panel with panel-success class</div>
                <div class="panel-body">Body:  Panel Contenu</div>
            </div>  
            
            <!-- un panel vert - avec entet & body pour les listes -->
            <div class="panel panel-primary">
                <div class="panel-heading">Entete:  Panel with panel-primary class</div>
                <div class="panel-body">Body:  Panel Contenu pour les listes --- sous forme de tableau ---</div>
            </div>              

        </div>
    </body>
</HTML>