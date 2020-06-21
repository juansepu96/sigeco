<?php

require_once "pdo.php";


    $tipo=$_SESSION['tipo.factura'];
    
    $fecha_desde=$_SESSION['fechad.factura'];
    $fecha_hasta=$_SESSION['fechah.factura'];

    $num_desde=$_SESSION['numd.factura'];
    $num_hasta=$_SESSION['numh.factura'];
    
    if($tipo=="TODAS"){
        $GetFacturas=$conexion->prepare("SELECT * FROM factura_datos WHERE ( (fecha BETWEEN :fecha1 AND :fecha2) AND (ID BETWEEN :num1 AND :num2)) ORDER BY fecha DESC");
        $GetFacturas->bindParam(':fecha1',$fecha_desde);
        $GetFacturas->bindParam(':fecha2',$fecha_hasta);
        $GetFacturas->bindParam(':num1',$num_desde);
        $GetFacturas->bindParam(':num2',$num_hasta);
        $GetFacturas->execute();
    }else{
        $GetFacturas=$conexion->prepare("SELECT * FROM factura_datos WHERE ( (tipo=:tipo) AND (fecha BETWEEN :fecha1 AND :fecha2) AND (ID BETWEEN :num1 AND :num2)) ORDER BY fecha DESC");
        $GetFacturas->bindParam(':tipo',$tipo);
        $GetFacturas->bindParam(':fecha1',$fecha_desde);
        $GetFacturas->bindParam(':fecha2',$fecha_hasta);
        $GetFacturas->bindParam(':num1',$num_desde);
        $GetFacturas->bindParam(':num2',$num_hasta);
        $GetFacturas->execute();
    }    
$total_iva10=0;
$total_iva21=0;
$total_iva27=0;
$total_imp_interno=0;
$total_neto=0;

header("Pragma: public");
header("Expires: 0");
$filename = "SiGeCo v1.0 - Facturas Export-.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Exportar facturas - SiGeCo v1.0</title>
</head>
<body onload="window.close();">

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
    </div>
    <div class="content">

        <div class="table">   
        <h2 style="text-align:center;padding:10px;">LISTAR FACTURAS</h2>

        <h2 style="text-align:left;padding:10px;">Filtros usados:</h2>  

        Tipo de Factura: <?php echo $tipo;?>  Fecha Desde: <?php echo $fecha_desde;?>  Fecha Hasta: <?php echo $fecha_hasta;?>  Num. desde: <?php echo $num_desde;?>  Num. hasta: <?php echo $num_hasta;?>  

        <table style="width:100%">
                <tr>
                    <th>Nro.</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Cliente</th>
                    <th>Cond. Venta</th>
                    <th>Neto</th>
                    <th>IVA Total</th>
                    <th>Imp. Interno</th>
                    <th>TOTAL FACTURA</th>

                </tr>
                <?php foreach ($GetFacturas as $Factura) { ?>
                    <form method="post" action="listar_facturas.php">
                    <tr>
                        <input hidden name="factura_id" style="text-align:center;" value="<?php echo $Factura['ID'];?>">
                        <?php $total_neto=$total_neto+$Factura['neto'];?>
                        <?php $total_iva10=$total_iva10+$Factura['iva_10'];?>
                        <?php $total_iva21=$total_iva21+$Factura['iva_21'];?>
                        <?php $total_iva27=$total_iva27+$Factura['iva_27'];?>
                        <?php $total_imp_interno=$total_imp_interno+$Factura['imp_interno'];?>
                        <td style="text-align:center;"><?php echo "0008-"."0000".$Factura['ID'];?></td>
                        <td style="text-align:center;"><?php echo $Factura['fecha'];?></td>
                        <td style="text-align:center;"><?php echo $Factura['tipo'];?></td>
                        <td style="text-align:center;"><?php echo $Factura['nombre_cliente'];?></td>
                        <td style="text-align:center;"><?php echo $Factura['cond_venta'];?></td>
                        <td style="text-align:right;"><?php echo "$ ".number_format($Factura['neto'],2);?></td>
                        <td style="text-align:right;"><?php $total=$Factura['neto']+$Factura['iva_10']+$Factura['iva_21']+$Factura['iva_27']; echo "$ ".number_format(($Factura['iva_10']+$Factura['iva_21']+$Factura['iva_27']),2);?></td>
                        <td style="text-align:right;"><?php $total=$total+$Factura['imp_interno']; echo "$".number_format($Factura['imp_interno'],2);?></td>
                        <td style="text-align:right;"><?php echo "$ ".number_format($total,2);?></td>
                        
                        </tr>
                    </form>
                <?php } ?>

            </table>

                    <br>
                    <h4 style="text-align:right;">TOTAL NETO  $ <?php echo number_format($total_neto,2);?></h4>

            <h4 style="text-align:right;">IVA AL 10.5%  $ <?php echo number_format($total_iva10,2);?></h4>
            <h4 style="text-align:right;">IVA AL 21%  $ <?php echo number_format($total_iva21,2);?></h4>
            <h4 style="text-align:right;">IVA AL 27%  $ <?php echo number_format($total_iva27,2);?></h4>
            <h4 style="text-align:right;">IMP. INTERNO  $ <?php echo number_format($total_imp_interno,2);?></h4>

        </div>

    </div>

</body>
</html>


    
</body>
</html>

