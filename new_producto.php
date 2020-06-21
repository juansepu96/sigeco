<?php 

require_once "pdo.php";

if(isset($_POST['add'])){    
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
    $InsertarProducto=$conexion->prepare("INSERT INTO products (name,stock,costo,precio_neto,IVA,imp_interno,imp_interno_valor) VALUES (:name,:stock,:costo,:precio_neto,:IVA,:imp_interno,:imp_interno_valor)");
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
    <title>Nuevo Producto - SiGeCo v 1.0</title>
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

            <form method="post" action="new_producto.php" id="newseller-form" autocomplete="off" style="transform:translateX(150px);">

                <h2 style="text-align:center; padding:10px;">Nuevo Producto</h2>

                <strong  style="margin-right:20px;">Denominacion:</strong><input required type="text" class="newseller-field" name="name" style="font-size:1em;"> <br><br>

                <strong  style="margin-right:95px;">Stock:</strong><input required type="text" class="newseller-field" name="cantidad" style="font-size:1em;"> <br><br>

                <strong  style="margin-right:15px;">Costo unitario:</strong><input required type="text" class="newseller-field" name="costo_unitario" style="font-size:1em;"> <br><br>

                <strong   style="margin-right:5px;">Precio Neto vta.:</strong><input required type="text" class="newseller-field" name="precio_neto" style="font-size:1em;"> <br><br>


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
                    <strong  style="margin-right:0px;">Valor Imp. Interno:</strong><input type="text" class="newseller-field" name="impuesto_interno_valor" style="font-size:1em;"> <br><br>

                </div>
                <input type="submit" value="Agregar" name="add" class="menu2-button" style="color:white;transform:translatex(500px);">

                </form>

                


        </div>

    </div>

</body>
</html>