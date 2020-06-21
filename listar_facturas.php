<?php

require_once "pdo.php";

$filtre="false";




$GetFacturas=$conexion->query("SELECT * FROM factura_datos ORDER BY fecha DESC");

if(isset($_POST['imp_factura'])){
    $ID=$_POST['factura_id'];
    $_SESSION['factura.id']=$ID;
    echo "<script>window.open('factura_print.php', '_blank');</script>";

}


if(isset($_POST['filter'])){
    $filtre="true";
    $tipo=$_POST['tipo_factura'];
    $_SESSION['tipo.factura']=$tipo;
    
    $fecha_desde=$_POST['fechadesde'];
    $_SESSION['fechad.factura']=$fecha_desde;
    $fecha_hasta=$_POST['fechahasta'];
    $_SESSION['fechah.factura']=$fecha_hasta;

    $num_desde=$_POST['numdesde'];
    $_SESSION['numd.factura']=$num_desde;
    $num_hasta=$_POST['numhasta'];
    $_SESSION['numh.factura']=$num_hasta;
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

}

if(isset($_POST['print'])){
    echo "<script>window.open('facturas-print.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    echo "<script>window.open('facturas-excel.php', '_blank');</script>";
}

if(isset($_POST['print2'])){
    echo "<script>window.open('facturas-print2.php', '_blank');</script>";
}

if(isset($_POST['excel2'])){
    echo "<script>window.open('facturas-excel2.php', '_blank');</script>";
}




?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Listar facturas - SiGeCo v1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "factu_menu.php"; ?>
    </div>
    <div class="content">

        <div class="table">   
        <h2 style="text-align:center;padding:10px;">LISTAR FACTURAS</h2>
        <br>
        <form method="post" action="listar_facturas.php">
                <div id="auditory-filters">
                    <strong style="margin-left:10px;">Factura:</strong>
                    <select style="width:180px;" name="tipo_factura" class="list">
                        <option value="TODAS">TODAS</option>
                        <option value="A">FACTURA A</option>
                        <option value="B">FACTURA B</option>
                    </select>

                    <strong style="margin-right:10px;">Fecha desde:</strong><input class="newseller-field" type="date" value="2019-01-01" style="padding:10px;width:130px;" name="fechadesde"></strong>
                    <strong style="margin-right:10px;">Fecha hasta:</strong><input class="newseller-field" value="<?php echo date('Y-m-d');?>" type="date" style="padding:10px;width:130px;" name="fechahasta"></strong>
                    
                    <strong style="margin-right:10px;">Desde Nro:</strong><input class="newseller-field" type="number" value="1" style="padding:10px;width:80px;" name="numdesde"></strong>
                    <strong style="margin-right:10px;">Hasta Nro:</strong><input class="newseller-field" type="number" value="999999" style="padding:10px;width:80px;" name="numhasta"></strong>
                    <input type="submit" class="menu2-button" style="width:100px;color:white;" name="filter" value="Filtrar">
                </div>
        </form>  
              
         <?php if ($filtre=="true") { ?>
            <form method="post" action="listar_facturas.php" style="padding-left:500px;">
                <input type="submit" name="print2" value="IMPRIMIR" class="menu2-button">
                <input type="submit" name="excel2" value="EXCEL" class="menu2-button">
            </form>  

        <?php }else{  ?>

            <form method="post" action="listar_facturas.php" style="padding-left:500px;">
                    <input type="submit" name="print" value="IMPRIMIR" class="menu2-button">
                    <input type="submit" name="excel" value="EXCEL" class="menu2-button">
            </form> 

        <?php } ?>
  

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
                    <th>IMPRIMIR</th>

                </tr>
                <?php foreach ($GetFacturas as $Factura) { ?>
                    <form method="post" action="listar_facturas.php">
                    <tr>
                        <input hidden name="factura_id" style="text-align:center;" value="<?php echo $Factura['ID'];?>">
                        <td style="text-align:center;"><?php echo "0008-"."0000".$Factura['ID'];?></td>
                        <td style="text-align:center;"><?php echo $Factura['fecha'];?></td>
                        <td style="text-align:center;"><?php echo $Factura['tipo'];?></td>
                        <td style="text-align:center;"><?php echo $Factura['nombre_cliente'];?></td>
                        <td style="text-align:center;"><?php echo $Factura['cond_venta'];?></td>
                        <td style="text-align:right;"><?php echo "$ ".number_format($Factura['neto'],2);?></td>
                        <td style="text-align:right;"><?php $total=$Factura['neto']+$Factura['iva_10']+$Factura['iva_21']+$Factura['iva_27']; echo "$ ".number_format(($Factura['iva_10']+$Factura['iva_21']+$Factura['iva_27']),2);?></td>
                        <td style="text-align:right;"><?php $total=$total+$Factura['imp_interno']; echo "$".number_format($Factura['imp_interno'],2);?></td>
                        <td style="text-align:right;"><?php echo "$ ".number_format($total,2);?></td>
                        <td style="text-align:center;background:blue;"><input style="cursor:pointer;background:blue;color:white;" type="submit" value="IMPRIMIR" name="imp_factura"></td>
                        </tr>
                    </form>
                <?php } ?>

            </table>

            


        </div>

    </div>

</body>
</html>


    
</body>
</html>

