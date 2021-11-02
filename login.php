<!DOCTYPE html>
<html>
<head>
	<title>CandyPet</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" >
	<link rel="stylesheet" href="css/style.css" >
</head>
<body>
	<div class="inicio container d-flex justify-content-center align-items-center">
		<form method="post" action="" class="border shadow p-3 rounded">
		  	<div class="mb-3">
		    	<label class="form-label">Email:
		    	    <input class="form-control" name="email">
                </label>
		  	</div>
		  	<div class="mb-3">
		    	<label class="form-label">Contraseña:
		    	    <input type="password" class="form-control" name="password">
                </label>
		  	</div>
		  <button type="submit" class="btn btn-primary">Enviar</button>
		</form>
        <?php
            include 'database.php';
            if(!empty($_POST)){
                session_start();
                $email = $_POST['email'];
                $pass = $_POST['password'];

                $database = new Database();
                $pdo = $database->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = $pdo->prepare("SELECT * FROM users WHERE email = :u AND password = :p");
                $query->bindParam(":u",$email);
                $query->bindParam(":p",$pass);
                $query->execute();
                $usuario = $query->fetch(PDO::FETCH_ASSOC);
                if($usuario){
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['tipo'] = $usuario['type_user'];
                    header('Location: inicio.php');
                }else{
                    echo '<script type="text/javascript">alert("Usuario o contraseña invalidos");</script>';
                }
            }
        ?>
	</div>
</body>
</html>	