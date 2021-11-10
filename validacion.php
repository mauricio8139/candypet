<!DOCTYPE html>
<html lang="">
<head>
	<title>CandyPet</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" >
	<link rel="stylesheet" href="css/style.css" >
</head>
<body>
	<div class="inicio container d-flex justify-content-center align-items-center">
		<form method="post" action="" class="border shadow p-3 rounded">
		  	<div class="mb-3">
                <p>Por favor, ingresa el codigo que hemos enviado a tu correo.</p>
		    	<label class="form-label">Codigo:
		    	    <input class="form-control" name="codigo">
                </label>
		  	</div>
		  <button type="submit" class="btn btn-primary">Enviar</button>
		</form>
        <?php
            include 'database.php';
            session_start();

            if(!empty($_POST)){
                if((string)$_POST['codigo'] === (string)$_SESSION['codigo']) {
                    $id = 0;
                    $database = new Database();
                    $pdo = $database->connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO users (email,password) VALUES (?,?)";

                    $q = $pdo->prepare($sql);
                    $valores[] = $_SESSION['email'];
                    $valores[] = $_SESSION['password'];

                    $q->execute($valores);
                    $id = $pdo->lastInsertId();

                    $pdo = $database->disconnect();

                    $valores = array();
                    $database = new Database();
                    $pdo = $database->connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "INSERT INTO customer (name, last_name, phone, address, users_id) VALUES (?,?,?,?,?)";

                    $q = $pdo->prepare($sql);
                    $valores[] = $_SESSION['name'];
                    $valores[] = $_SESSION['last_name'];
                    $valores[] = $_SESSION['phone'];
                    $valores[] = $_SESSION['address'];
                    $valores[] = $id;

                    $q->execute($valores);

                    $pdo = $database->disconnect();

                    session_start();
                    session_destroy();
                    header('Location: login.php');
                }else{
                    echo '<script type="text/javascript">alert("El codigo no es correcto");</script>';
                }
            }
        ?>
	</div>
</body>
</html>	