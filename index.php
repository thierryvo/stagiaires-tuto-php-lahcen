<?php session_start();
$_SESSION['message']['text'] = null;
$_SESSION['message']['type'] = null;
header("location:pages/filieres.php");