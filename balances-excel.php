<?php

require_once "pdo.php";

$ObtenerBalance=$conexion->query('SELECT * FROM balances ORDER BY code ASC');

$ini=0;
$deb=0;
$hab=0;

foreach ($ObtenerBalance as $Balance){
    $ini=$ini+$Balance['saldoi'];
    $deb=$deb+$Balance['debe'];
    $hab=$hab+$Balance['haber'];
}

$acum=$deb-$hab;

$cierre=$ini+$acum;

$ObtenerBalance=$conexion->query('SELECT * FROM balances ORDER BY code ASC');


header("Pragma: public");
header("Expires: 0");
$filename = "SiGeCo v1.0 - Balances  Export-.xls";
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
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src=”https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js”></script>
    <script type="text/javascript" src="asset2_validations.js"></script>
    <title>Imprimir Balance - SiGeCo v 1.0</title>    
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
     <h2 style="text-align:center;padding:10px;">Emisión del Balance</h2>
        <?php if ($filtro=='false') { ?>
            <div name="date">
                <form action="balances.php" method="post" style="padding:20px;content-align:center;transform:translatex(300px);">
                    Desde: <input required type="date" name="date1" class="newseller-field"> 
                    Hasta: <input required type="date" name="date2" class="newseller-field">
                    <input type="submit" value="Filtrar" class="menu2-button" style="color:white;" name="filtrar">
                </form>
            </div>
        <?php }else { ?>    
                <h3 style="text-align:center;">Desde: <?php echo date_format(date_create_from_format('Y-m-d', $date1), 'd/m/Y'); ?> Hasta: <?php echo date_format(date_create_from_format('Y-m-d', $date2), 'd/m/Y');?></h3>
                <strong>================================================================================================================================================</strong>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cuenta &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Código &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nombre &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saldo Inicial &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Débitos  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Créditos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspSaldo Acum.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saldo Cierre<strong>
                <strong>================================================================================================================================================</strong>
                <table style="width:100%">
                <?php foreach ($ObtenerBalance as $Balance){?>
                 <?php if (!( ($Balance['saldoi']==0) && ($Balance['debe']==0) && ($Balance['haber']==0))){?>

                    <?php if($Balance['type']==0){?>
                        <tr style="background:#F5D4CD;">
                        <td style="text-align:center;width:50px;"><?php echo $Balance['num'];?></td>
                        <td style="text-align:left;width:70px;"><?php echo $Balance['code'];?></td>
                        <?php 
                            $ObtenerNombre=$conexion->prepare("SELECT * FROM accounts WHERE code=:code");
                            $ObtenerNombre->bindParam(':code',$Balance['code']);
                            $ObtenerNombre->execute();
                            foreach ($ObtenerNombre as $Account){
                                $nombre=$Account['name'];
                                break;
                            }

                        ?>
                        <td style="text-align:left;width:200px;"><?php echo $nombre;?></td>
                        <td style="text-align:center;width:80px;"><?php if($Balance['saldoi']!=0) { echo $Balance['saldoi'];}else{ echo "";}?></td>
                        <td style="text-align:center;width:80px;"><?php if($Balance['debe']!=0) { echo $Balance['debe'];}else{ echo "";}?></td>
                        <td style="text-align:center;width:80px;"><?php if($Balance['haber']!=0) { echo $Balance['haber'];}else{ echo "";}?></td>
                        <td style="text-align:center;width:80px;"><?php $acum=$Balance['debe']-$Balance['haber']; if($acum!=0) { echo $acum;}else{ echo "";}?></td>
                        <?php if($Balance['saldoi']==0){ ?>
                            <td style="text-align:center;width:80px;"><?php if($acum!=0) { echo $acum;}else{ echo "";}?></td>

                        <?php }else{ ?>
                            <td style="text-align:center;width:80px;"><?php $cierre=$Balance['saldoi']+$acum; if($cierre!=0) { echo $cierre;}else{ echo "";}?></td>
                        <?php } ?>
                    </tr>

                   <?php }else{ ?>
                    <tr>
                        <td style="text-align:center;width:50px;"><?php echo $Balance['num'];?></td>
                        <td style="text-align:left;width:70px;"><?php echo $Balance['code'];?></td>
                        <?php 
                            $ObtenerNombre=$conexion->prepare("SELECT * FROM accounts WHERE code=:code");
                            $ObtenerNombre->bindParam(':code',$Balance['code']);
                            $ObtenerNombre->execute();
                            foreach ($ObtenerNombre as $Account){
                                $nombre=$Account['name'];
                            }

                        ?>
                        <td style="text-align:left;width:200px;"><?php echo $nombre;?></td>
                        <td style="text-align:center;width:80px;"><?php if($Balance['saldoi']!=0) { echo $Balance['saldoi'];}else{ echo "";}?></td>
                        <td style="text-align:center;width:80px;"><?php if($Balance['debe']!=0) { echo $Balance['debe'];}else{ echo "";}?></td>
                        <td style="text-align:center;width:80px;"><?php if($Balance['haber']!=0) { echo $Balance['haber'];}else{ echo "";}?></td>
                        <td style="text-align:center;width:80px;"><?php $acum=$Balance['debe']-$Balance['haber']; if($acum!=0) { echo $acum;}else{ echo "";}?></td>
                        <?php if($Balance['saldoi']==0){ ?>
                            <td style="text-align:center;width:80px;"><?php echo $acum;?></td>
                        <?php }else{ ?>
                            <td style="text-align:center;width:80px;"><?php $cierre=$Balance['saldoi']+$acum; if($cierre!=0) { echo $cierre;}else{ echo "";}?></td>
                        <?php } ?>
                        
                    </tr>

                    <?php } ?>
                    
                <?php } ?>

                <?php } ?>
                
                </table>
                <table>
                <tr>
                    <td style="text-align:center;width:1000px;"><?php echo "";?></td>
                    <td style="text-align:center;width:100px;"><?php $ini;?></td>
                    <td style="text-align:center;width:100px;"><?php $acum;?></td>
                    <td style="text-align:center;width:100px;"><?php $cierre;?></td>
                </tr>
                <br>
                <tr>
                    <td style="text-align:center;width:1000px;"><?php echo "TOTAL RESULTADO NEGATIVO";?></td>
                    <td style="text-align:center;width:100px;"><?php $ini;?></td>
                    <td style="text-align:center;width:100px;"><?php echo "";?></td>
                    <td style="text-align:center;width:100px;"><?php echo "";?></td>
                    <?php $ObtenerNeg=$conexion->query("SELECT * FROM balances WHERE num=19");
                    $ini=0;
                    $debe=0;
                    $haber=0;
                            foreach ($ObtenerNeg as $Saldo){
                                $debe=$Saldo['debe'];
                                $haber=$Saldo['haber'];
                                $ini=$Saldo['saldoi'];
                            }
                    ?>
                    <td style="text-align:center;width:100px;"><?php echo $debe-$haber;?></td>
                    <td style="text-align:center;width:100px;"><?php if($ini==0){
                        echo $debe-$haber;
                        }else{
                            echo $ini+$debe-$haber;
                            }?></td>
                </tr>
                <br>
                
                <tr><td style="text-align:center;width:1000px;"><?php echo "RESULTADOS (PERDIDA)";?></td>
                    <td style="text-align:center;width:100px;"><?php $ini;?></td>
                    <td style="text-align:center;width:100px;"><?php echo "";?></td>
                    <td style="text-align:center;width:100px;"><?php echo "";?></td>
                    <?php $ObtenerNeg=$conexion->query("SELECT * FROM balances WHERE num=19");
                    $ini=0;
                    $debe=0;
                    $haber=0;
                            foreach ($ObtenerNeg as $Saldo){
                                $debe=$Saldo['debe'];
                                $haber=$Saldo['haber'];
                                $ini=$Saldo['saldoi'];
                            }
                    ?>
                    <td style="text-align:center;width:100px;"><?php echo $debe-$haber;?></td>
                    <td style="text-align:center;width:100px;"><?php if($ini==0){
                        echo $debe-$haber;
                        }else{
                            echo $ini+$debe-$haber;
                            }?></td>
                </tr>
            </table>
        <?php } ?>     

    </div>
</body>
</html>