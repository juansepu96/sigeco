<?php

require_once "pdo.php";

$GetAssets=$conexion->query("SELECT * from assets WHERE status=1 ORDER BY type ASC");

if(isset($_POST['open'])){
    $_SESSION['asset.id']=$_POST['assetid'];
    header ("Location: edit_asset.php");
}




?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src=”https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js”></script>
    <script type="text/javascript" src="asset_validations.js"></script>
    <title>Asientos - SiGeCo v 1.0</title>    
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "asientos_menu.php"; ?>
    </div>

    <div class="content">
     
        <div class="table">

            <h2 style="text-align:center;padding:10px;">Listado de Asientos SIN REGISTRAR</h2>

            <table style="transform:translateX(480px);">
                <tr>
                    <th>Nro. Asiento</th>
                    <th>Fecha</th>
                    <th>Tipo de Asiento</th>
                    <th>Editar</th>
                </tr>
                
                <?php foreach ($GetAssets as $Asset) { ?>
                    <form action="change_asset.php" method="post">
                    <tr>
                        <td hidden><input type="text" name="assetid" value="<?php echo $Asset['ID'];?>"></td>
                        <td><?php echo $Asset['ID'];?></td>
                        <td><?php echo date_format(date_create_from_format('Y-m-d', $Asset['date']), 'd/m/Y');?></td>
                        <?php $Asset['type']=intval($Asset['type']);?>
                        <?php if(is_int($Asset['type'])){if($Asset['type']==0) { $AssetType="Apertura"; } else { if($Asset['type']==5) { $AssetType="Normal";}else {$AssetType="Cierre";}}} ?>
                        <td><?php echo $AssetType;?></td>
                        <td style="background:blue;color:white;"><input readonly style="background:blue;color:white;cursor:pointer;" type="submit" value="Editar" name="open"></td>
                        
                    </form>

                <?php } ?>

            </table>

        </div>

    </div>
</body>
</html>