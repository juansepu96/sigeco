<?php

require_once "pdo.php";


if(isset($_POST['anular'])){
    $ID=$_POST['factura_id'];
    $ActualizarFactura=$conexion->prepare("UPDATE factura_datos SET anulada='SI' WHERE ID=:id");
    $ActualizarFactura->bindParam(':id',$ID);
    $ActualizarFactura->execute();
}

$GetFacturas=$conexion->query("SELECT * FROM factura_datos ORDER BY fecha DESC");


if(isset($_POST['filter'])){
    $tipo=$_POST['tipo_factura'];
    
    $fecha_desde=$_POST['fechadesde'];
    $fecha_hasta=$_POST['fechahasta'];

    $num_desde=$_POST['numdesde'];
    $num_hasta=$_POST['numhasta'];
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




?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Anular una factura - SiGeCo v1.0</title>
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
        <h2 style="text-align:center;padding:10px;">ANULAR UNA FACTURA</h2>
        <br>
        <form method="post" action="anular_factura.php">
                <div id="auditory-filters">
                    <strong style="margin-left:10px;">Factura:</strong>
                    <select style="width:180px;" name="tipo_factura" class="list">
                        <option value="TODAS">TODAS</option>
                        <option value="A">FACTURA A</option>
                        <option value="B">FACTURA B</option>
                    </select>

                    <strong style="margin-right:10px;">Fecha desde:</strong><input class="newseller-field" type="date" value="2019-01-01" style="padding:10px;width:130px;" name="fechadesde"></strong>
                    <strong style="margin-right:10px;">Fecha hasta:</strong><input class="newseller-field" value="<?php echo date('Y-m-d');?>" type="date" style="padding:10px;width:130px;" name="fechahasta"></strong>
                    
                    <strong style="margin-right:10px;">Desde Nro:</strong><input class="newseller-field" type="numer" value="1" style="padding:10px;width:80px;" name="numdesde"></strong>
                    <strong style="margin-right:10px;">Hasta Nro:</strong><input class="newseller-field" type="numer" value="999999" style="padding:10px;width:80px;" name="numhasta"></strong>
                    <input type="submit" class="menu2-button" style="width:100px;color:white;" name="filter" value="Filtrar">
                </div>
        </form>  
              
        <!-- <?php if ($filtre="true") { ?>
            <form method="post" action="anular_factura.php" style="padding-left:500px;">
                <input type="submit" name="print2" value="IMPRIMIR" class="menu2-button">
                <input type="submit" name="excel2" value="EXCEL" class="menu2-button">
            </form>  

        <?php }else{  ?>

            <form method="post" action="anular_factura.php" style="padding-left:500px;">
                    <input type="submit" name="print" value="IMPRIMIR" class="menu2-button">
                    <input type="submit" name="excel" value="EXCEL" class="menu2-button">
            </form> 

        <?php } ?>

        !-->
       

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
                    <th>ANULAR</th>

                </tr>
                <?php foreach ($GetFacturas as $Factura) { ?>
                    <form method="post" action="anular_factura.php">
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
                        <?php if($Factura['anulada']=='NO'){?>
                            <td style="background:red;cursor:pointer;"><input readonly onclick="if(confirm('ESTÁ A PUNTO DE ANULAR ESTA FACTURA, DESEA CONTINUAR?')){
                                this.form.submit();}
                                else{ alert('Operacion Cancelada');}" style="cursor:pointer;background:red;color:white;text-align:center;width:40px;" value="ANUL." name="anular"></td>
                    
                        <?php }else{ ?>
                            <td disabled style="background:blue;color:white;">ANULADA</td>
                        <?php } ?>
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

