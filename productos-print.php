<?php

require_once "pdo.php";

$GetProductos=$conexion->query("SELECT * FROM products ORDER BY name ASC");




?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Imprimir Productos - SiGeCo v1.0</title>
</head>
<body onload="window.print();window.close();">
    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
    </div>

    <div class="content">

        <div class="table">

        <table style="width:100%">
                <tr>
                    <th>Denominación del artículo</th>
                    <th>Cantidad en existencia</th>
                    <th>Costo Unitario</th>
                    <th>Precio neto de venta</th>
                    <th>Tasa de IVA</th>
                    <th>Impuesto Interno $ o %</th>
                    <th>Impuesto Interno Valor</th>
                </tr>
                
                <?php foreach ($GetProductos as $Product) { ?>
                <tr>
                   <td><?php echo $Product['name'];?></td>
                   <td><?php echo $Product['stock'];?></td>
                   <td><?php echo number_format($Product['costo'],2);?></td>
                   <td><?php echo number_format($Product['precio_neto'],2);?></td>
                   <td><?php echo $Product['IVA']."%";?></td>
                   <?php
                        if($Product['imp_interno']!="0"){?>
                            <td><?php echo $Product['imp_interno'];?></td>
                            <td><?php echo $Product['imp_interno_valor'];?></td>
                        <?php }else{
                            echo "<td>-</td>";  
                            echo "<td>-</td>";  
                        }
                   ?>
                </tr>
                <?php } ?>

            </table>

        </div>

    </div>
    
</body>
</html>