<?php

require_once "pdo.php";

$ID=$_SESSION['factura.id'];

$subtotal=0;

$Obtener_Encabezado=$conexion->prepare("SELECT * FROM factura_datos WHERE ID=:id");
   $Obtener_Encabezado->bindParam(':id',$ID);
   $Obtener_Encabezado->execute();
   foreach ($Obtener_Encabezado as $Encabezado){
       $ID=$Encabezado['ID'];
       $fecha=$Encabezado['fecha'];
       $tipo=$Encabezado['tipo'];
       $id_cliente=$Encabezado['id_cliente'];
       $nombre_cliente=$Encabezado['nombre_cliente'];
       $direccion_cliente=$Encabezado['direccion_cliente'];
       $cuit_cliente=$Encabezado['cuit_cliente'];
       $sit_iva_cliente=$Encabezado['sit_iva_cliente'];
       $cond_venta=$Encabezado['cond_venta'];
       $iva_10=$Encabezado['iva_10'];
       $iva_21=$Encabezado['iva_21'];
       $iva_27=$Encabezado['iva_27'];
       $imp_interno=$Encabezado['imp_interno'];
       break;       
   }
$ObtenerMiCUIT=$conexion->query("SELECT * FROM companydata");
foreach ($ObtenerMiCUIT as $Dato){
    $nombre=$Dato['name'];
    $CUIT=$Dato['tribut_id'];
    $fecha_inicio="01/03/2019";
    $domicilio=$Dato['location'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Imprimir Factura</title>
</head>
<body onload="window.print();">

   <h4 style="font-weigth:bold;font-size:1.5em;text-align:center;margin-top:50px;"><?php echo $tipo;?></h1>
   
   <h3 style="margin-left:550px;margin-top:-15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0008&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "000000".$ID;?></h3>
   <h4 style="margin-left:600px;margin-top:-15px;"><?php echo $fecha;?></h4>
   <h4 style="margin-left:550px;margin-top:-15px;"><?php echo $CUIT;?></h4>
   <h4 style="margin-left:680px;margin-top:-5px;"><?php echo $fecha_inicio;?></h4>

   <h4 style="margin-left:80px;margin-top:-100px;"><?php echo $nombre;?></h4>
   <h4 style="margin-left:150px;"><?php echo $domicilio;?></h4>

<br>

   <h4 style="margin-left:30px;margin-top:-1px;"><?php echo $cuit_cliente;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nombre_cliente;?></h4>
   <h4 style="margin-left:150px;margin-top:-1px;"><?php echo $sit_iva_cliente;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $direccion_cliente;?></h4>
   <h4 style="margin-left:150px;margin-top:-1px"><?php echo $cond_venta;?></h4>
    
    <table>
    <tr>
    </tr>
   <?php 

    $ObtenerProductos=$conexion->prepare("SELECT * FROM factura_productos WHERE ID_factura=:id");
    $ObtenerProductos->bindParam(':id',$ID);
    $ObtenerProductos->execute();
    
    foreach($ObtenerProductos as $Producto1){ $subtotal=$subtotal+($Producto1['precio']*$Producto1['cantidad']);?>
        <tr>
            <td style="width:50px;"><?php echo $Producto1['codigo'];?></td>
            <td style="width:200px;"><?php echo $Producto1['denominacion'];?></td>
            <td style="width:80px;"><?php echo $Producto1['cantidad'];?></td>
            <td style="width:80px;"><?php echo $Producto1['iva'];?></td>
            <td style="width:80px;"><?php echo "$".$Producto1['precio'];?></td>
            <td style="width:80px;">0%</td>
            <td style="width:80px;"><?php echo $Producto1['imp_interno'];?></td>
            <td style="width:80px;"><?php echo "$".number_format($Producto1['total'],2);?></td>
        </tr> 
    <?php } ?>
    </table>

    <h4 style="margin-left:680px;transform:translateY(430px);margin-top:-25px;"><?php echo number_format($subtotal,2);?></h4>
    <h4 style="margin-left:680px;transform:translateY(430px);margin-top:-15px;"><?php echo number_format($iva_10,2);?></h4>
    <h4 style="margin-left:680px;transform:translateY(430px);margin-top:-20px;"><?php echo number_format($iva_21,2);?></h4>
    <h4 style="margin-left:680px;transform:translateY(430px);margin-top:-15px;"><?php echo number_format($iva_27,2);?></h4>
    <h4 style="margin-left:680px;transform:translateY(430px);margin-top:-15px;"><?php echo number_format($imp_interno,2);?></h4>
    <?php $total=$subtotal+$iva_10+$iva_21+$iva_27+$imp_interno;?>
    <h4 style="margin-left:680px;transform:translateY(430px);margin-top:-15px;"><?php echo number_format($total,2);?></h4>


   
    
</body>
</html>