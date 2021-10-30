<!DOCTYPE html>
<html>
<head>
	<title>Proyecto Base de Datos</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" >
	<link rel="stylesheet" href="css/style.css" >
</head>
<body>
	<div class="inicio container d-flex justify-content-center align-items-center">
		<form method="post" class="border shadow p-3 rounded">
		  	<div class="mb-3">
		    	<label class="form-label">Usuario: </label>
		    	<input class="form-control" name="usuario">
		  	</div>
		  	<div class="mb-3">
		    	<label class="form-label">Contraseña: </label>
		    	<input type="password" class="form-control" name="password">
		  	</div>
		  <button type="submit" class="btn btn-primary">Enviar</button>
		</form>
	</div>
</body>
<?php
include 'database.php';

if(!empty($_POST)){
	session_start();
	$user = $_POST['usuario'];
	$pass = $_POST['password'];

	$database = new Database();
	$pdo = $database->connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = $pdo->prepare("SELECT * FROM users WHERE name_user = :u AND pass = :p");
	$query->bindParam(":u",$user);
	$query->bindParam(":p",$pass);
	$query->execute();
	$usuario = $query->fetch(PDO::FETCH_ASSOC);
	if($usuario){
		$_SESSION['usuario'] = $usuario['name_user'];
		header('Location: inicio.php');
	}else{
		echo '<script type="text/javascript">alert("Usuario o contraseña invalidos");</script>';
	}
}
?>
</html>	