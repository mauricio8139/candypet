<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include 'database.php';
require 'vendor/autoload.php';


$user_ok = false;
$id = 0;

if(!empty($_POST)){
    $database = new Database();
    $pdo = $database->connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql="INSERT INTO recipes (";
    foreach ($_POST as $campo => $valor) {
        $sql.= $campo.", ";
    }
    $sql = trim($sql, ', ');
    $sql.=") values(";


    for($i=0;$i<count($_POST); $i++){
        $sql.='?, ';
    }
    $sql = trim($sql, ', ');
    $sql.=")";

    $q = $pdo->prepare($sql);
    foreach ($_POST as $campo => $valor) {
        $valores[] = $valor;
    }

    $q->execute($valores);
    $id = $pdo->lastInsertId();
    $user_ok = true;
    $pdo = $database->disconnect();
}

if($user_ok) {
    $database = new Database();
    $pdo = $database->connect();

    $recipe = $pdo->query("SELECT * FROM recipes WHERE id =".$id)->fetchALL(PDO::FETCH_ASSOC);
    $pet = $pdo->query("SELECT * FROM pets WHERE files_id =".$recipe[0]['files_id'])->fetchALL(PDO::FETCH_ASSOC);
    $customer = $pdo->query("SELECT * FROM customer WHERE id =".$pet[0]['customer_id'])->fetchALL(PDO::FETCH_ASSOC);
    $user = $pdo->query("SELECT * FROM users WHERE id =".$customer[0]['users_id'])->fetchALL(PDO::FETCH_ASSOC);

    $mail = new PHPMailer(true);

    $correo_des = $user[0]['email'];
    $nombre_des = $customer[0]['name'].' '.$customer[0]['last_name'];

    $asunto = 'Receta de '.$pet[0]['name_pet'];
    $mensaje = "Buen dia ".$customer[0]['name'].", nos comunicamos de CandyPet para hacerle llegar le receta de ".$pet[0]['name_pet'].": <br>
        * ".$recipe[0]['dsc_recipes'];

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
        $mail->Password = '.UdeG-57692';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        $mail->setFrom('mauricio.hernandez8139@alumnos.udg.mx', 'CandyPet');
        $mail->addAddress($correo_des, $nombre_des);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->AltBody = $mensaje;

        $mail->send();

        header('Location: alta.php?tabla=recipes');
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
}