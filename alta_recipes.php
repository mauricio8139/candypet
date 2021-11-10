<?php
include 'database.php';
session_start();
if(isset($_SESSION['id'])){
    $database = new Database();
    $pdo = $database->connect();
    $files = $pdo->query("SELECT * FROM files")->fetchALL(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="">
        <head>
            <title>CandyPet</title>
            <link rel="stylesheet" href="css/bootstrap.min.css" >
            <link rel="stylesheet" href="css/style.css" >
            <script>
                function valida_datos() {
                    const dsc_recipes = document.getElementById("dsc_recipes");
                    const date_recipe = document.getElementById("date_recipe");

                    if(dsc_recipes.value==="") {
                        dsc_recipes.setCustomValidity("Campo descripcion no puede venir vacio");
                    }else{
                        dsc_recipes.setCustomValidity("");
                    }

                    if(date_recipe.value==="") {
                        date_recipe.setCustomValidity("Campo apellido no puede venir vacio");
                    }else{
                        date_recipe.setCustomValidity("");
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
                <form method="post" action="correo.php">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta descripcion receta:</label>
                        </div>
                       <div class="col-md-8">
                           <input class="alta" id="dsc_recipes" type="text" placeholder="Descripcion" name="dsc_recipes">
                       </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta fecha:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="date_recipe" type="date" placeholder="fecha" name="date_recipe">
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
                        <div class="col-md-12">
                            <button class="btn_alta" type="submit" onclick="valida_datos()">Guardar</button>
                        </div>
                </form>
            </div>
        </body>
    </html>
    <?php
}else{
    header('Location: inicio.php');
}
?>