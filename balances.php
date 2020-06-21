<?php

require_once "pdo.php";

$filtro='false';
$saldo=0;
$debe=0;
$haber=0;

$Vaciar=$conexion->query("TRUNCATE balances");

if(isset($_POST['print'])){
    echo "<script>window.open('balances-print.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    echo "<script>window.open('balances-excel.php', '_blank');</script>";
}

if(isset($_POST['filtrar'])){
    $date1=$_POST['date1'];
    $_SESSION['date1']=$date1;
    $date2=$_POST['date2'];
    $_SESSION['date2']=$date2;
    $filtro='true';
    $GetAccounts=$conexion->query("SELECT * from accounts ORDER BY code ASC");

    $ObtenerTitulos=$conexion->query("SELECT * FROM accounts WHERE type=0");

        foreach ($ObtenerTitulos as $Titulo){
            $Guardar=$conexion->prepare("INSERT INTO balances (code,type,level,dad,num) VALUES (:code,:type,:level,:dad,:num)");
            $Guardar->bindParam(':code',$Titulo['code']);
            $Guardar->bindParam(':type',$Titulo['type']);
            $Guardar->bindParam(':level',$Titulo['level']);
            $Guardar->bindParam(':dad',$Titulo['dad']);
            $Guardar->bindParam(':num',$Titulo['ID']);
            $Guardar->execute();
        }

       
        foreach ($GetAccounts as $Account) {            

            if ($Account['type']==1) {                      
                $inicial=0;
                $GetSaldoInicial=$conexion->query("SELECT * FROM assets WHERE type=0");
                foreach ($GetSaldoInicial as $Asset){                    
                    $GetAssetRows=$conexion->prepare("SELECT * from assets_row WHERE (asset_number=:id)");
                    $GetAssetRows->bindparam(':id',$Asset['ID']);
                    $GetAssetRows->execute();
                    foreach($GetAssetRows as $Row){
                        if($Row['account']==$Account['code']){
                            $inicial=$Row['import'];
                            if($Row['type']==1){
                                $inicial=$inicial*-1;
                            }
                        }
                    }
                }    
                    
                    $ObtenerDebitos=$conexion->prepare("SELECT * FROM assets WHERE ((date BETWEEN :fecha1 AND :fecha2) AND (type=5))");
                    $ObtenerDebitos->bindParam(':fecha1',$date1);
                    $ObtenerDebitos->bindParam(':fecha2',$date2);
                    $ObtenerDebitos->execute();
                    $debe=0;
                    $haber=0;
                    foreach ($ObtenerDebitos as $Asset){
                        
                        $ID=$Asset['ID'];
                        $AcumularDebitos=$conexion->prepare("SELECT * FROM assets_row WHERE asset_number=:ID");
                        $AcumularDebitos->bindParam(':ID',$ID);
                        $AcumularDebitos->execute();
                        foreach ($AcumularDebitos as $Debito){
                            if($Debito['account']==$Account['code']){
                                if($Debito['type']==0){
                                    $debe=$debe+$Debito['import'];
                                }else{
                                    $haber=$haber+$Debito['import'];
                                }
                            }
                        
                        }
                    }               
                        
                        $Guardar=$conexion->prepare("INSERT INTO balances (code,saldoi,debe,haber,type,level,dad,num) VALUES (:code,:saldoi,:debe,:haber,:type,:level,:dad,:num)");
                        $Guardar->bindParam(':code',$Account['code']);
                        $Guardar->bindParam(':saldoi',$inicial);
                        $Guardar->bindParam(':debe',$debe);
                        $Guardar->bindParam(':haber',$haber);
                        $Guardar->bindParam(':type',$Account['type']);
                        $Guardar->bindParam(':level',$Account['level']);
                        $Guardar->bindParam(':dad',$Account['dad']);
                        $Guardar->bindParam(':num',$Account['ID']);
                        $Guardar->execute();

                        $ObtenerDatosPadre=$conexion->prepare("SELECT * from balances WHERE code=:code");
                        $ObtenerDatosPadre->bindParam(':code',$Account['dad']);
                        $ObtenerDatosPadre->execute();

                        foreach ($ObtenerDatosPadre as $Padre){
                            $inicial_padre=floatval($Padre['saldoi'])+floatval($inicial);
                            $debe_padre=floatval($Padre['debe'])+floatval($debe);
                            $haber_padre=floatval($Padre['haber'])+floatval($haber);
                        }

                        $ActualizoDatos=$conexion->prepare("UPDATE balances SET saldoi=:saldoi,debe=:debe,haber=:haber WHERE code=:code");
                        $ActualizoDatos->bindParam(':code',$Account['dad']);
                        $ActualizoDatos->bindParam(':saldoi',$inicial_padre);
                        $ActualizoDatos->bindParam(':debe',$debe_padre);
                        $ActualizoDatos->bindParam(':haber',$haber_padre);
                        $ActualizoDatos->execute();
            }           
        } 

        $ObtenerTitulos=$conexion->query("SELECT * from balances WHERE type=0 ORDER BY level DESC");

        foreach ($ObtenerTitulos as $Account){

            $ObtenerDatosPadre=$conexion->prepare("SELECT * from balances WHERE code=:code");
            $padre=trim($Account['dad']);
            $ObtenerDatosPadre->bindParam(':code',$padre);
            $ObtenerDatosPadre->execute();

                        foreach ($ObtenerDatosPadre as $Padre){
                            $ini_p=$Padre['saldoi']+$Account['saldoi'];
                            $debe_p=$Padre['debe']+$Account['debe'];
                            $haber_p=$Padre['haber']+$Account['haber'];
                        }

            $ActualizoDatos=$conexion->prepare("UPDATE balances SET saldoi=:saldoi,debe=:debe,haber=:haber WHERE code=:code");
            $ActualizoDatos->bindParam(':code',$Account['dad']);
            $ActualizoDatos->bindParam(':saldoi',$ini_p);
            $ActualizoDatos->bindParam(':debe',$debe_p);
            $ActualizoDatos->bindParam(':haber',$haber_p);
            $ActualizoDatos->execute();

        }
}
$SumarBancoADisponibilidades=$conexion->query("SELECT * from balances WHERE num=23");

