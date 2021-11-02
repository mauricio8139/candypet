<?php
	include 'database.php';	
	session_start();
	if(isset($_SESSION['id'])){
		$database = new Database();
		$pdo = $database->connect();
		$columnas = $pdo->query("SELECT COLUMN_NAME AS columna FROM information_schema.columns WHERE table_schema = '$database->dbNombre' AND table_name = '$_GET[tabla]'")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="">
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
	<?php
		if(isset($_GET['error']) && (string)$_GET['error']!==''){
			echo '<div class="col-md-12"><p class="error">'.$_GET['error'].'</p></div>';
		}
	?>
		<form class="input_filtro" method="post" onsubmit="">
			<?php
				foreach($columnas AS $campo=>$valor){
					echo '<input class="texto" id="fname" type="text" placeholder="'.$valor['columna'].'" name="'.$valor['columna'].'">';
				}
			?>
			<button class="filtrar" type="submit" name="btn">Filtrar</button>
			<?php echo '<a class="limpiar" href="lista.php?tabla='.$_GET['tabla'].'">Limpiar</a>'?>
		</form>
	<?php
		if(isset($_POST['btn'])){
			$url="Location: lista.php?tabla=".$_GET['tabla']."&filtro=activo";
			foreach ($_POST as $campo => $valor) {
				if (!empty($valor)) {
					$url.="&".$campo."=".$valor;
				}
			}
			header($url);
		}
	?>
	<div class="col-md-12">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<?php
						foreach($columnas AS $campo=>$valor){
							if($_GET['tabla']==="users" && $valor['columna'] === "password"){
								continue;
							}else{
								echo '<th>'.$valor['columna'].'</th>';
							}
						}
					?>
					<th>Modifica</th>
					<th>Elimina</th>
			  	</tr>
			</thead>

			<tbody>
				<?php
					if(isset($_GET['filtro']) && (string)$_GET['filtro']==='activo'){
						$filtro=$_GET;
						unset($filtro['tabla'], $filtro['filtro']);

						$sql="SELECT * FROM $_GET[tabla] WHERE ";
						foreach ($filtro as $campo => $valor) {
							$sql.=$campo.' LIKE "%'.$valor. '%" AND ';
						}
						$sql = trim($sql, 'AND ');
						$tabla = $pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
					}else{
						$tabla = $pdo->query("SELECT * FROM $_GET[tabla]")->fetchALL(PDO::FETCH_ASSOC);
					}
					
					if(count($tabla)>0){
						foreach ($tabla as $campo => $valor) {
							echo '<tr>';
								$res='';
								foreach ($valor as $cam => $val) {
									if($_GET['tabla']==="users" && $cam === "password"){
										continue;
									}else{
										$res .= '<td>' . $val . '</td>';
									}
								}
								echo $res;
                                echo '<td>';
                                echo '<a class="accion" href="modifica.php?tabla='.$_GET['tabla'].'&id='.$valor['id'].'">Modifica</a>';
                                echo '</td>';
                                echo '<td>';
                                echo '<a class="accion" href="elimina.php?tabla='.$_GET['tabla'].'&id='.$valor['id'].'">Borra</a>';
                                echo '</td>';
							echo '</tr>';
						}
					}
				?>
			</tbody>
		</table>
	</div>
</body>
<?php
	}else{
		header('Location: index.php');
	}

?>