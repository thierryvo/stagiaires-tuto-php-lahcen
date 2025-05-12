<?php

// rechercher_user_par_email ------------------------------------------------------------
function rechercher_user_par_email($email){
    global $pdo;
    $requete=$pdo->prepare("select * from utilisateur where email =?");
    $requete->execute(array($email));
    $user=$requete->fetch(PDO::FETCH_ASSOC);
    if($user)
        return $user;
    else
        return null;
}

// rechercher_token_en_base ------------------------------------------------------------
function rechercher_token_en_base($email){
    global $pdo;
    $requete=$pdo->prepare("select * from utilisateur_token where email =?");
    $requete->execute(array($email));
    $tab=$requete->fetch(PDO::FETCH_ASSOC);
    if($tab){
        $unToken = $tab['token'];
        return $unToken;
    }else
        return null;
}

// rechercher_par_login -----------------------------------------------------------------
function rechercher_par_login($login){
    global $pdo;
    $requete=$pdo->prepare("select * from utilisateur where login =?");
    $requete->execute(array($login));
    return $requete->rowCount();
}

// rechercher_par_email -----------------------------------------------------------------
function rechercher_par_email($email){
    global $pdo;
    $requete=$pdo->prepare("select * from utilisateur where email =?");
    $requete->execute(array($email));
    return $requete->rowCount();
}


//
// Fonctions de dates -----------------------------
function annee_scolaire_actuelle()
{
    $mois = date("m");//Le mois de la date actuelle
    $annee_actuelle = date("Y");//L'année de la date actuelle
    if ($mois >= 9 && $mois <= 12) {
        $annee1 = $annee_actuelle;
        $annee2 = $annee_actuelle + 1;
    } else {
        $annee1 = $annee_actuelle - 1;
        $annee2 = $annee_actuelle;
    }

    $annee_scolaire_actuelle = $annee1 . "/" . $annee2;
    return $annee_scolaire_actuelle;
}

function nombre_annee_scolaire()
{
    $annee_debut = 2020;
    $mois = date("m");
    $annee_actuelle = date("Y");//2018
    if ($mois >= 9 && $mois <= 12)
        return ($annee_actuelle - $annee_debut) + 1;
    else
        return $annee_actuelle - $annee_debut;
}

function les_annee_scolaire($annee_debut = 2010)
{
    $les_annees = array();
    for ($i = 1; $i <= nombre_annee_scolaire(); $i++) {
        $annee_sc = ($annee_debut + ($i - 1)) . "/" . ($annee_debut + $i);
        $les_annees[] = $annee_sc;
    }
    return $les_annees;

}
function dateEnToDateFr($dateEn)
{
    //$dateEn='2019-02-26';
    return substr($dateEn, 8, 2) . "/" . substr($dateEn, 5, 2) . "/" . substr($dateEn, 0, 4);
    // Result: '26/02/2019'
}

function dateFrToDateEn($dateFr)
{
    //$dateFR='26/02/2019';
    return substr($dateFr, 6, 4) . "-" . substr($dateFr, 3, 2) . "-" . substr($dateFr, 0, 2);
    // Result: '2019-02-26'
}
// dates ------------------------------------------
