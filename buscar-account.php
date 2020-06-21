<?php

require_once "pdo.php";

$consultaBusqueda = $_POST['valorBusqueda'];

$description="";

if(isset($consultaBusqueda)){
    $Consulta=$conexion->prepare("SELECT * FROM accounts WHERE code=:valor AND type=1");
    $Consulta->bindParam(':valor',$consultaBusqueda);
    $Consulta->execute();

    $filas=$Consulta->rowcount();

    if($filas==0){
        $result='false';
    }else{
        $result='true';
        foreach($Consulta as $Account){
            $description=$Account['name'];
        }

    }

}

echo $description;


?>