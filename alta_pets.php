<?php
include 'database.php';
session_start();
if(isset($_SESSION['id'])){
    $database = new Database();
    $pdo = $database->connect();
    $files = $pdo->query("SELECT * FROM files")->fetchALL(PDO::FETCH_ASSOC);
    $customer = $pdo->query("SELECT * FROM customer")->fetchALL(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="">
        <head>
            <title>CandyPet</title>
            <link rel="stylesheet" href="css/bootstrap.min.css" >
            <link rel="stylesheet" href="css/style.css" >
            <script>
                function valida_datos() {
                    const name_pet = document.getElementById("name_pet");
                    const specie = document.getElementById("specie");
                    const breed = document.getElementById("breed");

                    if(name_pet.value==="") {
                        name_pet.setCustomValidity("Campo nombre mascota no puede venir vacio");
                    }else{
                        name_pet.setCustomValidity("");
                    }

                    if(specie.value==="") {
                        specie.setCustomValidity("Campo especie no puede venir vacio");
                    }else{
                        specie.setCustomValidity("");
                    }

                    if(breed.value==="") {
                        breed.setCustomValidity("Campo raza no puede venir vacio");
                    }else{
                        breed.setCustomValidity("");
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
                            <label class="des_alta" for="fname">Inserta nombre mascota:</label>
                        </div>
                       <div class="col-md-8">
                           <input class="alta" id="name_pet" type="text" placeholder="Nombre" name="name_pet">
                       </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta especie:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="specie" type="text" placeholder="Especie" name="specie">
                        </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta raza:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="breed" type="text" placeholder="Raza" name="breed">
                        </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Selecciona expediente:</label>
                        </div>
                        <div class="col-md-8">
                            <select class="alta" id="files_id" name="files_id">
                                <?php
                                    foreach ($files as $file){
                                        echo '<option value="'.$file['id'].'">'.$file['id'].' '.$file['dsc_files'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Selecciona cliente:</label>
                        </div>
                        <div class="col-md-8">
                            <select class="alta" id="customer_id" name="customer_id">
                                <?php
                                    foreach ($customer as $custome){
                                        echo '<option value="'.$custome['id'].'">'.$custome['id'].' '.$custome['name'].' '.$custome['last_name'].'</option>';
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

        $sql="INSERT INTO pets (";
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