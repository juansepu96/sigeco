<?php

require_once "pdo.php";

$paso1='true';
$paso2='false';
$paso3='false';
$listado='false';

if(isset($_POST['next'])){
    $fecha=date('Y-m-d');
    $comprobante=$_POST['tipo_comprobante'];
    $pdv="0008";
    $CrearFactura=$conexion->prepare("INSERT INTO factura_datos_temporal (fecha,tipo) VALUES (:fecha,:tipo)");
    $comprobante=substr($comprobante,-1);
    $CrearFactura->bindParam(':fecha',$fecha);
    $CrearFactura->bindParam(':tipo',$comprobante);
    $CrearFactura->execute();
    $ObtenerID=$conexion->query("SELECT* FROM factura_datos_temporal ORDER BY ID DESC");
    foreach ($ObtenerID as $ID){
        $_SESSION['factura.id']=$ID['ID'];
        break;
    }
    $paso2='true';
    $paso1='false';
}

if(isset($_POST['next2'])){
    $cliente_ID=$_POST['cliente_id'];
    $nombre_cliente=$_POST['cliente_nombre'];
    $direccion_cliente=$_POST['cliente_direccion'];
    $cuit_cliente=$_POST['cliente_cuit'];
    $sit_iva_cliente=$_POST['cliente_sit_iva'];
    $cond_venta=$_POST['cond_venta'];
    $CompletarFactura=$conexion->prepare("UPDATE factura_datos_temporal SET id_cliente=:id_cliente,nombre_cliente=:nombre_cliente,direccion_cliente=:direccion_cliente,cuit_cliente=:cuit_cliente,sit_iva_cliente=:sit_iva_cliente,cond_venta=:cond_venta WHERE ID=:id");
    $CompletarFactura->bindParam(':id',$_SESSION['factura.id']);
    $CompletarFactura->bindParam(':id_cliente',$cliente_ID);
    $CompletarFactura->bindParam(':nombre_cliente',$nombre_cliente);
    $CompletarFactura->bindParam(':direccion_cliente',$direccion_cliente);
    $CompletarFactura->bindParam(':cuit_cliente',$cuit_cliente);
    $CompletarFactura->bindParam(':sit_iva_cliente',$sit_iva_cliente);
    $CompletarFactura->bindParam(':cond_venta',$cond_venta);
    $CompletarFactura->execute();
    $paso2='false';
    $paso1='false';
    $paso3='true';
}

if(isset($_POST['next3'])){
    $cod_producto=$_POST['cod_producto'];
    $denominacion=$_POST['nom_producto'];
    $precio=$_POST['precio_producto'];
    $precio=substr($precio,1);
    $iva=$_POST['iva_producto'];    
    $valor_iva=substr($iva,0,-1);
    $cantidad=$_POST['cantidad_producto'];
    $valor_iva=($precio*$cantidad*$valor_iva)/100;
    $imp_interno=$_POST['imp_interno_producto'];
    
    $total=substr($_POST['precio_total'],1);
    if(is_int(strpos($imp_interno,'$'))){
        $imp_interno2=substr($imp_interno,1);
        $imp_interno_valor=($precio*$cantidad*$imp_interno2);
    }else{
        if(is_int(strpos($imp_interno,'%'))){
            $imp_interno2=substr($imp_interno,0,-1);
            $imp_interno_valor=($precio*$cantidad*$imp_interno2)/100;
        }else{
            $imp_interno_valor=0;
        }
    }
    $InsertarProducto=$conexion->prepare("INSERT INTO factura_productos_temporal (codigo,denominacion,precio,iva,valor_iva,imp_interno,valor_imp_interno,cantidad,total,ID_factura) VALUES (:codigo,:denominacion,:precio,:iva,:valor_iva,:imp_interno,:valor_imp_interno,:cantidad,:total,:ID_factura)");
    $InsertarProducto->bindParam(':codigo',$cod_producto);
    $InsertarProducto->bindParam(':denominacion',$denominacion);
    $InsertarProducto->bindParam(':precio',$precio);    
    $InsertarProducto->bindParam(':iva',$iva);    
    $InsertarProducto->bindParam(':valor_iva',$valor_iva);    
    $InsertarProducto->bindParam(':imp_interno',$imp_interno);    
    $InsertarProducto->bindParam(':valor_imp_interno',$imp_interno_valor);    
    $InsertarProducto->bindParam(':cantidad',$cantidad);
    $InsertarProducto->bindParam(':total',$total);
    $InsertarProducto->bindParam(':ID_factura',$_SESSION['factura.id']);
    $InsertarProducto->execute();
    
    $listado='true';
    $paso2='false';
    $paso1='false';
    $paso3='true';

}

