<?php
	include 'database.php';	
	session_start();
	if(isset($_SESSION['id'])){
		$database = new Database();
		$tabla = $_GET['tabla'];
	    $id = $_GET['id'];
		$pdo = $database->connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "DELETE FROM ".$tabla." WHERE id = ".$id;
		$q = $pdo->prepare($sql);

		$error='';
		if($q->execute()){
				header("Location: lista.php?tabla=".$tabla);
		}else{
		   	$arr = $q->errorInfo();
			$error=$arr['2']; 	
			header("Location: lista.php?tabla=".$tabla."&error=".$error);
		}
	}else{
		header('Location: index.php');
	}