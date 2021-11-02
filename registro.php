<!DOCTYPE html>
<html lang="">
    <head>
        <title>CandyPet</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" href="css/style.css" >
    </head>
    <body>
        <div style="height: 100vh" class="container d-flex justify-content-center align-items-center">
            <form style="width: 40%" method="POST" action="" class="border shadow p-3 rounded">
                <div class="mb-3" style="text-align: center">
                    <h1 class="button_registro">Registro</h1>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Nombre:
                        <input class="form-control" name="name">
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Apellidos:
                        <input class="form-control" name="last_name">
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Telefono:
                        <input class="form-control" name="phone">
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Direccion:
                        <input class="form-control" name="address">
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Email:
                        <input class="form-control" name="email">
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Contraseña:
                        <input id="pass_1" type="password" class="form-control" name="password">
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label input_registro">Confirma contraseña:
                        <input id="pass_2" type="password" class="form-control" name="conf_password">
                    </label>
                </div>

                <button type="submit" class="btn btn-primary button_registro">Enviar</button>
            </form>
            <?php
                include 'database.php';
                if(!empty($_POST)){
                    $user_ok = false;
                    $id = 0;
                    if($_POST['password']===$_POST['conf_password']) {
                        $database = new Database();
                        $pdo = $database->connect();
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql = "INSERT INTO users (email,password) VALUES (?,?)";

                        $q = $pdo->prepare($sql);
                        $valores[] = $_POST['email'];
                        $valores[] = $_POST['password'];

                        $q->execute($valores);
                        $id = $pdo->lastInsertId();

                        $pdo = $database->disconnect();
                        $user_ok = true;
                    }
                    if($user_ok){
                        $valores = array();
                        $database = new Database();
                        $pdo = $database->connect();
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql = "INSERT INTO customer (name, last_name, phone, address, users_id) VALUES (?,?,?,?,?)";

                        $q = $pdo->prepare($sql);
                        $valores[] = $_POST['name'];
                        $valores[] = $_POST['last_name'];
                        $valores[] = $_POST['phone'];
                        $valores[] = $_POST['address'];
                        $valores[] = $id;

                        $q->execute($valores);

                        $pdo = $database->disconnect();
                        header('Location: login.php');
                    }
                }
            ?>
        </div>
    </body>
</html>