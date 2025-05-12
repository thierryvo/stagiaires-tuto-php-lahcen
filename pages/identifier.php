<?php session_start();
// SI user NON connecté: 
// Redirection sur la PAGE de connexion
if(!isset($_SESSION['user'])) {
    header('location:login.php');
    exit();
}