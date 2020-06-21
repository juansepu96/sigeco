<?php

require_once "pdo.php";


$consultaBusqueda = $_POST['valorBusqueda'];

$description="";

if(isset($consultaBusqueda)){
    $Consulta=$conexion->prepare("SELECT * FROM companydata WHERE validatekey=:valor");
    $Consulta->bindParam(':valor',$consultaBusqueda);
    $Consulta->execute();

    $filas=$Consulta->rowcount();

    if($filas>0){
        $description="";
    }else{
        $description="ERROR";
    }

}

echo $description;


?>