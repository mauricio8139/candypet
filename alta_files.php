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
                    const date_files = document.getElementById("date_files");
                    const dsc_files = document.getElementById("dsc_files");

                    const expresion = /[0-9]{10}/;

                    if(date_files.value==="") {
                        date_files.setCustomValidity("Campo fecha no puede venir vacio");
                    }else{
                        date_files.setCustomValidity("");
                    }

                    if(dsc_files.value==="") {
                        dsc_files.setCustomValidity("Campo descripcion no puede venir vacio");
                    }else{
                        dsc_files.setCustomValidity("");
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
                    <div class="row">
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta fecha:</label>
                        </div>
                       <div class="col-md-8">
                           <input class="alta" id="date_files" type="date" placeholder="Fecha" name="date_files">
                       </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta descripcion expediente:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="dsc_files" type="text" placeholder="Descripcion expediente" name="dsc_files">
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

        $sql="INSERT INTO files (";
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