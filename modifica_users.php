<?php
include 'database.php';
session_start();
if(isset($_SESSION['id'])){
    $database = new Database();
    $pdo = $database->connect();
    ?>
    <!DOCTYPE html>
    <html lang="">
        <head>
            <title>CandyPet</title>
            <link rel="stylesheet" href="css/bootstrap.min.css" >
            <link rel="stylesheet" href="css/style.css" >
            <script>
                function valida_datos() {
                    const email = document.getElementById("email");
                    const password = document.getElementById("password");
                    const regex_email = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if(!regex_email.test(email.value)) {
                        email.setCustomValidity("Campo email debe de tener la estructura a@a.com");
                    }else{
                        email.setCustomValidity("");
                    }

                    if(password.value==="") {
                        password.setCustomValidity("Campo contraseña no puede venir vacio");
                    }else{
                        password.setCustomValidity("");
                    }
                }
            </script>
        </head>
        <body>
            <div class="col-md-12">
                <ul class="menu_sis">
                    <li><a href="inicio.php" class="dropbtn obj_list">Inicio</a>
                        <?php
                        $tablas = $pdo->query("SELECT table_name AS nombre FROM information_schema.tables WHERE table_schema = '$database->dbNombre';")->fetchAll(PDO::FETCH_COLUMN);
                        foreach($tablas AS $tabla){
                            if($tabla==='customer' && $_SESSION['tipo'] === 'administrador'){
                                echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Cliente</a>';
                            }else if($tabla==='dates'){
                                echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Citas</a>';
                            }else if($tabla==='files' && $_SESSION['tipo'] === 'administrador'){
                                echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Archivos</a>';
                            }else if($tabla==='pets' && $_SESSION['tipo'] === 'administrador'){
                                echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Mascotas</a>';
                            }else if($tabla==='recipes'){
                                echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Recetas</a>';
                            }else if($tabla==='users' && $_SESSION['tipo'] === 'administrador'){
                                echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Usuario</a>';
                            }
                            echo '<div class="dropdown-content">';
                            if($_SESSION['tipo'] === 'administrador'){
                                echo '<a class="stl_accion" href="alta_'.$tabla.'.php?tabla='.$tabla.'">Alta</a>';
                            }else if($tabla==='dates' && $_SESSION['tipo'] === 'basico'){
                                echo '<a class="stl_accion" href="alta_'.$tabla.'.php?tabla='.$tabla.'">Alta</a>';
                            }
                            echo '<a class="stl_accion" href="lista.php?tabla='.$tabla.'">Lista</a>';
                            echo '</div></li>';
                        }
                        ?>
                    <li><a href="salir.php" class="dropbtn obj_list">Cerrar Session</a>
                </ul>
            </div>
            <br>
            <div class="col-md-12">
                <form method="post">
                    <?php
                        if(empty($_POST)){
                            $user = $pdo->query("SELECT * FROM $_GET[tabla] WHERE id = $_GET[id]")->fetch(PDO::FETCH_ASSOC);
                        }else{
                            $user = $_POST;
                        }
                    ?>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta email:</label>
                        </div>
                       <div class="col-md-8">
                           <input class="alta" id="email" type="text" placeholder="Email" name="email" value="<?php echo $user['email']?>">
                       </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta contraseña:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="password" type="password" placeholder="Contraseña" name="password" value="<?php echo $user['password']?>">
                        </div>

                        <div class="col-md-12">
                            <button class="btn_alta" type="submit" onclick="valida_datos()">Guardar</button>
                        </div>
                </form>
            </div>
        </body>
    </html>
    <?php
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $valores = array();

    $sql="UPDATE $_GET[tabla] SET ";
    foreach ($_POST as $campo => $valor) {
        $sql.= $campo."=?, ";
    }
    $sql = trim($sql, ', ');
    $sql.=" WHERE id = $_GET[id]";

    $q = $pdo->prepare($sql);
    foreach ($_POST as $campo => $valor) {
        $valores[] = $valor;
    }
    $q->execute($valores);
    $pdo = $database->disconnect();
}else{
    header('Location: inicio.php');
}

?>