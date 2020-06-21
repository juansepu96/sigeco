<?php

require_once "pdo.php";

$consultaBusqueda = $_POST['valorBusqueda1'];

$description="";
$resultado="";


if(isset($consultaBusqueda)){
    $Consulta=$conexion->prepare("SELECT * FROM products WHERE ID=:valor");
    $Consulta->bindParam(':valor',$consultaBusqueda);
    $Consulta->execute();

    $filas=$Consulta->rowcount();

    if($filas==0){
        $result='false';
    }else{
        $result='true';
        foreach($Consulta as $Datos){
            if($Datos['stock']>0){
                $resultado=$resultado.'/'.$Datos['name'];
                $resultado=$resultado.'/'."$".$Datos['precio_neto'];
                $resultado=$resultado.'/'.$Datos['IVA']."%";
                if($Datos['imp_interno']!="0"){
                    if($Datos['imp_interno']=='%'){                        
                      $resultado=$resultado.'/'.$Datos['imp_interno_valor']."%";
                    }else{
                      $resultado=$resultado.'/'."$ ".$Datos['imp_interno_valor'];
                    }
                }else{                    
                 $resultado=$resultado.'/'."---";
                }
            }else{
                $resultado="E1";
            }
            
            
            
            
        }

    }

}

$description=$resultado;

echo $description


?>