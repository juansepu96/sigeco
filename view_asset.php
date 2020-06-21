<?php

require_once "pdo.php";

$GetAssets=$conexion->prepare("SELECT * from assets WHERE ID=:id");
$GetAssets->bindParam(':id',$_SESSION['asset.id']);
$GetAssets->execute();

$GetAssetItems=$conexion->prepare("SELECT * FROM assets_row WHERE asset_number=:id");
$GetAssetItems->bindParam(':id',$_SESSION['asset.id']);
$GetAssetItems->execute();

foreach ($GetAssets as $Asset){
    $date=$Asset['date'];
    $number=$Asset['ID'];
    $type=$Asset['type'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Ver Asiento - SiGeCo v 1.0</title>    
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
     
        <div class="table" style="padding:25px;"> 
            <strong style="margin-left:350px;">Nro. de Asiento: </strong> <?php echo $number;?>
            <strong style="margin-left:50px;">Fecha: </strong> <?php echo $date;?>
            <?php $type=intval($type);?>
            <?php if(is_int($type)){if($type==0) { $AssetType="Apertura"; } else { if($type==5) { $AssetType="Normal";}else {$AssetType="Cierre";}}} ?>
            <strong style="margin-left:50px;">Tipo: </strong> <?php echo $AssetType;?>
        
            <table style="padding-top:20px;margin-left:10px;">
                <tr>
                    <th>Nro. Item</th>
                    <th>Nro. Cuenta</th>
                    <th>Cuenta</th>
                    <th>F. Op. </th>
                    <th>F. Ven. </th>
                    <th>Suc.</th>
                    <th>Sec. </th>
                    <th>Comprobante</th>
                    <th>Concepto</th>
                    <th>Tipo</th>
                    <th>Importe</th>
            </tr>      
                <?php foreach ($GetAssetItems as $Renglon) { ?>
                    <tr>

                        <td hidden><input type="text" name="item_delete" value="<?php echo $Renglon['ID'];?>"></td>
                        <td><?php echo $Renglon['row_number'];?></td>
                        <td><?php echo $Renglon['account'];?></td>
                        <?php $GetAccountName=$conexion->prepare("SELECT * FROM accounts WHERE code=:code");?>
                        <?php $GetAccountName->bindParam(':code',$Renglon['account']); $GetAccountName->execute();?>
                        <?php foreach ($GetAccountName as $Account) { $AccountName=$Account['name']; break;} ?>
                        <td><?php echo $AccountName;?></td>
                        <td><?php echo $Renglon['date_op'];?></td>
                        <td><?php echo $Renglon['date_ven'];?></td>
                        <td><?php echo $Renglon['suc'];?></td>
                        <td><?php echo $Renglon['secc'];?></td>
                        <td><?php echo $Renglon['comprobante'];?></td>
                        <td><?php echo $Renglon['concep'];?></td>
                        <?php $RowType=intval($Renglon['type']);?>
                        <?php if(is_int($RowType)){if($RowType==0) { $RowType="Debe"; } else { if($RowType==1) { $RowType="Haber";}else {$RowType="Error";}}} ?>
                        <td><?php echo $RowType?></td>
                        <td><?php echo $Renglon['import'];?></td>
                    </tr>
                <?php } ?>
            </table>

        </div>

    </div>

</body>
</html>