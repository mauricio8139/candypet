<?php
include 'database.php';
session_start();
if(isset($_SESSION['id'])){
    $database = new Database();
    $pdo = $database->connect();
    $users = $pdo->query("SELECT * FROM users")->fetchALL(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="">
        <head>
            <title>CandyPet</title>
            <link rel="stylesheet" href="css/bootstrap.min.css" >
            <link rel="stylesheet" href="css/style.css" >
            <script>
                function valida_datos() {
                    const name = document.getElementById("name");
                    const last_name = document.getElementById("last_name");
                    const phone = document.getElementById("phone");
                    const  address = document.getElementById("address");

                    const expresion = /[0-9]{10}/;

                    if(name.value==="") {
                        name.setCustomValidity("Campo nombre no puede venir vacio");
                    }else{
                        name.setCustomValidity("");
                    }

                    if(last_name.value==="") {
                        last_name.setCustomValidity("Campo apellido no puede venir vacio");
                    }else{
                        last_name.setCustomValidity("");
                    }

                    if(!expresion.test(phone.value) || phone.value.length > 10) {
                        phone.setCustomValidity("El numero de telefono debe de ser de 10 digitos");
                    }else{
                        phone.setCustomValidity("");
                    }

                    if(address.value==="") {
                        address.setCustomValidity("Campo direccion no puede venir vacio");
                    }else{
                        address.setCustomValidity("");
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
                            }else if($tabla==='dates' && $_SESSION['tipo'] === 'administrador'){
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
                    <div class="row">
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta nombre:</label>
                        </div>
                       <div class="col-md-8">
                           <input class="alta" id="name" type="text" placeholder="Nombre" name="name">
                       </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta apellido:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="last_name" type="text" placeholder="Apellido" name="last_name">
                        </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta telefono:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="phone" type="text" placeholder="Telefono" name="phone">
                        </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta direccion:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="address" type="text" placeholder="Direccion" name="address">
                        </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Selecciona usuario:</label>
                        </div>
                        <div class="col-md-8">
                            <select class="alta" id="users_id" name="users_id">
                                <?php
                                    foreach ($users as $user){
                                        echo '<option value="'.$user['id'].'">'.$user['id'].' '.$user['email'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button class="btn_alta" type="submit" onclick="valida_datos()">Guardar</button>
                        </div>
                </form>
            </div>
        </body>
    </html>
    <?php
    if(!empty($_POST)){
        $database = new Database();
        $pdo = $database->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql="INSERT INTO customer (";
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

        $pdo = $database->disconnect();
    }

}else{
    header('Location: inicio.php');
}

?>