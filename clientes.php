<?php

require_once "pdo.php";

$GetClientes=$conexion->query("SELECT * from clientes");

if(isset($_POST['print'])){
    echo "<script>window.open('clientes_print.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    echo "<script>window.open('clientes_excel.php', '_blank');</script>";
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Lista de Clientes - SiGeCo v1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "clientes_menu.php"; ?>
    </div>
    <div class="content">

        <div class="table">     
        <form method="post" action="clientes.php" style="padding-left:500px;">
                <input type="submit" name="print" value="IMPRIMIR" class="menu2-button">
                <input type="submit" name="excel" value="EXCEL" class="menu2-button">

            </form>   

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