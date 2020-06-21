<?php 

require_once "pdo.php";

if(isset($_POST['add'])){    
    $denominacion=$_POST['name'];
    $porcentaje=$_POST['cantidad'];
    
    $InsertarIVA=$conexion->prepare("INSERT INTO iva (nombre,tasa) VALUES (:name,:tasa)");
        $InsertarIVA->bindParam(':name',$denominacion);
        $InsertarIVA->bindParam(':tasa',$porcentaje);
        $InsertarIVA->execute();

        header ("Location: listar_iva.php");
        
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
        <?php require_once "iva_menu.php"; ?>
    </div>

    <div class="content">

        <div class="table">

            <form method="post" action="tipos_IVA.php" id="newseller-form" autocomplete="off" style="transform:translateX(150px);">

                <h2 style="text-align:center; padding:10px;">Nueva tasa IVA</h2>

                <strong  style="margin-right:20px;">Denominacion:</strong><input required type="text" class="newseller-field" name="name" style="font-size:1em;"> <br><br>

                <strong  style="margin-right:95px;">Porcentaje:</strong><input required type="text" class="newseller-field" name="cantidad" style="font-size:1em;"> <br><br>

                <input type="submit" value="Agregar" name="add" class="menu2-button" style="color:white;transform:translatex(500px);">

                </form>

                


        </div>

    </div>

</body>
</html>