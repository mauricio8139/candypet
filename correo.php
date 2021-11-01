<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

$correo_des = 'mauricioisraelhernandez@gmail.com';
$nombre_des = 'Israel';

$asunto = 'Avance 3';
$mensaje = 'Prueba 3';

if($correo_des === '') {
    echo 'Se tiene que seleccionar un correo de destino';
    exit;
}

if($nombre_des === ''){
    echo 'Se tiene que seleccionar el nombre de destino';
    exit;
}

if($asunto === ''){
    echo 'Se tiene que tener un asunto';
    exit;
}

if($mensaje === ''){
    echo 'Se tiene que tener un mensaje';
    exit;
}

try {
    $mail->SMTPDebug = 2;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'mauricio.hernandez8139@alumnos.udg.mx';                     //SMTP username
    $mail->Password   = '.UdeG-57692';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    $mail->setFrom('mauricio.hernandez8139@alumnos.udg.mx', 'CandyPet');
    $mail->addAddress($correo_des, $nombre_des);

    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body    = $mensaje;
    $mail->AltBody = $mensaje;

    $mail->send();
    echo 'El mensaje ha sido enviado';
    exit;
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}