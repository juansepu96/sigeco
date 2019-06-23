<?php

try {
	$conexion = new PDO('mysql:host=localhost;dbname=sigeusuv3','root','');

    }catch(PDOException $e){
            echo "Error" . $e->getMessage();

}

$consultaBusqueda = $_POST['valorBusqueda'];

$description="";

if(isset($consultaBusqueda)){
    $Consulta=$conexion->prepare("SELECT * FROM uniques_keys WHERE unique_key=:valor");
    $Consulta->bindParam(':valor',$consultaBusqueda);
    $Consulta->execute();

    $filas=$Consulta->rowcount();

    if($filas>=1){
                $description="YES";
            }
            else{
                $description="";
    }

}

echo $description;


?>