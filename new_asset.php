<?php

require_once "pdo.php";


$AssetID='';
$AssetDate='';
$generado='false';
$linea=1;

    $suma_debe=0;
    $suma_haber=0;


if(isset($_POST['generate'])){
    $date=$_POST['date'];
    $RowDateOp=$date;
    $RowDateVen=$date;
    $status='0';
    $type=intval($_POST['asset_type']);
    $NewAsset=$conexion->prepare("INSERT INTO assets  (date,status,type) VALUES (:date,:status,:type)");
    $NewAsset->bindParam(':date',$date);
    $NewAsset->bindParam(':status',$status);
    $NewAsset->bindParam(':type',$type);
    $NewAsset->execute();
    $NewAsset=$conexion->query("SELECT * FROM assets ORDER BY ID desc");
    foreach ($NewAsset as $Asset){
        $AssetID=$Asset['ID'];
        break;
    }
    $AssetDate=$date;
    $AssetType=intval($type);
    $generado='true';
}

if(isset($_POST['save'])){
    $AssetID=$_POST['asset_num2'];
    $RowAccount=$_POST['account_renglon'];
    $RowNumber=$_POST['renglon_num2'];
    $RowDateOp=$_POST['date_renglon'];
    $RowDateVen=$_POST['date_venc_renglon'];
    $RowSuc=$_POST['sucursal_renglon'];
    $RowSecc=$_POST['seccion_renglon'];
    $RowComp=$_POST['comprobante_renglon'];
    $RowConcepto=$_POST['concepto_renglon'];
    $RowConcepto=substr($RowConcepto,0,60);
    $RowType=$_POST['tipo'];
    $RowImporte=$_POST['importe_renglon'];
    $status=0;
    $InsertarRenglon=$conexion->prepare("INSERT INTO assets_row (asset_number,row_number,account,date_op,date_ven,suc,secc,concep,type,import,comprobante,status) VALUES (:assest_number,:row_number,:account,:date_op,:date_ven,:suc,:secc,:concep,:type,:import,:comprobante,:status)");
    $InsertarRenglon->bindParam(':assest_number',$AssetID);
    $InsertarRenglon->bindParam(':row_number',$RowNumber);
    $InsertarRenglon->bindParam(':account',$RowAccount);
    $InsertarRenglon->bindParam(':date_op',$RowDateOp);
    $InsertarRenglon->bindparam(':date_ven',$RowDateVen);
    $InsertarRenglon->bindParam(':suc',$RowSuc);
    $InsertarRenglon->bindParam(':secc',$RowSecc);
    $InsertarRenglon->bindParam(':concep',$RowConcepto);
    $InsertarRenglon->bindParam(':type',$RowType);
    $InsertarRenglon->bindParam(':import',$RowImporte);
    $InsertarRenglon->bindParam(':comprobante',$RowComp);
    $InsertarRenglon->bindParam(':status',$status);
    $InsertarRenglon->execute();
    $generado='true';
    $linea=$RowNumber+1;
    $AssetDate=$_POST['asset_date2'];
    $AssetType=intval($_POST['asset_type2']);
   
}




if(isset($_POST['ok'])){
    $AssetID=$_POST['asset_num3'];
    $SaveAsset=$conexion->prepare("UPDATE assets SET status=1 WHERE ID=:id");
    $SaveAsset->bindParam(':id',$AssetID);
    $SaveAsset->execute();
    $UpdateRows=$conexion->prepare("UPDATE assets_row SET status=1 WHERE asset_number=:id");
    $UpdateRows->bindParam(':id',$AssetID);
    $UpdateRows->execute();
    header ("Location: asientos.php");
}

if(isset($_POST['edit'])){
    $RenglonID=$_POST['item_delete'];
    $GetData=$conexion->prepare("SELECT * From assets_row WHERE ID=:id");
    $GetData->bindParam(':id',$RenglonID);
    $GetData->execute();
    foreach($GetData as $Data){
        $RowDateOp=$Data['date_op'];
        $RowDateVen=$Data['date_ven'];
        $RowSuc=$Data['suc'];
        $RowSecc=$Data['secc'];
        $RowConcepto=$Data['concep'];
        $RowImporte=$Data['import'];
        $RowComp=$Data['comprobante'];
        break;
    }
    $DeleteItem=$conexion->prepare("DELETE FROM assets_row WHERE ID=:id");
    $DeleteItem->bindParam(':id',$RenglonID);
    $DeleteItem->execute();
    $AssetID=$_POST['asset_num2'];
    $generado="true";
    $AssetDate=$_POST['asset_date2'];
    $AssetType=intval($_POST['asset_type2']);
}

if(isset($_POST['delete'])){
    $RenglonID=$_POST['item_delete'];
    $DeleteItem=$conexion->prepare("DELETE FROM assets_row WHERE ID=:id");
    $DeleteItem->bindParam(':id',$RenglonID);
    $DeleteItem->execute();
    $AssetID=$_POST['asset_num2'];
    $generado="true";
    $AssetDate=$_POST['asset_date2'];
    $AssetType=intval($_POST['asset_type2']);
}


