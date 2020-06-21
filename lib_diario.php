<?php

require_once "pdo.php";


$filtro='false';
$saldo=0;
$GetDataA=$conexion->query("SELECT * FROM lib_diario");
foreach ($GetDataA as $Datos){
    $renglones=$Datos['renglones'];
    $asientos=$Datos['asientos'];
    $debe=$Datos['debe'];
    $haber=$Datos['haber'];
    $fecha=$Datos['date'];
    $ID=$Datos['ID'];
}

if(isset($_POST['filtrar'])){
    $date1=$fecha;
    $date2=$_POST['date2'];
    $_SESSION['date1']=$date1;
    $_SESSION['date2']=$date2;
    $filtro='true';
    $GetAssets=$conexion->prepare("SELECT * from assets WHERE ((status=2) AND (date>=:fecha1 AND date<=:fecha2)) ORDER BY date ASC");
    $GetAssets->bindParam(':fecha1',$date1);
    $GetAssets->bindParam(':fecha2',$date2);
    $GetAssets->execute();
    $asiento=$GetAssets->RowCount();
    $asientos=$asientos+$GetAssets->Rowcount();
    $renglones2=$renglones;
    foreach($GetAssets as $Asset){
        $GetAssetRows=$conexion->prepare("SELECT * from assets_row WHERE asset_number=:id"); 
        $GetAssetRows->bindParam(':id',$Asset['ID']);
        $GetAssetRows->execute();
        $renglones2=$renglones2+$GetAssetRows->Rowcount();
    }
    $GetAssets=$conexion->prepare("SELECT * from assets WHERE ((status=2) AND (date>=:fecha1 AND date<=:fecha2)) ORDER BY date ASC");
    $GetAssets->bindParam(':fecha1',$date1);
    $GetAssets->bindParam(':fecha2',$date2);
    $GetAssets->execute();
    $InsertarInformacion=$conexion->prepare("UPDATE lib_diario SET ID=:id,date=:date,renglones=:renglones,asientos=:asientos,debe=:debe,haber=:haber");
    $ID=$ID+1;
    $InsertarInformacion->bindParam(":id",$ID);
    $InsertarInformacion->bindParam(":date",$date2);
    $InsertarInformacion->bindParam(":renglones",$renglones2);
    $InsertarInformacion->bindParam(":asientos",$asientos);
    $InsertarInformacion->bindParam(":debe",$debe);
    $InsertarInformacion->bindParam(":haber",$haber);
    $InsertarInformacion->execute();
    $ObtenerNro=$conexion->query("SELECT * from lib_diario ORDER BY ID DESC");
    foreach ($ObtenerNro as $Nro){
        $folio=$Nro['ID'];
        break;
    }
}

if(isset($_POST['print'])){
    echo "<script>window.open('lib_diario-print.php', '_blank');</script>";
    $date1=$_SESSION['date1'];
   $date2=$_SESSION['date2'];
    $filtro='true';
    $GetAssets=$conexion->prepare("SELECT * from assets WHERE ((status=2) AND (date>=:fecha1 AND date<=:fecha2)) ORDER BY date ASC");
    $GetAssets->bindParam(':fecha1',$date1);
    $GetAssets->bindParam(':fecha2',$date2);
    $GetAssets->execute();
    $Registros=$GetAssets->Rowcount();
    $InsertarInformacion=$conexion->prepare("UPDATE lib_diario SET date=:date");
    $InsertarInformacion->bindParam(":date",$date);
    $InsertarInformacion->execute();
    $ObtenerNro=$conexion->query("SELECT * from lib_diario ORDER BY ID DESC");
    foreach ($ObtenerNro as $Nro){
        $folio=$Nro['ID'];
        break;
    }
}

