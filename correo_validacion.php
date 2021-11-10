<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include 'database.php';
require 'vendor/autoload.php';
if (!empty($_POST)) {
    if ($_POST['password'] === $_POST['conf_password']) {
        $confirmacion = 0;

        $mail = new PHPMailer(true);

        $codigo = rand(100000,999999);

        $correo_des = $_POST['email'];
        $nombre_des = $_POST['name'].' '.$_POST['last_name'];

        $asunto = "Confirmation de correo";
        $mensaje = "Buen dia la razon del correo es para validar el correo ".$_POST['email']." registrado. <br> Este es el codigo de confirmacion: 
            ".$codigo;

        if ($correo_des === '') {
            echo 'Se tiene que seleccionar un correo de destino';
            exit;
        }

        if ($nombre_des === '') {
            echo 'Se tiene que seleccionar el nombre de destino';
            exit;
        }

        if ($asunto === '') {
            echo 'Se tiene que tener un asunto';
            exit;
        }

        if ($mensaje === '') {
            echo 'Se tiene que tener un mensaje';
            exit;
        }

        try {
            $mail->SMTPDebug = 2;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = 'mauricio.hernandez8139@alumnos.udg.mx';                     //SMTP username
            $mail->Password = 'Moro1983582001.';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            $mail->setFrom('mauricio.hernandez8139@alumnos.udg.mx', 'CandyPet');
            $mail->addAddress($correo_des, $nombre_des);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;
            $mail->AltBody = $mensaje;

            $mail->send();

            session_start();
            $_SESSION['name'] = $_POST['name'];
            $_SESSION['last_name'] = $_POST['last_name'];
            $_SESSION['phone'] = $_POST['phone'];
            $_SESSION['address'] = $_POST['address'];
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['password'] = $_POST['password'];
            $_SESSION['codigo'] = $codigo;

            header('Location: validacion.php');
        } catch (Exception $e) {
            echo "Error: {$mail->ErrorInfo}";
        }
    }
}