if($generado=='true'){
    $ObtenerRenglones=$conexion->prepare("SELECT * FROM assets_row WHERE asset_number=:asset_id");
    $ObtenerRenglones->bindParam(':asset_id',$AssetID);
    $ObtenerRenglones->execute();
    foreach ($ObtenerRenglones as $Renglon){
        if(intval($Renglon['type'])==0){
            $suma_debe=$suma_debe+$Renglon['import'];
            $suma_debe=bcdiv($suma_debe,1,3);
        }else{
            $suma_haber=$suma_haber+$Renglon['import'];
            $suma_haber=bcdiv($suma_haber,1,3);
        }
    }


    $ObtenerRenglones=$conexion->prepare("SELECT * FROM assets_row WHERE asset_number=:asset_id");
    $ObtenerRenglones->bindParam(':asset_id',$AssetID);
    $ObtenerRenglones->execute();
}

if($suma_debe==$suma_haber){
    $haybalance="true";
}else{
    $haybalance="false";
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
    <title>Nuevo Asiento - SiGeCo v 1.0</title>    
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


        <form method="post" action="new_asset.php">

            <ul class="SecondMenu" style="transform:translate(400px,0px);">    
            <input hidden value="<?php echo $AssetID;?>" name="asset_num3">
            <input hidden value="<?php echo $AssetDate;?>" name="asset_date3">
            <input hidden value="<?php echo $AssetType;?>" name="asset_type3">

                        <a href="asientos.php"><h4 class="menu2-button">DESCARTAR</h4></a>

                        <?php if(($haybalance=='true') && ($generado=='true')) { ?>
                        <input type="submit" class="menu2-button" style="color:white;font-size:1.2em;" value="GRABAR" name="ok">
                        <?php } ?>

                        <?php if(($haybalance=='false') OR ($generado=='false')) { ?>
                        <input type="submit" disabled class="menu2-button" style="color:white;font-size:1.2em;background:gray;" value="GRABAR" name="ok">
                        <?php } ?>
                    </form>
            </ul>
                        </form>

                 <form method="post" action="new_asset.php">


         <div style="margin:5px;padding:15px;border:2px solid black;border-radius:10px;">
            <?php if($generado=='false'){ ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Fecha: </strong><input class="newseller-field" required type="date" id="date" name="date" onblur="ValidarFecha();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Num. Asiento : </strong><input disabled readonly class="newseller-field" type="text" style="text-align:center;" value="<?php echo $AssetID;?>" name="num_asset">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Tipo: </strong>
                <select name="asset_type" class="newseller-field" onchange="ValidarTipo();" id="tipo">
                    <option value="0">Apertura</option>
                    <option selected value="5">Normal</option>
                    <option value="9">Cierre</option>
                </select>
            <input type="submit" disabled class="menu2-button" style="width:100px;cursor:pointer;color:white;background:gray;" id="generate" name="generate" value="GENERAR">
            <?php } ?>

            <?php if($generado=='true') { ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Fecha: </strong><input disabled class="newseller-field" required type="date" name="date" value="<?php echo $AssetDate;?>">
            <input hidden value="<?php echo $AssetDate;?>" name="asset_date2">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Num. Asiento : </strong><input type="text" disabled readonly class="newseller-field" value="<?php echo $AssetID;?>" name="num_asset">
            <?php if(is_int($AssetType)){if($AssetType==0) { $AssetType="Apertura"; } else { if($AssetType==5) { $AssetType="Normal";}else {$AssetType="Cierre";}}} ?>
            <strong>Tipo: </strong><input type="text" disabled class="newseller-field" name="asset_type" value="<?php echo $AssetType;?>">
            <input hidden value="<?php echo $AssetType;?>" name="asset_type2">
            =============================================================================================================================================
            <strong>N° de Item: </strong><input type="text" disabled readonly class="newseller-field" style="width:20px;" name="num_renglon" value="<?php echo $linea;?>">
            <input hidden value="<?php echo $AssetID;?>" name="asset_num2">
            <input hidden value="<?php echo $linea;?>" name="renglon_num2">
            <strong>Cuenta: </strong><input required type="text" class="newseller-field" style="width:80px;" type="text" name="account_renglon" id="account_renglon" onblur="ValidarAccount();">
            <input type="text" disabled readonly class="newseller-field" style="width:300px" name="account_name" id="account_name">
            <strong>F. Oper.: </strong><input required type="date" class="newseller-field" name="date_renglon" value="<?php  if(isset($RowDateOp)) { echo $RowDateOp; }?>">
            <strong>F. Venc.: </strong><input type="date" class="newseller-field" name="date_venc_renglon" value="<?php  if(isset($RowDateVen)) { echo $RowDateVen; }?>">
            <strong>Suc.: </strong><input type="text" class="newseller-field" name="sucursal_renglon" style="width:20px;" value="<?php  if(isset($RowSuc)) { echo $RowSuc; }?>">
            <strong>Secc.: </strong><input type="text" class="newseller-field" name="seccion_renglon" style="width:20px;" value="<?php  if(isset($RowSecc)) { echo $RowSecc; }?>">
            <strong>Concepto: </strong><input required type="text" class="newseller-field" name="concepto_renglon" style="width:450px;" value="<?php  if(isset($RowConcepto)) { echo $RowConcepto; }?>">
            <strong>Comprobante: </strong><input type="text" class="newseller-field" name="comprobante_renglon" style="width:190px;" value="<?php  if(isset($RowComp)) { echo $RowComp; }?>">
            <strong>Tipo:</strong>
                <select required name="tipo" class="newseller-field" style="background:white;">
                    <option disabled selected></option>
                    <option value="0">DEBE</option>
                    <option value="1">HABER</option>
                </select>
            <strong>Importe: </strong><input required type="number" autocomplete="off" step="0.01" class="newseller-field" name="importe_renglon" style="width:70px;" value="<?php  if(isset($RowImporte)) { echo $RowImporte; }?>">
            <input type="submit" class="menu2-button" style="width:70px;cursor:pointer;color:white;" name="save" value="OK">
            </form>
            =============================================================================================================================================
            <table>
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
                    <th>Editar</th>
                    <th>Quitar</th>
            </tr>      
                <?php foreach ($ObtenerRenglones as $Renglon) { ?>
                    <form method="post" action="new_asset.php">
                    <tr>
                    <input hidden value="<?php echo $AssetType;?>" name="asset_type2">
                    <input hidden value="<?php echo $AssetID;?>" name="asset_num2">
                    <input hidden value="<?php echo $AssetDate;?>" name="asset_date2">

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
                        <td style="background:blue;"><input style="background:blue;color:white;" type="submit" name="edit" value="Editar" style="padding:5px;cursor:pointer;"></td>
                        <td style="background:red;"><input style="background:red;color:white;" type="submit" name="delete" value="Quitar" style="padding:5px;cursor:pointer;"></td>

                    </tr>
                    </form>
                <?php } ?>
            </table>

            <?php if ($haybalance=='false') { ?>
            <h2 style="margin:10px;color:red;text-align:center;padding:10px;font-size:2em;">EL DEBE Y EL HABER NO COINCIDEN</h2>
            <?php }else{ ?>
            <h2 style="margin:10px;color:green;text-align:center;padding:10px;font-size:2em;">EL DEBE Y EL HABER COINCIDEN</h2>
            <?php } ?>

           <h3 style="text-align:center;"> SALDO DEBE: <?php echo "$".number_format(floatval($suma_debe),2);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            SALDO HABER: <?php echo "$".number_format(floatval($suma_haber),2);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            DIFERENCIA: <?php $dif=$suma_debe-$suma_haber; echo "$".number_format($dif,2);?> </h3>

            <?php } ?>
            
            </div>


        </div>

        <div hidden id="listarcuentas" style="background:skyblue;width:600px;height:100%;transform:translate(-900px,10px);">
                      <input style="float:right; padding:10px; background:blue; color:white;cursor:pointer;margin:10px;margin-buttom:0px;border-radius:10px;" type="button" id="close-popup" value="Cerrar"><br><br><br>
                      <table style="margin:10px;width:100%;">
                            <tr>
                                <th style="width:150px;">Codigo</th>
                                <th>Cuenta</th>
                            </tr>
                            <?php $Accounts=$conexion->query("SELECT * from accounts order by code ASC");?>
                            <?php foreach ($Accounts as $Account) { ?>
                                <?php $nivel=$Account['level']; 

                                $espaciado='';

                                for ($i=$nivel;$i>1;$i--) {
                                    $espaciado=$espaciado.'&nbsp;&nbsp;&nbsp;&nbsp;';
                                }


                                ?>
                                <tr>
                                <?php if($Account['type']==0) { ?>
                                    <td style="background:gray;" id="<?php echo $Account['code'];?>"><?php echo $espaciado.$Account['code'];?></td>
                                    <td style="background:gray;"><?php echo $Account['name'];?></td>
                                <?php }else{ ?>
                                   <td style="cursor:pointer;" id="<?php echo $Account['code'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $espaciado.$Account['code'];?></td>
                                   <td><?php echo $Account['name'];?></td>
                                <?php } ?>

                                
                                </tr>
                            <?php } ?>
                        </table>
        </div>

    </div>

    <script type="text/javascript">
        //Clickeable tabla cuentas
        function Completar(id_fila) {
            valoringresado = id_fila;
            if(valoringresado != "" ){
                $.post("buscar-account2.php",{valorBusqueda:valoringresado}, function(description) {
                    if(description != ""){
                        $('input:text[name=account_renglon]').val(description);
                        $('input:text[name=account_renglon]').focus();

                    }
                });
            };
            $('#listarcuentas').hide();
            ValidarAccount();
        }
    </script>
</body>
</html>