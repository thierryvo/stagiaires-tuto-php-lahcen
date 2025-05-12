<?php require_once('identifier.php'); // Vérification LOGIN
// BACK ------------------------------------------------------------------------------------------------------------------- BACK
// ZONES issu d'une url donc en get ---------------------------------------------------------------------------------------- GET
$message="Erreur";
if(isset($_GET['message'])){
    $message = trim(htmlspecialchars($_GET['message']));
}
$url="../index.php";
if(isset($_GET['url'])){
    $url = $_GET['url'];
}


// FRONT ----------------------------------------------------------------------------------------------------------------- FRONT
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <?php include '../include/head.php' ?>
        <title>Alerte</title>        
    </head>
    <body>        
        <?php include("menu.php"); ?>
        <div class="container py-2 margetop60">
            <!-- un panel vert - avec entet & body -->
            <div class="panel panel-success">
                <div class="panel-heading">Message important</div>
                <div class="panel-body">                    
                    <!-- Message d'erreur -->
                    <div class="alert alert-danger">
                        <h4><?php echo $message ?></h4>
                    </div>
                    
                    <!-- Info -->
                    <br><br>                
                    <div class="alert alert-info">
                        <h4>Vous serez rediregé dans 3 secondes</h4>
                        <?php  header("refresh:3;url=$url"); ?>
                    </div>                    

                </div>
            </div>              

        </div>            
    </body>
</HTML>