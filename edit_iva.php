<?php
 require_once "pdo.php";

 $ID=$_SESSION['iva.edit']; 

 $GetIVA=$conexion->prepare("SELECT * FROM iva WHERE ID=:id");
 $GetIVA->bindParam(':id',$ID);
 $GetIVA->execute();

 foreach ($GetIVA as $IVA){
     $ID=$IVA['ID'];
     $denominacion=$IVA['nombre'];
     $tasa=$IVA['tasa'];
 }

 if(isset($_POST['edit'])){     
    $denominacion=$_POST['name'];
    $tasa=$_POST['tasa'];
        $InsertarProducto=$conexion->prepare("UPDATE iva SET nombre=:name,tasa=:tasa WHERE ID=:id");
        $InsertarProducto->bindParam(':id',$ID);
        $InsertarProducto->bindParam(':name',$denominacion);
        $InsertarProducto->bindParam(':tasa',$tasa);
        $InsertarProducto->execute();

        header ("Location: iva.php");
        
    
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
    <title>Editar IVA - SiGeCo v 1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "iva_menu.php"; ?>
    </div>

    <div class="content">

        <div class="table">

            <form method="post" action="edit_iva.php" autocomplete="off" style="transform:translateX(150px);">

                <h2 style="text-align:center; padding:10px;">Editar IVA</h2>

                <strong  style="margin-right:20px;">Denominacion:</strong><input required type="text" value="<?php echo $denominacion;?>" class="newseller-field" name="name" style="font-size:1em;"> <br><br>

                <strong  style="margin-right:95px;">Tasa en %:</strong><input required type="text" value="<?php echo $tasa;?>" class="newseller-field" name="tasa" style="font-size:1em;"> <br><br>

                <input type="submit" value="Actualizar" name="edit" class="menu2-button" style="color:white;transform:translatex(500px);">

                </form>

                


        </div>

    </div>

</body>
</html>