<?php

require_once "pdo.php";

$GetIVA=$conexion->query("SELECT * FROM iva ORDER BY nombre ASC");


if(isset($_POST['abrir'])){
    $_SESSION['iva.edit']=$_POST['iva_id'];
    header ("Location: edit_iva.php");
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Tasas de IVA - SiGeCo v1.0</title>
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
                    <th>Denominación del Impuesto</th>
                    <th>Valor de IVA</th>
                    <th>EDITAR</th>
                </tr>
                
                <?php foreach ($GetIVA as $IVA) { ?>
                    <form action="change_IVA.php" method="post">

                <tr>
                <td hidden><input type="text" name="iva_id" value="<?php echo $IVA['ID'];?>"></td>

                   <td><?php echo $IVA['nombre'];?></td>
                   <td><?php echo $IVA['tasa']."%";?></td>
                   <td style="background:blue;color:white;"><input readonly style="background:blue;color:white;cursor:pointer;" type="submit" value="Editar" name="abrir"></td>

                </tr>
                </form>
                <?php } ?>

            </table>

        </div>

    </div>
    
</body>
</html>