if(isset($_POST['excel'])){
    echo "<script>window.open('lib_diario-excel.php', '_blank');</script>";
   $date1=$_SESSION['date1'];
   $date2=$_SESSION['date2'];
    $filtro='true';
    $GetAssets=$conexion->prepare("SELECT * from assets WHERE ((status=2) AND (date>=:fecha1 AND date<=:fecha2)) ORDER BY date ASC");
    $GetAssets->bindParam(':fecha1',$date1);
    $GetAssets->bindParam(':fecha2',$date2);
    $GetAssets->execute();
    $Registros=$GetAssets->Rowcount();
    $GetAssets=$conexion->prepare("SELECT * from assets WHERE ((status=2) AND (date>=:fecha1 AND date<=:fecha2)) ORDER BY date ASC");
    $GetAssets->bindParam(':fecha1',$date1);
    $GetAssets->bindParam(':fecha2',$date2);
    $GetAssets->execute();
    $InsertarInformacion=$conexion->prepare("UPDATE lib_diario SET date=:date");
    $InsertarInformacion->bindParam(":date",$date);
    $InsertarInformacion->execute();
    $ObtenerNro=$conexion->query("SELECT * from lib_diario ORDER BY ID DESC");
    foreach ($ObtenerNro as $Nro){
        $folio=$Nro['ID'];
        break;
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
    <title>Libro Diario - SiGeCo v 1.0</title>    
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <div>
            <ul class="SecondMenu" style="transform:translate(350px,-100px);">   
                    <?php if ($filtro=='true')  { ?>      
                    <a href="lib_diario.php"><li class="menu2-button" style="width:100px;">VOLVER</li></a>
                    <?php }else{ ?>
                        <a href="contabilidad.php"><li class="menu2-button" style="width:100px;">VOLVER</li></a>

                    <?php } ?>
            </ul>

</div>
    </div>

    <div class="content">
        <div class="table">
        <h2 style="text-align:center;padding:10px;">LIBRO DIARIO</h2>

        <?php if ($filtro=='false') {?>
            <div name="date">
                <form action="lib_diario.php" method="post" style="padding:20px;content-align:center;transform:translatex(300px);">
                   Desde: <input disabled type="date" name="date1" class="newseller-field" value="<?php echo $fecha;?>">
                  Hasta: <input type="date" name="date2" class="newseller-field" value="">
                   <input type="submit" value="GENERAR" class="menu2-button" style="color:white;" name="filtrar">
                </form>
            </div>
        
        <?php }else{?>
            <form method="post" action="lib_diario.php" style="padding-left:500px;">
                <input type="submit" name="print" value="IMPRIMIR" class="menu2-button">
                <input type="submit" name="excel" value="EXCEL" class="menu2-button">

            </form>
     
            <strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <p style="text-align: center;" > FECHA DE EMISIÓN: <?php echo $date2;?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SUCURSAL 00 - CASA CENTRAL -  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; FOLIO: <?php echo $folio;?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; REGISTROS: <input readonly id="registros" type="text" value="<?php echo $renglones2;?>"></p></strong>
            <strong>================================================================================================================================================</strong>
            <strong>&nbsp;&nbsp;&nbsp;&nbsp;N°&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  N. Cta.  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuenta &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;F. Op &nbsp;&nbsp;&nbsp;&nbsp; F. Vto. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Comprob. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Suc. &nbsp;Sec. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Concepto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Debe  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Haber<strong>
            <strong>================================================================================================================================================</strong>
            Cant. registros: <?php echo $renglones;?>============================================ TRANSPORTE ==================================================== &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<?php echo number_format($debe,2); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo number_format($haber,2);?>
            
            <?php foreach ($GetAssets as $Asset) { ?>   
            <?php $asiento=$asiento+1;?>
                <table style="border-radius:0px;border:1px solid #FFFFFF;width:1300px;">
                <td style="padding:15px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha: <?php echo date_format(date_create_from_format('Y-m-d', $Asset['date']), 'd/m/Y'); ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nro. Cont. <?php echo $Asset['ID'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; N. Asto.: <?php echo $asiento;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cargado: OK  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Estado: <?php if($Asset['status']==1) { echo "No registrado";}else{  echo "REGISTRADO";} ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tipo: <?php if($Asset['type']=="0") { echo "Apertura";}else{ if($Asset['type']=="5"){ echo "Normal";}else{ if($Asset['type']=="9"){ echo "Cierre";}}} ?> 
                <?php $GetAssetRows=$conexion->prepare("SELECT * from assets_row WHERE asset_number=:id"); 
                      $GetAssetRows->bindParam(':id',$Asset['ID']);
                      $GetAssetRows->execute();
                ?>
                <br><br>                
                <?php foreach($GetAssetRows as $Row){ ?>
                <?php $renglones++;?>
                    <table style="content-align:center;text-align:center;">
                        <tr>
                            <td style="border:0px;width:50px;"><?php echo $renglones;?></td>
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
                                <td style="border:0px;width:100px;"><?php echo number_format($Row['import'],2);?></td>
                                <?php
                                    $ObtenerDebe=$conexion->query("SELECT * from lib_diario");
                                    foreach ($ObtenerDebe as $Debe){
                                        $saldo_debe=$Debe['debe'];
                                        break;
                                    }
                                    $saldo_debe=$saldo_debe+floatval($Row['import']);
                                    $ActualizarTabla=$conexion->prepare("UPDATE lib_diario SET debe=:debe");
                                    $ActualizarTabla->bindParam(':debe',$saldo_debe);
                                    $ActualizarTabla->execute();               
                
                                ?>
                                <td style="border:0px;width:100px;"></td>
                            <?php }else{ ?>
                                <td style="border:0px;width:100px;"></td>
                                <td style="border:0px;width:100px;"><?php echo number_format($Row['import'],2);?></td>
                                <?php
                                    $ObtenerHaber=$conexion->query("SELECT * from lib_diario");
                                    foreach ($ObtenerHaber as $Haber){
                                        $saldo_haber=$Haber['haber'];
                                        break;
                                    }
                                    $saldo_haber=$saldo_haber+floatval($Row['import']);
                                    $ActualizarTabla=$conexion->prepare("UPDATE lib_diario SET haber=:haber");
                                    $ActualizarTabla->bindParam(':haber',$saldo_haber);
                                    $ActualizarTabla->execute();                               
                                ?>
                            <?php } ?>
                        </tr>       
                    </table>                 
                <?php } ?>
                </td>
                </table>
                <?php } ?>
                <?php $ObtenrSaldos=$conexion->query("SELECT * from lib_diario");
         foreach ($ObtenrSaldos as $saldos){
             $haber=$saldos['haber'];
             $debe=$saldos['debe'];
             $reg=$saldos['renglones'];
         }


        ?>
        <br><br>

         Cant. registros: <?php echo $reg;?>============================= TRANSPORTE ==================================================== &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<?php echo number_format($debe,2); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo number_format($haber,2);?>

        </div>
        

         <?php } ?>
         

    </div>

    <input hidden id="reg" type="text" value="<?php echo $renglones;?>">

    <script>
            $(document).ready(function(){
            
                valoringresado = $('#reg').val();

               $('#registros').val(valoringresado);


                
            });

    </script>
</body>
</html>