<?php session_start();
// SI user NON connecté: 
// Redirection sur la PAGE de connexion: LOGIN
if (!isset($_SESSION['user'])) {
    header('location:login.php');
    exit();
} else {
    // SINON
    // Deconnexion !!!!!!!!!!!!!!!!
    if ($_SESSION['user']['role'] != 'ADMIN') {
        header('location:seDeconnecter.php');
        exit();
    }
}