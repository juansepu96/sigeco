<?php

require_once "pdo.php";

$consultaBusqueda = $_POST['valorBusqueda'];

$description="";

if(isset($consultaBusqueda)){
    $Consulta=$conexion->prepare("SELECT * FROM pdv WHERE ID=:valor");
    $Consulta->bindParam(':valor',$consultaBusqueda);
    $Consulta->execute();

    $filas=$Consulta->rowcount();

    if($filas==0){
        $result='false';
    }else{
        $result='true';
        foreach($Consulta as $Date){
            $description=$Date['description'];
        }

    }

}

echo $description;


?>