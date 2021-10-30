<?php
	include 'database.php';	
	session_start();
	if(isset($_SESSION['usuario'])){
		$database = new Database();
		$pdo = $database->connect();
		$columnas = $pdo->query("SELECT COLUMN_NAME AS columna FROM information_schema.columns WHERE table_schema = '$database->dbNombre' AND table_name = '$_GET[tabla]'")->fetchAll(PDO::FETCH_ASSOC);
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
				if(empty($_POST)){
					if($_GET['tabla'] === 'departamento'){
						$campos = $pdo->query("SELECT * FROM $_GET[tabla] WHERE NUMEROD = $_GET[NUMEROD]")->fetch(PDO::FETCH_ASSOC);
						foreach ($campos as $campo => $valor) {
							if($campo!=='id'){
								echo '<div class="row">
									    <div class="col-md-3">
									      	<label class="des_alta" for="fname">Inserta '.$campo.' :</label>
									    </div>';
								echo '<div class="col-md-9"> 
										<input class="alta" id="fname" type="text" placeholder="'.$campo.'" name="'.$campo.'" value="'.$valor.'">
									</div></div>';
							}
						}
					}else if($_GET['tabla'] === 'dependiente'){	
						$campos = $pdo->query("SELECT * FROM $_GET[tabla] WHERE NSSE = $_GET[NSSE] AND NOMBRE_DEPENDIENTE = '$_GET[NOMBRE_DEPENDIENTE]'")->fetch(PDO::FETCH_ASSOC);
						foreach ($campos as $campo => $valor) {
							if($campo!=='id'){
								echo '<div class="row">
									    <div class="col-md-3">
									      	<label class="des_alta" for="fname">Inserta '.$campo.':</label>
									    </div>';
								echo '<div class="col-md-9"> 
										<input class="alta" id="fname" type="text" placeholder="'.$campo.'" name="'.$campo.'" value="'.$valor.'">
									</div></div>';
							}
						}
					}else if($_GET['tabla'] === 'empleado'){
						$campos = $pdo->query("SELECT * FROM $_GET[tabla] WHERE NSS = $_GET[NSS]")->fetch(PDO::FETCH_ASSOC);
						foreach ($campos as $campo => $valor) {
							if($campo!=='id'){
								echo '<div class="row">
									    <div class="col-md-3">
									      	<label class="des_alta" for="fname">Inserta '.$campo.':</label>
									    </div>';
								echo '<div class="col-md-9"> 
										<input class="alta" id="fname" type="text" placeholder="'.$campo.'" name="'.$campo.'" value="'.$valor.'">
									</div></div>';
							}
						}
					}else if($_GET['tabla'] === 'lugares_deptos'){
						$campos = $pdo->query("SELECT * FROM $_GET[tabla] WHERE NUMEROD = $_GET[NUMEROD] AND LUGARD = '$_GET[LUGARD]'")->fetch(PDO::FETCH_ASSOC);
						foreach ($campos as $campo => $valor) {
							if($campo!=='id'){
								echo '<div class="row">
									    <div class="col-md-3">
									      	<label class="des_alta" for="fname">Inserta '.$campo.':</label>
									    </div>';
								echo '<div class="col-md-9"> 
										<input class="alta" id="fname" type="text" placeholder="'.$campo.'" name="'.$campo.'" value="'.$valor.'">
									</div></div>';
							}
						}
					}else if($_GET['tabla'] === 'proyecto'){
						$campos = $pdo->query("SELECT * FROM $_GET[tabla] WHERE NUMEROP = $_GET[NUMEROP]")->fetch(PDO::FETCH_ASSOC);
						foreach ($campos as $campo => $valor) {
							if($campo!=='id'){
								echo '<div class="row">
									    <div class="col-md-3">
									      	<label class="des_alta" for="fname">Inserta '.$campo.':</label>
									    </div>';
								echo '<div class="col-md-9"> 
										<input class="alta" id="fname" type="text" placeholder="'.$campo.'" name="'.$campo.'" value="'.$valor.'">
									</div></div>';
							}
						}
					}else if($_GET['tabla'] === 'trabaja_en'){
						$campos = $pdo->query("SELECT * FROM $_GET[tabla] WHERE NSSE = $_GET[NSSE] AND NUMEROD = $_GET[NUMEROD] AND FECHAINICGTE  = $_GET[FECHAINICGTE]")->fetch(PDO::FETCH_ASSOC);
						foreach ($campos as $campo => $valor) {
							if($campo!=='id'){
								echo '<div class="row">
									    <div class="col-md-3">
									      	<label class="des_alta" for="fname">Inserta '.$campo.':</label>
									    </div>';
								echo '<div class="col-md-9"> 
										<input class="alta" id="fname" type="text" placeholder="'.$campo.'" name="'.$campo.'" value="'.$valor.'">
									</div></div>';
							}
						}
					}else {
						$campos = $pdo->query("SELECT * FROM $_GET[tabla] WHERE id = $_GET[id]")->fetch(PDO::FETCH_ASSOC);
						foreach ($campos as $campo => $valor) {
							if($campo!=='id'){
								echo '<div class="row">
									    <div class="col-md-3">
									      	<label class="des_alta" for="fname">Inserta '.$campo.':</label>
									    </div>';
								echo '<div class="col-md-9"> 
										<input class="alta" id="fname" type="text" placeholder="'.$campo.'" name="'.$campo.'" value="'.$valor.'">
									</div></div>';
							}
						}
					}
				}else{
					foreach ($_POST as $campo => $valor) {
						if($campo!=='id'){
							echo '<div class="row">
								    <div class="col-md-3">
								      	<label class="des_alta" for="fname">Inserta '.$campo.':</label>
								    </div>';
							echo '<div class="col-md-9"> 
									<input class="alta" id="fname" type="text" placeholder="'.$campo.'" name="'.$campo.'" value="'.$valor.'">
								</div></div>';
						}
					}
				}
			?>
		  	<button class="btn_alta" type="submit">Guardar</button>
		 </form>
	</div>
</body>
<?php
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if($_GET['tabla'] === 'departamento'){
			$sql="UPDATE $_GET[tabla] SET ";
			foreach ($_POST as $campo => $valor) {
				$sql.= $campo."=?, ";
			}
			$sql = trim($sql, ', ');
			$sql.=" WHERE NUMEROD = $_GET[NUMEROD]";

			$q = $pdo->prepare($sql);
				
			foreach ($_POST as $campo => $valor) {
				$valores[] = $valor;
			}		
		}else if($_GET['tabla'] === 'dependiente'){
			$sql="UPDATE $_GET[tabla] SET ";
			foreach ($_POST as $campo => $valor) {
				$sql.= $campo."=?, ";
			}
			$sql = trim($sql, ', ');
			$sql.=" WHERE NSSE = $_GET[NSSE] AND NOMBRE_DEPENDIENTE = '$_GET[NOMBRE_DEPENDIENTE]'";

			$q = $pdo->prepare($sql);
				
			foreach ($_POST as $campo => $valor) {
				$valores[] = $valor;
			}
		}else if($_GET['tabla'] === 'empleado'){
			$sql="UPDATE $_GET[tabla] SET ";
			foreach ($_POST as $campo => $valor) {
				$sql.= $campo."=?, ";
			}
			$sql = trim($sql, ', ');
			$sql.=" WHERE NSS = $_GET[NSS]";

			$q = $pdo->prepare($sql);
				
			foreach ($_POST as $campo => $valor) {
				$valores[] = $valor;
			}
		}else if($_GET['tabla'] === 'lugares_deptos'){
			$sql="UPDATE $_GET[tabla] SET ";
			foreach ($_POST as $campo => $valor) {
				$sql.= $campo."=?, ";
			}
			$sql = trim($sql, ', ');
			$sql.=" WHERE NUMEROD = $_GET[NUMEROD] AND LUGARD = '$_GET[LUGARD]'";

			$q = $pdo->prepare($sql);
				
			foreach ($_POST as $campo => $valor) {
				$valores[] = $valor;
			}
		}else if($_GET['tabla'] === 'proyecto'){
			$sql="UPDATE $_GET[tabla] SET ";
			foreach ($_POST as $campo => $valor) {
				$sql.= $campo."=?, ";
			}
			$sql = trim($sql, ', ');
			$sql.=" WHERE NUMEROP = $_GET[NUMEROP]";

			$q = $pdo->prepare($sql);
				
			foreach ($_POST as $campo => $valor) {
				$valores[] = $valor;
			}
		}else if($_GET['tabla'] === 'trabaja_en'){
			$sql="UPDATE $_GET[tabla] SET ";
			foreach ($_POST as $campo => $valor) {
				$sql.= $campo."=?, ";
			}
			$sql = trim($sql, ', ');
			$sql.=" WHERE NSSE = $_GET[NSSE] AND NUMEROD = $_GET[NUMEROD] AND FECHAINICGTE  = $_GET[FECHAINICGTE]";

			$q = $pdo->prepare($sql);
				
			foreach ($_POST as $campo => $valor) {
				$valores[] = $valor;
			}
		}else {
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
		}

     	$q->execute($valores);
     	$pdo = $database->disconnect();
	}else{
		header('Location: index.php');
	}

?>