if(isset($_POST['next5'])){
    $iva_10=0;
       $iva_21=0;
       $iva_27=0;
       $imp_interno=0;
       $neto=0;
    $CalcularDegravacion=$conexion->prepare("SELECT * FROM factura_productos_temporal WHERE ID_factura=:id");
    $CalcularDegravacion->bindParam(':id',$_SESSION['factura.id']);
    $CalcularDegravacion->execute();
    foreach ($CalcularDegravacion as $Prd){
        if($Prd['iva']=="10.5%"){
            $iva_10=$iva_10+$Prd['valor_iva'];
        }
        if($Prd['iva']=="21%"){
            $iva_21=$iva_21+$Prd['valor_iva'];
        }
        if($Prd['iva']=="27%"){
            $iva_27=$iva_27+$Prd['valor_iva'];
        }
        $imp_interno=$imp_interno+$Prd['valor_imp_interno'];
        $neto=$neto+($Prd['precio']*$Prd['cantidad']);
    }
   $Obtener_Encabezado=$conexion->prepare("SELECT * FROM factura_datos_temporal WHERE ID=:id");
   $Obtener_Encabezado->bindParam(':id',$_SESSION['factura.id']);
   $Obtener_Encabezado->execute();
   foreach ($Obtener_Encabezado as $Encabezado){
       $ID=$Encabezado['ID'];
       $fecha=$Encabezado['fecha'];
       $tipo=$Encabezado['tipo'];
       $id_cliente=$Encabezado['id_cliente'];
       $nombre_cliente=$Encabezado['nombre_cliente'];
       $direccion_cliente=$Encabezado['direccion_cliente'];
       $cuit_cliente=$Encabezado['cuit_cliente'];
       $sit_iva_cliente=$Encabezado['sit_iva_cliente'];
       $cond_venta=$Encabezado['cond_venta'];
       break;       
   }
   
   $anulada='NO';

   $InsertarDefinitiva=$conexion->prepare("INSERT INTO factura_datos VALUES (:id,:fecha,:tipo,:id_cliente,:nombre_cliente,:direccion_cliente,:cuit_cliente,:sit_iva_cliente,:iva_10,:iva_21,:iva_27,:imp_interno,:cond_venta,:anulada,:neto)");
   $InsertarDefinitiva->bindParam(':id',$ID);
   $InsertarDefinitiva->bindParam(':fecha',$fecha);
   $InsertarDefinitiva->bindParam(':tipo',$tipo);
   $InsertarDefinitiva->bindParam(':id_cliente',$id_cliente);
   $InsertarDefinitiva->bindParam(':nombre_cliente',$nombre_cliente);
   $InsertarDefinitiva->bindParam(':direccion_cliente',$direccion_cliente);
   $InsertarDefinitiva->bindParam(':cuit_cliente',$cuit_cliente);
   $InsertarDefinitiva->bindParam(':sit_iva_cliente',$sit_iva_cliente);
   $InsertarDefinitiva->bindParam(':iva_10',$iva_10);
   $InsertarDefinitiva->bindParam(':iva_21',$iva_21);
   $InsertarDefinitiva->bindParam(':iva_27',$iva_27);
   $InsertarDefinitiva->bindParam(':imp_interno',$imp_interno);
   $InsertarDefinitiva->bindParam(':cond_venta',$cond_venta);
   $InsertarDefinitiva->bindParam(':anulada',$anulada);
   $InsertarDefinitiva->bindParam(':neto',$neto);
   $InsertarDefinitiva->execute();

   $ObtenerProductos=$conexion->prepare("SELECT * FROM factura_productos_temporal WHERE ID_factura=:id");
   $ObtenerProductos->bindParam(':id',$_SESSION['factura.id']);
   $ObtenerProductos->execute();

   foreach($ObtenerProductos as $Producto1){
       $codigo=$Producto1['codigo'];
       $denominacion=$Producto1['denominacion'];
       $precio=$Producto1['precio'];
       $iva=$Producto1['iva'];
       $valor_iva=$Producto1['valor_iva'];
       $imp_interno=$Producto1['imp_interno'];
       $valor_imp_interno=$Producto1['valor_imp_interno'];
       $cantidad=$Producto1['cantidad'];
       $total=$Producto1['total'];
       $ID_factura=$Producto1['ID_factura'];
       
       $InsertarProducto=$conexion->prepare("INSERT INTO factura_productos (codigo,denominacion,precio,iva,valor_iva,imp_interno,valor_imp_interno,cantidad,total,ID_factura) VALUES (:codigo,:denominacion,:precio,:iva,:valor_iva,:imp_interno,:valor_imp_interno,:cantidad,:total,:ID_factura)");
       $InsertarProducto->bindParam(':codigo',$codigo);
       $InsertarProducto->bindParam(':denominacion',$denominacion);
       $InsertarProducto->bindParam(':precio',$precio);    
       $InsertarProducto->bindParam(':iva',$iva);    
       $InsertarProducto->bindParam(':valor_iva',$valor_iva);    
       $InsertarProducto->bindParam(':imp_interno',$imp_interno);    
       $InsertarProducto->bindParam(':valor_imp_interno',$valor_imp_interno);    
       $InsertarProducto->bindParam(':cantidad',$cantidad);
       $InsertarProducto->bindParam(':total',$total);
       $InsertarProducto->bindParam(':ID_factura',$ID_factura);
       $InsertarProducto->execute();       
   }

   echo "<script>window.open('factura_print.php', '_blank');</script>";



}

