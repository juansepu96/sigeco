<?php

require_once "pdo.php";


$filtrado='false';

    $num1=$_SESSION['num1'];
    $num2=$_SESSION['num2'];
    $GetAssets=$conexion->prepare("SELECT * from assets WHERE ((status<>0) AND (ID BETWEEN :num1 AND :num2)) ORDER BY ID ASC");
    $GetAssets->bindParam(':num1',$num1);
    $GetAssets->bindParam(':num2',$num2);
    $GetAssets->execute();
    $filtrado='true';


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
    <title>Imprimir Asientos - SiGeCo v 1.0</title>    
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
            <?php if($filtrado=='false') {?>
                <form method="post" action="asientos.php" style="padding:10px;transform:translatex(300px);">
                    Desde: <input type="number" name="num1" class="newseller-field">
                    Hasta: <input type="number" name="num2" class="newseller-field">
                    <input type="submit" value="Filtrar" name="filtrar" class="menu2-button">
                </form>
            <?}else{ ?>

            <h2 style="text-align:center;padding:10px;">Listado de Asientos <?php echo $num1;?> hasta <?php echo $num2;?></h2>
            <strong>================================================================================================================================================</strong>
            <strong>&nbsp;&nbsp;&nbsp;&nbsp;N°&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  N. Cta.  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuenta &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;F. Op &nbsp;&nbsp;&nbsp;&nbsp; F. Vto. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Comprob. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Suc. &nbsp;Sec. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Concepto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Debe  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Haber<strong>
            <strong>================================================================================================================================================</strong>
            <?php foreach ($GetAssets as $Asset) { ?>            
                <table style="border-radius:0px;border:1px solid #FFFFFF;width:1300px;">
                <td style="padding:15px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha: <?php echo date_format(date_create_from_format('Y-m-d', $Asset['date']), 'd/m/Y'); ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; N. Asto.: <?php echo $Asset['ID'];?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cargado: OK  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Estado: <?php if($Asset['status']==1) { echo "No registrado";}else{  echo "REGISTRADO";} ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tipo: <?php if($Asset['type']=="0") { echo "Apertura";}else{ if($Asset['type']=="5"){ echo "Normal";}else{ if($Asset['type']=="9"){ echo "Cierre";}}} ?> 
                <?php $GetAssetRows=$conexion->prepare("SELECT * from assets_row WHERE asset_number=:id"); 
                      $GetAssetRows->bindParam(':id',$Asset['ID']);
                      $GetAssetRows->execute();
                ?>
                <br><br>                
                <?php foreach($GetAssetRows as $Row){ ?>
                    <table style="content-align:center;text-align:center;">
                        <tr>
                            <td style="border:0px;width:50px;"><?php echo $Row['row_number'];?></td>
                            <td style="border:0px;width:100px;"><?php echo $Row['account'];?></td>                        
                        <?php $GetAccountName=$conexion->prepare("SELECT * from accounts WHERE code=:code");
                            $GetAccountName->bindParam(':code',$Row['account']);
                            $GetAccountName->execute();
                            foreach ($GetAccountName as $Account){
                                $Name=$Account['name'];
                                break;
                            }
                        ?>
                            <td style="border:0px;width:150px;"><?php echo substr($Name,0,15);?></td>
                            <td style="border:0px;width:70px;"><?php echo date_format(date_create_from_format('Y-m-d', $Row['date_op']),'d/m/Y');?></td>
                            <?php if($Row['date_ven']!="0000-00-00") { ?>
                            <td style="border:0px;width:70px;"><?php echo date_format(date_create_from_format('Y-m-d', $Row['date_ven']),'d/m/Y');?></td>
                            <?php }else{ ?>
                            <td style="border:0px;width:70px;"></td>
                            <?php } ?>
                            <?php if($Row['comprobante']!="") { ?>
                            <td style="border:0px;width:120px;"><?php echo substr($Row['comprobante'],0,15);?></td>
                            <?php }else{ ?>
                            <td style="border:0px;width:120px;"></td>
                            <?php } ?>
                            <?php if($Row['suc']!="") { ?>
                            <td style="border:0px;width:30px;"><?php echo $Row['suc'];?></td>
                            <?php }else{ ?>
                            <td style="border:0px;width:30px;"></td>
                            <?php } ?>
                            <?php if($Row['secc']!="") { ?>
                            <td style="border:0px;width:30px;"><?php echo $Row['secc'];?></td>
                            <?php }else{ ?>
                            <td style="border:0px;width:30px;"></td>
                            <?php } ?>
                            <td style="border:0px;width:430px;"><?php echo substr($Row['concep'],0,100);?></td>
                            <?php if($Row['type']==0){?>
                                <td style="border:0px;width:100px;"><?php echo $Row['import'];?></td>
                                <td style="border:0px;width:100px;"></td>
                            <?php }else{ ?>
                                <td style="border:0px;width:100px;"></td>
                                <td style="border:0px;width:100px;"><?php echo $Row['import'];?></td>
                            <?php } ?>
                        </tr>       
                    </table>                 
                <?php } ?>
                </td>
                </table>
                <?php } ?>
                <?php } ?>
        </div>

    </div>
</body>
</html>