<?php

require_once "pdo.php";

$consultaBusqueda = $_POST['valorBusqueda'];

$description="";

if(isset($consultaBusqueda)){
    $Consulta=$conexion->query("SELECT * FROM companydata");

        foreach($Consulta as $Account){
            if(($consultaBusqueda>=$Account['inicio_fiscal']) && ($consultaBusqueda<=$Account['fin_fiscal'])){
                $BuscarAsset=$conexion->query("SELECT * FROM assets WHERE type=0 AND status<>0");
                $filas=$BuscarAsset->rowcount();
                if($filas>0){
                    foreach ($BuscarAsset as $Asset){
                        if ($consultaBusqueda<$Asset['date']){
                            $description="a";
                        }
                    }
                    
                }

                $BuscarAsset=$conexion->query("SELECT * FROM assets WHERE type=9 AND status<>0");
                $filas=$BuscarAsset->rowcount();
                if ($filas>0){
                    foreach ($BuscarAsset as $Asset){
                        if ($consultaBusqueda>$Asset['date']){
                            $description="b";
                        }
                    }
                }
            }else{
                $description="c";
            }

        }


        $ObtenerUltImpresion=$conexion->query("SELECT * from lib_diario ORDER BY date DESC");
        if($ObtenerUltImpresion->Rowcount()>0){        
        foreach ($ObtenerUltImpresion as $Ultima){
            $fecha=$Ultima['date'];
            break;
        }
        if($consultaBusqueda<=$fecha){
            $description="d";
        }

     }


}

echo $description;


?>