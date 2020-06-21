<?php

require_once "pdo.php";

$filtro='false';

$GetAssets=$conexion->query("SELECT * from assets WHERE status=1 ORDER BY type ASC");

if(isset($_POST['open'])){
    $_SESSION['asset.id']=$_POST['assetid'];
    header ("Location: view_asset.php");
}

if(isset($_POST['registrar'])){
    $GetAssets=$conexion->query("SELECT * from assets WHERE status=1 ORDER BY type ASC");
    foreach ($GetAssets as $Asset){
        $UpDateAssets=$conexion->prepare("UPDATE assets SET status=2 WHERE ID=:id");
        $UpDateAssets->bindParam(':id',$Asset['ID']);
        $UpDateAssets->execute();
        $UpdateRows=$conexion->prepare("UPDATE assets_row SET status=2 WHERE asset_number=:id");
        $UpdateRows->bindParam(':id',$Asset['ID']);
        $UpdateRows->execute();
        header ("Location: asientos.php");
        
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
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src=”https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js”></script>
    <script type="text/javascript" src="asset2_validations.js"></script>
    <title>Registar Asientos - SiGeCo v 1.0</title>    
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
            <?php if ($filtro=='false') { ?>
                <div name="auth">
                    <h3 style="text-align:center;padding:10px;">LISTADO DE ASIENTOS SIN REGISTRAR</h3>

                    <table style="transform:translateX(480px);">
                      <tr>
                    <th>Nro. Asiento</th>
                    <th>Fecha</th>
                    <th>Tipo de Asiento</th>
                    <th>Abrir</th>
                      </tr>

                      <?php foreach ($GetAssets as $Asset) { ?>
                        <form action="reg_asset.php" method="post">
                        <tr>
                            <td hidden><input type="text" name="assetid" value="<?php echo $Asset['ID'];?>"></td>
                            <td><?php echo $Asset['ID'];?></td>
                            <td><?php echo date_format(date_create_from_format('Y-m-d', $Asset['date']), 'd/m/Y');?></td>
                            <?php $Asset['type']=intval($Asset['type']);?>
                            <?php if(is_int($Asset['type'])){if($Asset['type']==0) { $AssetType="Apertura"; } else { if($Asset['type']==5) { $AssetType="Normal";}else {$AssetType="Cierre";}}} ?>
                            <td><?php echo $AssetType;?></td>
                            <td style="background:blue;color:white;"><input style="background:blue;color:white;cursor:pointer;" type="submit" value="Abrir" name="open"></td>

                        </tr>
                        </form>
                    <?php } ?>

                    </table>

                    <div style="width:500px;background:skyblue;border-radius:10px;padding:10px;transform:translate(400px,15px);">
                        <form action="reg_asset.php" method="post">
                            <input type="password" id="clave" style="width:300px;" class="newseller-field" name="password" placeholder="Ingrese su clave de administrador: " onblur="ValidarClave();">
                            <input hidden disabled id="registrar" type="submit" value="Comenzar" name="registrar" class="menu2-button" style="color:white;">
                        </form>
                    </div>




                </div>
            <?php }else { ?>
        
                <div class="table">

                    
                </div>
            <?php } ?>
            </div>
    </div>
</body>
</html>