<?php
require 'vendor/autoload.php';
$sender = isset($_GET['idUser']) ? $_GET['idUser'] : null;

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../mailer/autoload.php';

// Récupérer les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $carte = $_POST["carte"];
    $email = $_POST["email"];
} else {
    exit("Erreur : Aucune donnée reçue.");
}

// Chemin vers l'image de logo
$imagePath = 'chemin/vers/votre/logo.png'; // Remplacez 'chemin/vers/votre/logo.png' par le chemin correct de votre image de logo

// Date actuelle
$date = date("Y-m-d");

// Contenu HTML de l'attestation
$html = "
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        .page {
            width: 90%;
            height: 95%;
            border: 2px solid #000;
            padding: 10px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 200px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-top: 40px;
        }
        .signature {
            margin-top: 60px;
        }
        .signature img {
            max-width: 200px;
            display: block;
            margin: 0 auto;
        }
        /* Styles pour les carrés */
        .square {
            width: 50px; /* Taille du carré */
            height: 50px;
            margin-right: 5px; /* Espacement entre les carrés */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px; /* Taille du texte */
            color: white; /* Couleur du texte */
            border-radius: 5px; /* Bord arrondi */
        }
        /* Couleur de fond de chaque carré */
        .square:nth-child(1) { background-color: #000000; }
        .square:nth-child(2) { background-color: #808080; }
        .square:nth-child(3) { background-color: #f7941d; }
        .square:nth-child(4) { background-color: #f16623; }
        .square:nth-child(5) { background-color: white; }
        .square:nth-child(6) { background-color: #808080; }
        .square:nth-child(7) { background-color: #f7941d; }
        .square:nth-child(8) { background-color: #f16623; }
        .square:nth-child(9) { background-color: #000000; }
        .square:nth-child(10) { background-color: #808080; }
        .square:nth-child(11) { background-color: #f7941d; }
    </style>
</head>
<body>
<div class='page'>
<div class='container' style='margin-right:1100px;padding-top:20px'>
    <div class='square' style='display: inline-block; text-align: center; line-height: 50px;'>W</div>
    <div class='square' style='display: inline-block; text-align: center; line-height: 50px;'>E</div>
    <div class='square' style='display: inline-block; text-align: center; line-height: 50px;'>D</div>
    <div class='square' style='display: inline-block; text-align: center; line-height: 50px;'>O</div>
    <div class='hero-container' data-aos='fade-in' style='display: inline-block;'>
       
    </div>
</div>

        <div class='header'>
            <h1>Attestation</h1>
        </div>
        <div class='content'>
            <p>Je soussigné sami gharathi, agissant en qualité  de l'entreprise wedo consult, immatriculée sous le numéro B4 et située au grombalia,tunis, nabeul,

            Certifie que :</p>
          
                <p>Mademoiselle/Monsieur $nom $prenom  de carte d'identite $carte, a effectué un stage au sein de notre entreprise .</p>
                <p>Ce stage avait pour objectif ameliorer les competences de developpements.</p>
                <!-- Ajoutez ici d'autres informations pertinentes -->
                <p>Durant la période de stage, Mademoiselle/Monsieur $nom $prenom a fait preuve de sérieux, de motivation et a réalisé les missions qui lui ont été confiées avec diligence.</p>
           
            <p> cordialement<br>Monsieur sami</p>
            <p>Fait le {$date}</p>
        
        <div class='signature'>
            <p>Signature :</p>
            <p><strong>sami gharathi</strong><p>
           
        </div>
    </div>
    </div>
</body>
</html>
";


// Instantiate Dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Setup paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render HTML as PDF
$dompdf->render();

// Get the PDF content
$pdfContent = $dompdf->output();

// Instantiate PHPMailer


try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Remplacez par votre SMTP 
    $mail->SMTPAuth = true;
    $mail->Username = 'consultwedo@gmail.com'; // Remplacez par votre email
    $mail->Password = 'wsde nowg lpbr lspu'; // Remplacez par votre mot de passe 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;


    // Destinataire
    $mail->setFrom('consultwedo@gmail.com', 'WeDo Consult');
    $mail->addAddress($email);

    // Contenu de l'email
    $mail->isHTML(true);
    $mail->Subject = 'Attestation';
    $mail->Body = 'Veuillez trouver ci-joint l\'attestation.';
    $mail->AltBody = 'Veuillez trouver ci-joint l\'attestation.';

    // Ajouter le PDF en pièce jointe
    $mail->addStringAttachment($pdfContent, 'Attestation.pdf');

    // Envoyer l'email
    $mail->send();
    header("Location: ../imprimerStagiaire.php?success=true&idUser=" . $sender);

    exit(); // Assurez-vous de terminer le script après la redirection
} catch (Exception $e) {
    // Gérer les erreurs d'envoi d'email
    echo 'Erreur lors de l\'envoi de l\'email : ', $mail->ErrorInfo;
}
