<?php

require_once "pdo.php";

$GetClientes=$conexion->query("SELECT * from clientes");


header("Pragma: public");
header("Expires: 0");
$filename = "SiGeCo v1.0 - Clientes Export-.xls";
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
    <title>Exportar Clientes - SiGeCo v1.0</title>
</head>
<body >

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
                    <th>Numero</th>
                    <th>Cuenta Contable</th>
                    <th>Apellido y Nombres</th>
                    <th>Domicilio</th>
                    <th>CUIT</th>
                    <th>Sit. frente a IVA</th>
                </tr>
                <?php foreach ($GetClientes as $Cliente) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $Cliente['ID'];?></td>
                        <td style="text-align:left;"><?php echo $Cliente['cuenta_contable'];?></td>
                        <td style="text-align:left;"><?php echo $Cliente['nombre'];?></td>
                        <td style="text-align:left;"><?php echo $Cliente['domicilio'];?></td>
                        <td style="text-align:center;"><?php echo $Cliente['cuit'];?></td>
                        <td style="text-align:left;"><?php echo $Cliente['sit_iva'];?></td>
                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</body>
</html>


    
</body>
</html>