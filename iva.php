<?php

require_once "pdo.php";

$GetIVA=$conexion->query("SELECT * FROM iva ORDER BY nombre ASC");

if(isset($_POST['print'])){
    echo "<script>window.open('iva-print.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    echo "<script>window.open('iva-excel.php', '_blank');</script>";
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
        <form method="post" action="iva.php" style="padding-left:500px;">
                <input type="submit" name="print" value="IMPRIMIR" class="menu2-button">
                <input type="submit" name="excel" value="EXCEL" class="menu2-button">

            </form>

        <table style="width:100%">
                <tr>
                    <th>Denominación del Impuesto</th>
                    <th>Valor de IVA</th>
                </tr>
                
                <?php foreach ($GetIVA as $IVA) { ?>
                <tr>
                   <td><?php echo $IVA['nombre'];?></td>
                   <td><?php echo $IVA['tasa']."%";?></td>
                </tr>
                <?php } ?>

            </table>

        </div>

    </div>
    
</body>
</html>