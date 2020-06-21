<?php
 require_once "pdo.php";

 $ID=$_SESSION['producto.edit']; 

 $ObtenerProducto=$conexion->prepare("SELECT * FROM products WHERE ID=:id");
 $ObtenerProducto->bindParam(':id',$ID);
 $ObtenerProducto->execute();

 foreach ($ObtenerProducto as $Producto){
     $ID=$Producto['ID'];
     $denominacion=$Producto['name'];
     $stock=$Producto['stock'];
     $costo=$Producto['costo'];
     $precio_neto=$Producto['precio_neto'];
     $IVA=$Producto['IVA'];
     $imp_interno=$Producto['imp_interno'];
     $imp_interno_valor=$Producto['imp_interno_valor'];
 }

 if(isset($_POST['edit'])){     
    $denominacion=$_POST['name'];
    $stock=$_POST['cantidad'];
    $costo_u=$_POST['costo_unitario'];
    $precio_n=$_POST['precio_neto'];
    $iva=$_POST['iva'];
    $impuesto_int=$_POST['impuesto_interno'];
    if($impuesto_int!="0"){
        $valor_imp_interno=$_POST['impuesto_interno_valor'];
    }else{
        $valor_imp_interno="0";
    }
        $InsertarProducto=$conexion->prepare("UPDATE products SET name=:name,stock=:stock,costo=:costo,precio_neto=:precio_neto,IVA=:IVA,imp_interno=:imp_interno,imp_interno_valor=:imp_interno_valor WHERE ID=:id");
        $InsertarProducto->bindParam(':id',$ID);
        $InsertarProducto->bindParam(':name',$denominacion);
        $InsertarProducto->bindParam(':stock',$stock);
        $InsertarProducto->bindParam(':costo',$costo_u);
        $InsertarProducto->bindParam(':precio_neto',$precio_n);
        $InsertarProducto->bindParam(':IVA',$iva);
        $InsertarProducto->bindParam(':imp_interno',$impuesto_int);
        $InsertarProducto->bindParam(':imp_interno_valor',$valor_imp_interno);
        $InsertarProducto->execute();

        header ("Location: productos.php");
        
    
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
    <script type="text/javascript" src="productos_validate.js"></script>
    <title>Editar Producto - SiGeCo v 1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "products_menu.php"; ?>
    </div>

    <div class="content">

        <div class="table">

            <form method="post" action="edit_producto.php" autocomplete="off" style="transform:translateX(150px);">

                <h2 style="text-align:center; padding:10px;">Nuevo Producto</h2>

                <strong  style="margin-right:20px;">Denominacion:</strong><input required type="text" value="<?php echo $denominacion;?>" class="newseller-field" name="name" style="font-size:1em;"> <br><br>

                <strong  style="margin-right:95px;">Stock:</strong><input required type="text" value="<?php echo $stock;?>" class="newseller-field" name="cantidad" style="font-size:1em;"> <br><br>

                <strong  style="margin-right:15px;">Costo unitario:</strong><input required value="<?php echo $costo;?>" type="text" class="newseller-field" name="costo_unitario" style="font-size:1em;"> <br><br>

                <strong   style="margin-right:5px;">Precio Neto vta.:</strong><input required value="<?php echo $precio_neto;?>" type="text" class="newseller-field" name="precio_neto" style="font-size:1em;"> <br><br>


                <strong style="margin-rigth:100px;">Tasa de IVA</strong>
                <select required name="iva" id="iva" style="margin-rigth:200px;padding:15px;border-radius:15px;width:200px;background:white;font-size:1em;" class="newseller-field">
                <option selected disabled value=""></option>
                        <?php
                            $ObtenerTasas=$conexion->query("SELECT * FROM iva");
                            foreach ($ObtenerTasas as $tasa){ ?>
                                <option value="<?php echo $tasa['tasa'];?>"><?php echo $tasa['nombre'];?></option>
                            <?php } ?>                                       
                </select> <br> <br>

                <strong style="margin-rigth:75px;">Impuesto Interno</strong>
                <select required name="impuesto_interno" id="impuesto_interno" style="padding:15px;border-radius:15px;width:200px;background:white;font-size:1em;" class="newseller-field" onChange="ValidarImpuestoInterno();">
                        <option selected disabled value=""></option>
                        <option value="$">$</option>
                        <option value="%">%</option>
                        <option value="0">NO APLICA</option>            
                </select> <br> <br>

                <div hidden id="impuesto_interno_valor">
                    <strong  style="margin-right:0px;">Valor Imp. Interno:</strong><input value="<?php echo $imp_interno_valor;?>" type="text" class="newseller-field" name="impuesto_interno_valor" style="font-size:1em;"> <br><br>

                </div>
                <input type="submit" value="Agregar" name="edit" class="menu2-button" style="color:white;transform:translatex(500px);">

                </form>

                


        </div>

    </div>

</body>
</html>