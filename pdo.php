<?php

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

$date=date("Y-m-d");
$time=date("H:i:s");

$dbname=$_SESSION['company.dbname'];


try {
	$conexion = new PDO('mysql:host=localhost;dbname='.$dbname,'root','');


}catch(PDOException $e){
		echo "Error" . $e->getMessage();

}

?>