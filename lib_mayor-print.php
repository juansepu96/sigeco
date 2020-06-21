<?php

require_once "pdo.php";

$saldo=0;
$debe=0;
$haber=0;


    $date1=$_SESSION['date1'];
    $date2=$_SESSION['date2'];
    $filtro='true';



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
    <title> Imprimir Libro Mayor - SiGeCo v 1.0</title>    
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
     <h2 style="text-align:center;padding:10px;">Mayores de Cuentas</h2>
        <?php if ($filtro=='false') { ?>
            <div name="date">
                <form action="lib_mayor.php" method="post" style="padding:20px;content-align:center;transform:translatex(300px);">
                    Desde: <input required type="date" name="date1" class="newseller-field"> 
                    Hasta: <input required type="date" name="date2" class="newseller-field">
                    <input type="submit" value="Filtrar" class="menu2-button" style="color:white;" name="filtrar">
                </form>
            </div>
        <?php }else { ?>    
     
                <h3 style="text-align:center;">Desde: <?php echo date_format(date_create_from_format('Y-m-d', $date1), 'd/m/Y'); ?> Hasta: <?php echo date_format(date_create_from_format('Y-m-d', $date2), 'd/m/Y');?></h3>
                <strong>================================================================================================================================================</strong>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;F. Op &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;F. Vto. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Comprob. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Suc. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sec. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Concepto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Débitos  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Créditos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saldo<strong>
                <strong>================================================================================================================================================</strong>
                <?php $GetAccounts=$conexion->query("SELECT * from accounts WHERE type=1 ORDER BY code ASC"); ?>
                <?php foreach ($GetAccounts as $Account) { ?>            
                    <table style="border-radius:0px;border:1px solid #FFFFFF;width:1300px;">                    
                    <?php   $GetAssets=$conexion->query("SELECT * from assets WHERE status=2");        
                            foreach ($GetAssets as $Asset){
                                $GetAssetRows=$conexion->prepare("SELECT * from assets_row WHERE ((account=:account) AND (date_op>=:date1 AND date_op<=:date2) AND (status=2)) ORDER BY date_op ASC");
                                $GetAssetRows->bindParam(':account',$Account['code']);
                                $GetAssetRows->bindParam(':date1',$date1);
                                $GetAssetRows->bindParam(':date2',$date2);
                                $GetAssetRows->execute();
                                $filas=$GetAssetRows->rowcount();
                            }                
                            
                            $ObtenerSaldoAnterior=$conexion->prepare("SELECT * FROM assets_row WHERE ((account=:account) AND (date_op<:fecha1) AND (status=2))");
                            $ObtenerSaldoAnterior->bindParam(':account',$Account['code']);
                            $ObtenerSaldoAnterior->bindParam(':fecha1',$date1);
                            $ObtenerSaldoAnterior->execute();
                            $filas2=$ObtenerSaldoAnterior->rowcount();
                            $debe=0;$haber=0;$saldo=0;
                            foreach ($ObtenerSaldoAnterior as $SaldoA){
                                if($SaldoA['type']==0){
                                    $debe=$debe+$SaldoA['import'];
                                }else{
                                    $haber=$haber+$SaldoA['import'];
                                }
                            }
                            if($filas>0) { ?>
                                    <td style="padding:15px;">
                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Account['code']; ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cuenta: <?php echo $Account['name'];?> 
                                     <br><strong>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<strong>
                                  <br>                                  
                            <?php } ?>
                          <?php if (($filas>0) AND ($filas2>0)) {?>
                            <table style="content-align:center;text-align:center;">
                              <tr>
                                  <td style="border:0px;width:100px;"><?php echo date_format(date_create_from_format('Y-m-d', $date1),'d/m/Y');?></td>
                                  <td style="border:0px;width:70px;"></td>
                                  <td style="border:0px;width:150px;"></td>
                                  <td style="border:0px;width:50px;"><?php echo "0";?></td>
                                  <td style="border:0px;width:50px;"><?php echo "0";?></td>
                                  <td style="border:0px;width:450px;"><?php echo "Saldo Anterior";?></td>
                                  <td style="border:0px;width:120px;"><?php echo $debe;?></td>
                                  <td style="border:0px;width:120px;"><?php echo $haber;?></td>                                          
                                  <td style="padding-left:15px;border:0px;width:120px;"><?php $saldo=$debe-$haber; echo $saldo;?></td>                
                              </tr>       
                          </table>  
                          <?php } ?>  
                          <?php  
                          $haber1=0;
                          $debe1=0;
                        foreach ($GetAssetRows as $Row){
                            $debe=0;
                            $haber=0;
                            if($Row['account']==$Account['code']){ ?>
                                <table style="content-align:center;text-align:center;">
                                    <tr>
                                        <td style="border:0px;width:100px;"><?php echo date_format(date_create_from_format('Y-m-d', $Row['date_op']),'d/m/Y');?></td>
                                        <?php if($Row['date_ven']!="0000-00-00") { ?>
                                        <td style="border:0px;width:100px;"><?php echo date_format(date_create_from_format('Y-m-d', $Row['date_ven']),'d/m/Y');?></td>
                                        <?php }else{ ?>
                                        <td style="border:0px;width:70px;"></td>
                                        <?php } ?>
                                        <?php if($Row['comprobante']!="") { ?>
                                        <td style="border:0px;width:150px;"><?php echo substr($Row['comprobante'],0,15);?></td>
                                        <?php }else{ ?>
                                        <td style="border:0px;width:150px;"></td>
                                        <?php } ?>
                                        <?php if($Row['suc']!="") { ?>
                                        <td style="border:0px;width:50px;"><?php echo $Row['suc'];?></td>
                                        <?php }else{ ?>
                                        <td style="border:0px;width:50px;"></td>
                                        <?php } ?>
                                        <?php if($Row['secc']!="") { ?>
                                        <td style="border:0px;width:50px;"><?php echo $Row['secc'];?></td>
                                        <?php }else{ ?>
                                        <td style="border:0px;width:50px;"></td>
                                        <?php } ?>
                                        <td style="border:0px;width:450px;"><?php echo substr($Row['concep'],0,120);?></td>
                                        <?php if($Row['type']==0){?>
                                            <td style="border:0px;width:120px;"><?php $debe1=$debe1+$Row['import'];$debe=$Row['import']; echo $Row['import'];?></td>
                                            <td style="border:0px;width:120px;"></td>
                                        <?php }else{ ?>
                                            <td style="border:0px;width:120px;"></td>
                                            <td style="border:0px;width:120px;"><?php $haber1=$haber1+$Row['import'];$haber=$Row['import']; echo $Row['import'];?></td>
                                        <?php } ?>    
                                        <td style="padding-left:15px;border:0px;width:120px;"><?php $saldo=$saldo+$debe-$haber; echo $saldo;?></td>                
                                    </tr>   
                                </table>                 
                            <?php } ?>
                            
                        <?php } 
                              $saldo1=$debe1-$haber1;
                         if(($debe1 > 0) && ($haber1 > 0) && ($saldo1>0)){ ?>
                        <table>
                                          <tr> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----------------------------------------------------------------------------------
                                            <td style="border:0px;width:100px;"></td>
                                            <td style="border:0px;width:100px;"></td>
                                            <td style="border:0px;width:150px;"></td>
                                            <td style="border:0px;width:50px;"></td>
                                            <td style="border:0px;width:50px;"></td>
                                            <td style="border:0px;width:450px;"><?php echo "TOTAL DEL PERIODO";?></td>
                                            <td style="border:0px;width:120px;"><?php echo $debe1;?></td> 
                                            <td style="border:0px;width:120px;"><?php echo $haber1;?></td> 
                                            <td style="padding-left:15px;border:0px;width:120px;"><?php echo $saldo;?></td>                                                            
                                        </tr>    
                                </table>   
                         <?php } ?>
                                         
                    </table>   
                                      
                <?php } ?>
                
            </div>
         <?php } ?>

    </div>
</body>
</html>