foreach ($SumarBancoADisponibilidades as $Saldo){
    $debitos=$Saldo['debe'];
    $creditos=$Saldo['haber'];
}
$ActualizarDisponibilidades=$conexion->query("SELECT * FROM balances WHERE num=20");

foreach ($ActualizarDisponibilidades as $Dispo){
    $debitos=$debitos+$Dispo['debe'];
    $creditos=$creditos+$Dispo['haber'];
}

$GraboDatos=$conexion->prepare("UPDATE balances SET debe=:debe,haber=:haber WHERE num=20");
$GraboDatos->bindParam(':debe',$debitos);
$GraboDatos->bindParam(':haber',$creditos);
$GraboDatos->execute();

$ObtengoCorriente=$conexion->query("SELECT * FROM balances WHERE num=20");

foreach ($ObtengoCorriente as $Corriente){
    $saldoi=$Corriente['saldoi'];
    $debe=$Corriente['debe'];
    $haber=$Corriente['haber'];
}

$GraboActivo=$conexion->prepare("UPDATE balances SET saldoi=:saldoi,debe=:debe,haber=:haber WHERE num=1");
$GraboActivo->bindParam(':saldoi',$saldoi);
$GraboActivo->bindParam(':debe',$debe);
$GraboActivo->bindParam(':haber',$haber);
$GraboActivo->execute();

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
    <title>Balance - SiGeCo v 1.0</title>    
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <div>
            <ul class="SecondMenu" style="transform:translate(350px,-100px);">                
                <a href="contabilidad.php"><li class="menu2-button" style="width:100px;">VOLVER</li></a>
            </ul>

</div>
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
            <form method="post" action="balances.php" style="transform:translate(350px,-5px);display:inline-flex;">
                    <input type="submit" class="menu2-button" value="IMPRIMIR" name="print">
                    <input  type="submit" class="menu2-button" style="width:180px;" value="EXPORTAR A EXCEL" name="excel">
                </form>
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
                        <td style="text-align:right;width:80px;"><?php if($Balance['saldoi']!=0) { echo number_format($Balance['saldoi'],2);}else{ echo "";}?></td>
                        <td style="text-align:right;width:80px;"><?php if($Balance['debe']!=0) { echo number_format($Balance['debe'],2);}else{ echo "";}?></td>
                        <td style="text-align:right;width:80px;"><?php if($Balance['haber']!=0) { echo number_format($Balance['haber'],2);}else{ echo "";}?></td>
                        <td style="text-align:right;width:80px;"><?php $acum=$Balance['debe']-$Balance['haber']; if($acum!=0) { echo number_format($acum,2);}else{ echo "";}?></td>
                        <?php if($Balance['saldoi']==0){ ?>
                            <td style="text-align:right;width:80px;"><?php if($acum!=0) { echo number_format($acum,2);}else{ echo "";}?></td>

                        <?php }else{ ?>
                            <td style="text-align:right;width:80px;"><?php $cierre=$Balance['saldoi']+$acum; if($cierre!=0) { echo number_format($cierre,2);}else{ echo "";}?></td>
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
                        <td style="text-align:right;width:80px;"><?php if($Balance['saldoi']!=0) { echo number_format($Balance['saldoi'],2);}else{ echo "";}?></td>
                        <td style="text-align:right;width:80px;"><?php if($Balance['debe']!=0) { echo number_format($Balance['debe'],2);}else{ echo "";}?></td>
                        <td style="text-align:right;width:80px;"><?php if($Balance['haber']!=0) { echo number_format($Balance['haber'],2);}else{ echo "";}?></td>
                        <td style="text-align:right;width:80px;"><?php $acum=$Balance['debe']-$Balance['haber']; if($acum!=0) { echo number_format($acum,2);}else{ echo "";}?></td>
                        <?php if($Balance['saldoi']==0){ ?>
                            <td style="text-align:right;width:80px;"><?php echo number_format($acum,2);?></td>
                        <?php }else{ ?>
                            <td style="text-align:right;width:80px;"><?php $cierre=$Balance['saldoi']+$acum; if($cierre!=0) { echo number_format($cierre,2);}else{ echo "";}?></td>
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