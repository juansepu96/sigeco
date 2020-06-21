<?php

require_once "pdo.php";

$GetClientes=$conexion->query("SELECT * FROM clientes ORDER BY ID ASC");


if(isset($_POST['abrir'])){
    $_SESSION['cliente.edit']=$_POST['cliente_id'];
    header ("Location: edit_cliente.php");
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Modificar Clientes - SiGeCo v1.0</title>
</head>
<body>
    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "iva_menu.php"; ?>
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
                    <th>EDITAR</th>
                </tr>
                <?php foreach ($GetClientes as $Cliente) { ?>
                    <form action="change_cliente.php" method="post">
                    <tr>
                    <td hidden><input type="text" name="cliente_id" value="<?php echo $Cliente['ID'];?>"></td>

                        <td style="text-align:center;"><?php echo $Cliente['ID'];?></td>
                        <td style="text-align:left;"><?php echo $Cliente['cuenta_contable'];?></td>
                        <td style="text-align:left;"><?php echo $Cliente['nombre'];?></td>
                        <td style="text-align:left;"><?php echo $Cliente['domicilio'];?></td>
                        <td style="text-align:center;"><?php echo $Cliente['cuit'];?></td>
                        <td style="text-align:left;"><?php echo $Cliente['sit_iva'];?></td>
                        <td style="background:blue;color:white;"><input readonly style="background:blue;color:white;cursor:pointer;" type="submit" value="Editar" name="abrir"></td>

                    </tr>
                    </form>

                <?php } ?>

            </table>
        </div>

    </div>
    
</body>
</html>