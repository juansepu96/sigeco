<?php
 require_once "pdo.php";

 $ID=$_SESSION['cliente.edit']; 

 $GetCliente=$conexion->prepare("SELECT * FROM clientes WHERE ID=:id");
 $GetCliente->bindParam(':id',$ID);
 $GetCliente->execute();

 foreach ($GetCliente as $Cliente){
     $ID=$Cliente['ID'];
     $nombre=$Cliente['nombre'];
     $domicilio=$Cliente['domicilio'];
     $cuit=$Cliente['cuit'];
 }

 if(isset($_POST['editar'])){     
    $ID=$_SESSION['cliente.edit']; 
    $nombre=$_POST['name'];
    $domicilio=$_POST['domicilio'];
    $cuit=$_POST['cuit'];
    $sit_iva=$_POST['sit_iva'];

    $ActualizarCliente=$conexion->prepare("UPDATE clientes SET nombre=:nombre,domicilio=:domicilio,cuit=:cuit,sit_iva=:sit_iva WHERE ID=:id");
    $ActualizarCliente->bindParam(':id',$ID);
    $ActualizarCliente->bindParam(':nombre',$nombre);
    $ActualizarCliente->bindParam(':domicilio',$domicilio);
    $ActualizarCliente->bindParam(':cuit',$cuit);
    $ActualizarCliente->bindParam(':sit_iva',$sit_iva);
    $ActualizarCliente->execute();


     header ("Location: clientes.php");
        
    
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
    <script type="text/javascript" src="new-cliente.js"></script>
    <title>Editar Cliente - SiGeCo v 1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "clientes_menu.php"; ?>
    </div>

    <div class="content">

        <div class="table">

        <form action="edit_cliente.php" method="post" id="newseller-form" autocomplete="off">
                <h2 style="text-align:center; padding:10px;">Modificar un nuevo cliente</h2>
                <strong style="margin-right:55px;">Nombre:</strong><input required type="text" value="<?php echo $nombre;?>" class="newseller-field" name="name"><br>
                <strong style="margin-right:40px;">Domicilio:</strong><input required type="text" value="<?php echo $domicilio;?>" class="newseller-field" name="domicilio" style="width:177px;"><br>
                <strong style="margin-right:75px;">CUIT:</strong><input required type="text" value="<?php echo $cuit;?>" class="newseller-field" name="cuit"  id="cuit" onblur="ValidarCuit()"><br>
                <strong style="margin-right:-10px;">Sit. frente a IVA:</strong>
                <select required name="sit_iva" style="margin-rigth:200px;padding:15px;border-radius:15px;width:200px;background:white;font-size:1em;" class="newseller-field">
                        <option selected disabled value=""></option>
                        <?php
                            $ObtenerSitu=$conexion->query("SELECT * FROM sit_iva");
                            foreach ($ObtenerSitu as $Situ){ ?>
                                <option value="<?php echo $Situ['nombre'];?>"><?php echo $Situ['nombre'];?></option>
                            <?php } ?>             
                </select> <br> <br>
                <input hidden type="submit" value="Actualizar" name="editar" id="agregar" class="menu2-button">
            </form>
                


        </div>

    </div>

</body>
</html>