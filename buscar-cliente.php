<?php

require_once "pdo.php";

$consultaBusqueda = $_POST['valorBusqueda'];

$description="";
$resultado="";

$consultaBusqueda=explode('/',$consultaBusqueda);

if(isset($consultaBusqueda)){
    $Consulta=$conexion->prepare("SELECT * FROM clientes WHERE ID=:valor");
    $Consulta->bindParam(':valor',$consultaBusqueda[0]);
    $Consulta->execute();

    $filas=$Consulta->rowcount();

    if($filas==0){
        $result='false';
    }else{
        $result='true';
        foreach($Consulta as $Datos){
            if(($consultaBusqueda[1]=='A')AND($Datos['sit_iva']=="Responsable Inscripto")){
                $resultado=$resultado.'/'.$Datos['nombre'];
                $resultado=$resultado.'/'.$Datos['cuit'];
                $resultado=$resultado.'/'.$Datos['sit_iva'];
                $resultado=$resultado.'/'.$Datos['domicilio'];
            }else{
                if(($consultaBusqueda[1]=='B')AND($Datos['sit_iva']!="Responsable Inscripto")){
                    $resultado=$resultado.'/'.$Datos['nombre'];
                    $resultado=$resultado.'/'.$Datos['cuit'];
                    $resultado=$resultado.'/'.$Datos['sit_iva'];
                    $resultado=$resultado.'/'.$Datos['domicilio'];
                }else{
                    $resultado="E1";
                }
            }
            
            
            
            
        }

    }

}

$description=$resultado;

echo $description


?>