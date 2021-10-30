<?php
class Database {
	public $dbNombre = 'veterinaria';
	public $dbHost = 'localhost';
	public $dbUser = 'root';
	public $dbUserPass = 'Karate001.';

	public $dbLink = null;

	public function connect() {
		if ( null === $this->dbLink ){
			try{
		    	$this->dbLink = new PDO( "mysql:host=".$this->dbHost.";"."dbname=".$this->dbNombre, 
		    		$this->dbUser, $this->dbUserPass);
		  	}catch(PDOException $e){
		    	echo $e->getMessage();
		  	}
		} 

		return $this->dbLink;
	}

	public function disconnect(){
		return $this->dbLink = null;
	}
}