if(isset($_POST['next4'])){
    $id_prod=$_POST['prod_id'];
    $EliminarProducto=$conexion->prepare("DELETE FROM factura_productos_temporal WHERE ID=:id");
    $EliminarProducto->bindParam(':id',$id_prod);
    $EliminarProducto->execute();
    $listado='true';
    $paso2='false';
    $paso1='false';
    $paso3='true';

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
    <script type="text/javascript" src="new-comprobante.js"></script>
    <title>Emitir un Comprobante - SiGeCo v1.0</title>
</head>
<body>

<div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "factu_menu.php"; ?>

    </div>

    <div class="content">
        <div class="table">
        <h2 style="text-align:center; padding:10px;">EMITIR NUEVO COMPROBANTE</h2>
            <?php if($paso1=='true') { ?>
            <form action="new_factura.php" method="post" id="newseller-form" style="width:80%" autocomplete="off">
                <br> <h3 style="text-align:center; padding:10px;">Datos de la factura</h2>
                <br>
                <strong style="margin-left:-5px;">Fecha:</strong><input disabled type="date" class="newseller-field" value="<?php echo date('Y-m-d');?>" name="fecha" onchange="ValidarFecha();">
                <strong style="margin-left:-10px;">Pto. de Vta.:</strong>
                <select name="pdv" style="margin-rigth:200px;padding:15px;border-radius:15px;width:200px;background:white;font-size:1em;" class="newseller-field">
                        <option selected value="0008">0008 - Grupo: Sepúlveda</option>
                </select> 
                <strong style="margin-left:-10px;">T. de Comp.:</strong>
                <select required name="tipo_comprobante" style="margin-rigth:200px;padding:15px;border-radius:15px;width:200px;background:white;font-size:1em;" class="newseller-field">
                        <option selected disabled value=""></option>
                                <option value="FACTURA A">FACTURA A</option>
                                <option  disabled value="NOTA DE CREDITO A">NOTA DE CREDITO A</option>
                                <option  disabled value="NOTA DE DEBITO A">NOTA DE DEBITO A</option>
                                <option  value="FACTURA B">FACTURA B</option>
                                <option  disabled value="NOTA DE CREDITO B">NOTA DE CREDITO B</option>
                                <option  disabled value="NOTA DE DEBITO B">NOTA DE DEBITO B</option>
                </select> 
                <input type="submit" style="color:white;font-weight:bold;" value="Sig." name="next" id="next" class="menu2-button">
            </form>
            <?php } ?>
                

            <?php if($paso2=='true'){?> 
                <form action="new_factura.php" method="post" id="newseller-form" style="width:80%" autocomplete="off">
                <br> <h3 style="text-align:center; padding:10px;">Datos del cliente</h2>
                <br><input hidden id="factura_tipo" name="factura_tipo" value="<?php echo $comprobante;?>">
                <strong>Nro. Cliente: </strong><input required type="text" name="cliente_id" id="cliente_id" class="newseller-field" style="width:80px;" onblur="ValidarCliente();">
                <strong style="margin-left:-8px;">Nombre:</strong><input class="newseller-field" id="cliente_nombre" name="cliente_nombre"  type="text" style="width: 120px;" readonly>
                <strong style="margin-left:-8px;">CUIT:</strong><input class="newseller-field"  id="cliente_cuit" name="cliente_cuit"  type="text" style="width: 120px;" readonly>
                <strong style="margin-left:-8px;">Sit. en IVA:</strong><input  class="newseller-field" id="cliente_sit_iva" name="cliente_sit_iva" style="width:120px;"  type="text" readonly><br>
                <input  hidden class="newseller-field" id="cliente_direccion" name="cliente_direccion" style="width:120px;"  type="text" readonly><br>
                <strong>Cond. Venta: </strong>
                <select required name="cond_venta" style="margin-rigth:200px;padding:15px;border-radius:15px;width:200px;background:white;font-size:1em;" class="newseller-field">
                        <option selected disabled value=""></option>
                                <option value="CONTADO">CONTADO</option>
                                <option  disabled value="TARJETA CREDITO">TARJETA DE CREDITO </option>
                                <option  disabled value="TARJETA DEBITO">TARJETA DE DEBITO </option>
                </select> 
                <input type="submit" style="color:white;font-weight:bold;" value="Sig." name="next2" id="next2" class="menu2-button">

                </form>

                <div hidden id="listarclientes" style="background:skyblue;width:600px;height:100%;transform:translate(400px,-100px);">
                      <input style="float:right; padding:10px; background:blue; color:white;cursor:pointer;margin:10px;margin-buttom:0px;border-radius:10px;" type="button" id="close-popup" value="Cerrar"><br><br><br>
                      <table style="margin:10px;width:100%;">
                            <tr>
                                <th style="width:150px;">Nro. Cliente</th>
                                <th>Nombre</th>
                                <th>CUIT</th>
                                <th>Sit. IVA</th>
                            </tr>
                            <?php $Accounts=$conexion->query("SELECT * from clientes");?>
                            <?php foreach ($Accounts as $Account) { ?>                                
                                <tr>
                                <?php if($comprobante=="A"){?>
                                    <?php if($Account['sit_iva']!="Responsable Inscripto") { ?>
                                    <td style="background:gray;" id="<?php echo $Account['ID'];?>"><?php echo $Account['ID'];?></td>
                                    <td style="background:gray;"><?php echo $Account['nombre'];?></td>
                                    <td style="background:gray;"><?php echo $Account['cuit'];?></td>
                                    <td style="background:gray;"><?php echo $Account['sit_iva'];?></td>
                                    <?php }else{ ?>
                                    <td style="cursor:pointer;" id="<?php echo $Account['ID'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $Account['ID'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['nombre'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $Account['nombre'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['cuit'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $Account['cuit'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['sit_iva'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $Account['sit_iva'];?></td>
                                    <?php } ?>
                                <?php } ?>

                                <?php if($comprobante=="B"){?>
                                    <?php if($Account['sit_iva']=="Responsable Inscripto") { ?>
                                    <td style="background:gray;" id="<?php echo $Account['ID'];?>"><?php echo $Account['ID'];?></td>
                                    <td style="background:gray;"><?php echo $Account['nombre'];?></td>
                                    <td style="background:gray;"><?php echo $Account['cuit'];?></td>
                                    <td style="background:gray;"><?php echo $Account['sit_iva'];?></td>
                                    <?php }else{ ?>
                                    <td style="cursor:pointer;" id="<?php echo $Account['ID'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $Account['ID'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['nombre'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $Account['nombre'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['cuit'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $Account['cuit'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['sit_iva'];?>" onclick="Completar(<?php echo $Account['ID'];?>);" ><?php echo $Account['sit_iva'];?></td>
                                    <?php } ?>
                                <?php } ?>
                               

                                
                                </tr>
                            <?php } ?>
                        </table>
        </div>

            <?php } ?>

            <?php if($paso3=='true'){ ?>
                <form action="new_factura.php" method="post" id="newseller-form" style="width:80%" autocomplete="off">
              <br> <h3 style="text-align:center; padding:10px;">Agregar Producto</h2>
           
                <table style="padding:20px;content-align:center;">
                    <tr>
                        <th>Cod. Producto</th>
                        <th>Denominacion</th>
                        <th>Precio</th>
                        <th>IVA</th>
                        <th>Imp. Interno</th>                        
                        <th>Cantidad</th>
                        <th>TOTAL</th>
                    </tr>

                    <tr>
                        <th><input type="text" class="newseller-field" name="cod_producto" required style="width:80px;" onblur="CargarProducto();"></th>
                        <th><input type="text" readonly class="newseller-field" id="nom_producto" required name="nom_producto" style="width:250px;"></th>
                        <th><input type="text" readonly class="newseller-field" id="precio_producto" required name="precio_producto" style="width:50px;"></th>
                        <th><input type="text" readonly class="newseller-field" id="iva_producto" required name="iva_producto" style="width:50px;"></th>
                        <th><input type="text" class="newseller-field" readonly id="imp_interno_producto" name="imp_interno_producto" required style="width:50px;"></th>
                        <th><input type="text" class="newseller-field" id="cantidad_producto" name="cantidad_producto" required style="width:60px;" onblur="TotalizarProducto();"></th>
                        <th><input type="text" readonly class="newseller-field" id="precio_total" name="precio_total" required style="width:80px;"></th>
                        <th><input type="submit" class="menu2-button" style="width:70px;color:white;" id="next3" name="next3" value="Agregar"></th>
                    </tr>

                </table>

                </form>

                <div hidden id="listarproductos" style="background:skyblue;width:600px;height:100%;transform:translate(400px,-100px);">
                      <input style="float:right; padding:10px; background:blue; color:white;cursor:pointer;margin:10px;margin-buttom:0px;border-radius:10px;" type="button" id="close-popup" value="Cerrar"><br><br><br>
                      <table style="margin:10px;width:100%;">
                            <tr>
                                <th style="width:150px;">Cod. Producto</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>IVA</th>
                            </tr>
                            <?php $Accounts=$conexion->query("SELECT * from products");?>
                            <?php foreach ($Accounts as $Account) { ?>                                
                                <tr>
                                <?php if($Account['stock']<0){?>
                                    <td style="background:gray;" id="<?php echo $Account['ID'];?>"><?php echo $Account['ID'];?></td>
                                    <td style="background:gray;"><?php echo $Account['name'];?></td>
                                    <td style="background:gray;"><?php echo $Account['precio_neto'];?></td>
                                    <td style="background:gray;"><?php echo $Account['IVA']."%";?></td>
                                    
                                <?php }else { ?>
                                    <td style="cursor:pointer;" id="<?php echo $Account['ID'];?>" onclick="Completar2(<?php echo $Account['ID'];?>);" ><?php echo $Account['ID'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['name'];?>" onclick="Completar2(<?php echo $Account['ID'];?>);" ><?php echo $Account['name'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['precio_neto'];?>" onclick="Completar2(<?php echo $Account['ID'];?>);" ><?php echo $Account['precio_neto'];?></td>
                                    <td style="cursor:pointer;" id="<?php echo $Account['IVA']."%";?>" onclick="Completar2(<?php echo $Account['ID'];?>);" ><?php echo $Account['IVA'];?></td>
                                    
                                <?php } ?>   
                                
                                </tr>
                            <?php } ?>
                        </table>
        </div>

            <?php } ?>

            <?php if($listado=='true') { ?>
                <form action="new_factura.php" method="post" id="newseller-form" style="width:80%" autocomplete="off">
              <br> <h3 style="text-align:center; padding:10px;">Productos Agregados</h2>
           
                <table style="padding:20px;content-align:center;">
                    <tr>
                        <th>Cod. Producto</th>
                        <th style="width:300px;">Denominacion</th>
                        <th>Precio</th>
                        <th>IVA</th>
                        <th>Imp. Interno</th>                        
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>QUITAR</th>
                    </tr>

                    
                        <?php $GetProductos=$conexion->prepare("SELECT * FROM factura_productos_temporal WHERE ID_factura=:id");
                                $GetProductos->bindParam(':id',$_SESSION['factura.id']);
                                $GetProductos->execute();
                                foreach ($GetProductos as $Producto) {  ?>
                                <tr>
                                <form action="new_factura.php" method="post" style="width:80%">                                
                                <td hidden><input type="text" value="<?php echo $Producto['ID'];?>" name="prod_id"></td>
                                <td><?php echo $Producto['codigo'];?></td>
                                <td><?php echo $Producto['denominacion'];?></td>
                                <td><?php echo "$".$Producto['precio'];?></td>
                                <td><?php echo $Producto['iva']."%";?></td>
                                <td><?php echo $Producto['imp_interno'];?></td>
                                <td><?php echo $Producto['cantidad'];?></td>
                                <td><?php echo "$".$Producto['total'];?></td>
                                <td style="background:red;"><input type="submit" style="background:red;cursor:pointer;color:white;" value="Quitar" name="next4" id="next4"></td>                                
                                </form>
                                </tr>
                             <?php } ?>
                     

                </table>

                </form>

                <form action="new_factura.php" method="post" name="CargarFactura" id="CargarFactura">
                    <input type="submit" class="menu2-button" value="Cargar Factura" name="next5" onclick="Confirmacion();">
                </form>

            <?php } ?>
            
        </div>

    </div>

    <script type="text/javascript">
        //Clickeable tabla clientes
        function Completar(id_fila) {
            valoringresado = id_fila;
            if(valoringresado != "" ){
                $('input:text[name=cliente_id]').val(valoringresado);
            };
            $('#listarclientes').hide();
            ValidarCliente();
        }       
        function Completar2(id_fila) {
            valoringresado = id_fila;
            if(valoringresado != "" ){
                $('input:text[name=cod_producto]').val(valoringresado);
            };
            $('#listarproductos').hide();
            CargarProducto();
        }        
    </script>
    
</body>
</html>