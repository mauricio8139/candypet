<?php
	include 'database.php';	
	session_start();
	if(isset($_SESSION['id'])){
		$database = new Database();
		$pdo = $database->connect();
?>
<!DOCTYPE html>
<html>
<head>
	<title>CandyPet</title>
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
	<div class="col-md-12">
		<?php
			$tablas = $pdo->query("SELECT table_name AS nombre FROM information_schema.tables WHERE table_schema = '$database->dbNombre';")->fetchAll(PDO::FETCH_COLUMN);
			foreach($tablas AS $tabla){
                if($tabla==='customer' && $_SESSION['tipo'] === 'administrador'){
                    echo '<h1>Cliente</h1>';
                }else if($tabla==='dates' && $_SESSION['tipo'] === 'administrador'){
                    echo '<h1>Citas</h1>';
                }else if($tabla==='files' && $_SESSION['tipo'] === 'administrador'){
                    echo '<h1>Archivos</h1>';
                }else if($tabla==='pets' && $_SESSION['tipo'] === 'administrador'){
                    echo '<h1>Mascotas</h1>';
                }else if($tabla==='recipes'){
                    echo '<h1>Recetas</h1>';
                }else if($tabla==='users' && $_SESSION['tipo'] === 'administrador'){
                    echo '<h1>Usuario</h1>';
                }
		?>

		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<?php
						$columnas = $pdo->query("SELECT COLUMN_NAME AS columna FROM information_schema.columns WHERE table_schema = '$database->dbNombre' AND table_name = '$tabla'")->fetchAll(PDO::FETCH_COLUMN);
                        if($_SESSION['tipo'] === 'administrador') {
                            foreach ($columnas as $campo => $valor) {
                                if ($valor === 'password') {
                                    continue;
                                } else {
                                    echo '<th>' . $valor . '</th>';
                                }
                            }
                        }else if($tabla === 'recipes'){
                            foreach ($columnas as $campo => $valor) {
                                if ($valor === 'password') {
                                    continue;
                                } else {
                                    echo '<th>' . $valor . '</th>';
                                }
                            }
                        }
					?>
			  	</tr>
			</thead>

			<tbody>
				<?php
                    if($_SESSION['tipo']==='administrador') {
                        $tab = $pdo->query('SELECT * FROM ' . $tabla)->fetchALL(PDO::FETCH_ASSOC);
                    }else{
                        $user = $pdo->query("SELECT * FROM users WHERE id =".$_SESSION['id'])->fetchALL(PDO::FETCH_ASSOC);
                        $customer = $pdo->query("SELECT * FROM customer WHERE users_id =".$user[0]['id'])->fetchALL(PDO::FETCH_ASSOC);
                        $pet = $pdo->query("SELECT * FROM pets WHERE customer_id =".$customer[0]['id'])->fetchALL(PDO::FETCH_ASSOC);
                        $tab = $pdo->query("SELECT * FROM recipes WHERE files_id =".$pet[0]['files_id'])->fetchALL(PDO::FETCH_ASSOC);
                    }
					if($tabla==='customer' && $_SESSION['tipo'] === 'administrador'){
						foreach ($tab as $campo=>$valor) {
							echo '<tr>';
							echo '<td>' . $valor['id'] . '</td>';
							echo '<td>' . $valor['name'] . '</td>';
							echo '<td>' . $valor['last_name'] . '</td>';
							echo '<td>' . $valor['phone'] . '</td>';
							echo '<td>' . $valor['address'] . '</td>';
							echo '<td>' . $valor['users_id'] . '</td>';
							echo '</tr>';
						}
					}else if($tabla==='dates' && $_SESSION['tipo'] === 'administrador'){
                        foreach ($tab as $campo=>$valor) {
                            echo '<tr>';
                            echo '<td>' . $valor['id'] . '</td>';
                            echo '<td>' . $valor['reason_appointment'] . '</td>';
                            echo '<td>' . $valor['date'] . '</td>';
                            echo '<td>' . $valor['cont_dates'] . '</td>';
                            echo '<td>' . $valor['files_id'] . '</td>';
                            echo '</tr>';
                        }
					}else if($tabla==='files' && $_SESSION['tipo'] === 'administrador'){
                        foreach ($tab as $campo=>$valor) {
                            echo '<tr>';
                            echo '<td>' . $valor['id'] . '</td>';
                            echo '<td>' . $valor['date_files'] . '</td>';
                            echo '<td>' . $valor['dsc_files'] . '</td>';
                            echo '</tr>';
                        }
					}else if($tabla==='pets' && $_SESSION['tipo'] === 'administrador'){
                        foreach ($tab as $campo=>$valor) {
                            echo '<tr>';
                            echo '<td>' . $valor['id'] . '</td>';
                            echo '<td>' . $valor['customer_id'] . '</td>';
                            echo '<td>' . $valor['files_id'] . '</td>';
                            echo '<td>' . $valor['name_pet'] . '</td>';
                            echo '<td>' . $valor['specie'] . '</td>';
                            echo '<td>' . $valor['breed'] . '</td>';
                            echo '</tr>';
                        }
					}else if($tabla==='recipes'){
                        foreach ($tab as $campo=>$valor) {
                            echo '<tr>';
                            echo '<td>' . $valor['id'] . '</td>';
                            echo '<td>' . $valor['cont_recipes'] . '</td>';
                            echo '<td>' . $valor['dsc_recipes'] . '</td>';
                            echo '<td>' . $valor['date_recipe'] . '</td>';
                            echo '<td>' . $valor['files_id'] . '</td>';
                            echo '</tr>';
                        }
					}else if ($tabla==='users' && $_SESSION['tipo'] === 'administrador'){
						foreach ($tab as $campo=>$valor) {
							echo '<tr>';
							echo '<td>' . $valor['id'] . '</td>';
							echo '<td>' . $valor['email'] . '</td>';
							echo '<td>' . $valor['type_user'] . '</td>';
							echo '</tr>';
						}
					}

				?>
			</tbody>
		</table>
		<?php  } ?>
	</div>
</body>
<?php
	}else{
		header('Location: login.php');
	}

?>