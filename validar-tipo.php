<?php


require_once "pdo.php";


$consultaBusqueda = $_POST['valorBusqueda'];

$description="";

if(isset($consultaBusqueda)){
    $Consulta=$conexion->query("SELECT * FROM assets WHERE status=1");

    $filas=$Consulta->rowcount();

    if($filas>=1){
        foreach($Consulta as $Unique){
            if(($consultaBusqueda!="5") AND (intval($Unique['type'])==intval($consultaBusqueda))){
                $description="hay";
                break;
            }
            else{
                $description="";
            }
        }
    }



}

echo $description;


?>