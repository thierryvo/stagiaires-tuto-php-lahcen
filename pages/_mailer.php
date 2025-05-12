<?php
// Importer des classes PHPMailer dans l'espace de noms global
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';
//
//
// forgot password reset = mot de passe oublié, réinitialisation
// reset_pass_perdu -------------------------------------------------------------------------------------------------------------------
function reset_pass_perdu($emailDestinataire, $token, $iduser){
    //
    // CREER une instance ; passer `true` active pour les exceptions
    $mail = new PHPMailer(true);

    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // enable = Activer la sortie de débogage détaillée
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // => Utiliser un SERVEUR SMTP gmail
        $mail->SMTPAuth   = true;                                   // => Enable = Activer SMTP authentication
        $mail->Username   = 'thi.voz@gmail.com';                    // => SMTP username: le mien pour tseter
        $mail->Password   = 'gyjnsisqjtfqguyt';                     // => SMTP password: Mettre ici le mot de passe des Applications Gmail mais pas: 'X..._01'
        $mail->SMTPSecure = 'tls';                                  // => Enable = activer implicit TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients = Destinatire
        $le_titre = "reinitialiser votre mot de passe";
        $mail->setFrom('thi.voz@gmail.com', $le_titre);              // From : email envoyeur, emmetteur, on met moi +  Mettre un titre
        $mail->addAddress($emailDestinataire);                      // recipient = Destinataire = thi.voz@gmail.com
        //$mail->addAddress('joe@example.net', 'Joe User');         // recipient = Destinataire --- avec le nom c'est facultatif  Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments = Pièces jointes
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content = Contenu
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Notification PHP Pass Perdu';
        $mail->Body    = "<h2>Bonjour</h2>
        <h3>Vous recevez cet email suite à la demande de changement de mot de passe 
        depuis votre compte du mini projet PHP sur coder de zéro</h3>
        <a href='http://localhost/LAHCEN/stagiaires/pages/loginChangerPwd.php?email=" .$emailDestinataire. "&token=" .$token. "&iduser=" .$iduser. "'>Merci de cliquer ici pour changer votre mot de passe</a>";
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
        return "Le message mail a été envoyé.";
    } catch (Exception $e) {
        return "De _mailer.php, Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
    }
}//FIN: reset_pass_perdu