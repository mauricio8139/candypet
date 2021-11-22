<?php
include 'database.php';
session_start();
if(isset($_SESSION['id'])){
    $database = new Database();
    $pdo = $database->connect();
    $files = $pdo->query("SELECT * FROM files")->fetchALL(PDO::FETCH_ASSOC);
    $times = $pdo->query("SELECT * FROM time")->fetchALL(PDO::FETCH_ASSOC);
    $dates = $pdo->query("SELECT * FROM dates")->fetchALL(PDO::FETCH_ASSOC);
    foreach ($times as $key => $time){
        foreach ($dates as $date){
            $cita = date('Y-m-d').' '.$time['time'];
            if($cita === $date['date']) {
                unset($times[$key]);
            }
        }
    }
    $fecha_actual = date('Y-m-d');
    ?>
    <!DOCTYPE html>
    <html lang="">
        <head>
            <title>CandyPet</title>
            <link rel="stylesheet" href="css/bootstrap.min.css" >
            <link rel="stylesheet" href="css/style.css" >
            <script>
                function valida_datos() {
                    const reason_appointment = document.getElementById("reason_appointment");
                    const date = document.getElementById("date");
                    const cont_dates = document.getElementById("cont_dates");
                    const time = document.getElementById("time");

                    if(reason_appointment.value==="") {
                        reason_appointment.setCustomValidity("Campo razon de cita no puede venir vacio");
                    }else{
                        reason_appointment.setCustomValidity("");
                    }

                    if(date.value==="") {
                        date.setCustomValidity("Campo apellido no puede venir vacio");
                    }else{
                        date.setCustomValidity("");
                    }

                    if(cont_dates.value==="") {
                        cont_dates.setCustomValidity("Campo apellido no puede venir vacio");
                    }else{
                           cont_dates.setCustomValidity("");
                    }

                    if(time.value==="") {
                        time.setCustomValidity("Campo horario no debe de venir vacio");
                    }else{
                        time.setCustomValidity("");
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
                            <label class="des_alta" for="fname">Inserta razon de la cita:</label>
                        </div>
                       <div class="col-md-8">
                           <input class="alta" id="reason_appointment" type="text" placeholder="Razon" name="reason_appointment">
                       </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Selecciona horario:</label>
                        </div>
                        <div class="col-md-8">
                            <select class="alta" id="time" name="time">
                                <?php
                                foreach ($times as $time){
                                    echo '<option value="'.$time['time'].'">'.$time['dsc_time'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="des_alta" for="fname">Inserta fecha:</label>
                        </div>
                        <div class="col-md-8">
                            <input class="alta" id="date" type="date" name="date" value="<?php echo $fecha_actual; ?>">
                        </div>
                        <?php if($_SESSION['tipo'] === 'administrador'){?>
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
                        <?php }?>
                        <div class="col-md-12">
                            <button class="btn_alta" type="submit" onclick="valida_datos()">Guardar</button>
                        </div>
                </form>
            </div>
        </body>
    </html>
    <?php
    if(!empty($_POST)){

        $user = $pdo->query("SELECT * FROM users WHERE id =".$_SESSION['id'])->fetchALL(PDO::FETCH_ASSOC);
        $customer = $pdo->query("SELECT * FROM customer WHERE users_id =".$user[0]['id'])->fetchALL(PDO::FETCH_ASSOC);
        $pet = $pdo->query("SELECT * FROM pets WHERE customer_id =".$customer[0]['id'])->fetchALL(PDO::FETCH_ASSOC);

        $_POST['files_id'] = $pet[0]['files_id'];

        $_POST['date']= $_POST['date'].' '.$_POST['time'];
        if(isset($_POST['time']))
            unset($_POST['time']);

        $database = new Database();
        $pdo = $database->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql="INSERT INTO dates (";
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