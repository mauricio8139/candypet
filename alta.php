<?php
	include 'database.php';	
	session_start();
	if(isset($_SESSION['usuario'])){
		$database = new Database();
		$pdo = $database->connect();
		$columnas = $pdo->query("SELECT COLUMN_NAME AS columna, COLUMN_TYPE AS tipo FROM information_schema.columns WHERE table_schema = '$database->dbNombre' AND table_name = '$_GET[tabla]'")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Proyecto Base de Datos</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" >
	<link rel="stylesheet" href="css/style.css" >
</head>
<body>
    <div class="col-md-12">
        <ul class="menu_sis">
            <li><a href="inicio.php" class="dropbtn obj_list">Inicio</a>
                <?php
                $tablas = $pdo->query("SELECT table_name AS nombre FROM information_schema.tables WHERE table_schema = '$database->dbNombre';")->fetchAll(PDO::FETCH_COLUMN);
                foreach($tablas AS $tabla){
                    if($tabla==='customer'){
                        echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Cliente</a>';
                    }else if($tabla==='dates'){
                        echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Citas</a>';
                    }else if($tabla==='files'){
                        echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Archivos</a>';
                    }else if($tabla==='pets'){
                        echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Mascotas</a>';
                    }else if($tabla==='recipes'){
                        echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Recetas</a>';
                    }else {
                        echo '<li class="dropdown"><a href="javascript:void(0)" class="dropbtn obj_list">Usuario</a>';
                    }
                    echo '<div class="dropdown-content">';
                    echo '<a class="stl_accion" href="alta.php?tabla='.$tabla.'">Alta</a>';
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
				foreach($columnas AS $campo => $valor){
					if($valor['columna']==='id'){
						continue;
					}else{
						echo '<div class="row">
								<div class="col-md-3">
								    <label class="des_alta" for="fname">Inserta '.$valor['columna'].' :</label>
								</div>';
						echo '<div class="col-md-8"> 
									<input class="alta" id="fname" type="text" placeholder="'.$valor['columna'].'" name="'.$valor['columna'].'">
								</div></div>';
					}
				}
			?>
		  	<button class="btn_alta" type="submit">Guardar</button>
		</form>
	</div>
</body>
<?php
		if(!empty($_POST)){
			$database = new Database('empresa');
			$pdo = $database->connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql="INSERT INTO $_GET[tabla] (";
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