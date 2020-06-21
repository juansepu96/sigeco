<?php


require_once "pdo.php";

$modif='false';

$validate1=strpos($_SESSION['user.role'],"PDV");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
     $sepuede='true';
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['open'])){
    $Open=$conexion->prepare("SELECT * FROM pdv WHERE ID=:id");
    $Open->bindParam(':id',$_POST['pdv_ID']);
    $Open->execute();
    foreach ($Open as $PDV){
        $pdv_ID=$PDV['ID'];
        $description=$PDV['description'];
    }

    $_SESSION['OID']=$pdv_ID;
    $_SESSION['Odescription']=$description;
    $modif='true';

}

if(isset($_POST['update'])){
    $original_ID=$_POST['original_pdvID'];
    $IDNumber=$_POST['pdv_ID'];
    $SearchSellers=$conexion->prepare("SELECT * FROM sellers WHERE pdv_ID=:id");
    $SearchSellers->bindParam(':id',$original_ID);
    $SearchSellers->execute();
    $filas=$SearchSellers->rowcount();

    if($filas!=0 && $original_ID!=$IDNumber){
        echo "<script language='JavaScript'>alert('No se puede modificar el ID de este PDV porque hay vendedores vinculados a él. Debe eliminar todos los vendedores de ese PDV para poder continuar.');</script>"; 
    }else{
        $Update=$conexion->prepare("UPDATE pdv SET ID=:new_id, description=:description WHERE ID=:idnumber");
        $Update->bindParam(':idnumber',$original_ID);
        $Update->bindParam(':new_id',$IDNumber);
        $Update->bindParam(':description',$_POST['description']);
        $Update->execute();
        $movement="MODIFICAR PUNTO DE VENTA";
        $description="";
        if($_SESSION['OID']!=$IDNumber){
            $description=$description."SE CAMBIO EL ID ".$_SESSION['OID']." POR ".$IDNumber."<br>";
        }
        if($_SESSION['Odescription']!=$_POST['description']){
            $description=$description."SE CAMBIO LA DESCRIPCION ".$_SESSION['Odescription']." POR ".$_POST['description']."<br>";
        }
        $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
        $NewMovement->bindParam(':user',$_SESSION['user.name']);
        $NewMovement->bindParam(':date',$date);
        $NewMovement->bindParam(':time',$time);
        $NewMovement->bindParam(':movement',$movement);
        $NewMovement->bindParam(':description',$description);
        $NewMovement->execute();
    }
}

$PDV=$conexion->query("SELECT * from pdv");
$Consulta=$conexion->query("SELECT * from pdv");


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
    <script type="text/javascript" src="change-pdv.js"></script>
    <title>Listar Punto de Ventas - SiGeCo v1.0</title>
</head>
<body>

<div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "pdv_menu.php"; ?>
</div>

    <div class="content">
            <div class="table">
               <?php if ($modif=='false') { ?>
                    <table>
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th>Descripcion</th>
                            <th style="width:10%;">Seleccionar</th>
                        </tr>
                        <?php foreach ($Consulta as $PV) { ?>
                            <form method="post" action="change-pdv.php">
                                <tr>
                                    <td style="width:100px;"><?php echo $PV['ID'];?></td>
                                    <td><?php echo $PV['description'];?></td>
                                    <td hidden><input type="text"  name="pdv_ID" value="<?php echo $PV['ID'];?>"></td>
                                    <td style="background:green;cursor:pointer;"><input style="cursor:pointer;color:white; background:green;" type="submit" value="ABRIR" name="open"></td>
                                </tr>
                            </form>
                        <?php } ?>

                    </table>
                <?php } ?>

                <?php if ($modif=='true') { ?>
                    <form action="change-pdv.php" method="post" id="newseller-form">
                        <h2 style="text-align:center; padding:10px;">Modificar Punto De Venta</h2>
                            <strong style="margin-right:90px;">ID:</strong><input required type="text" class="newseller-field" name="pdv_ID" value="<?php echo $pdv_ID;?>" onblur="ValidarPDV()"><br>
                            <strong style="margin-right:10px;">Descripcion:</strong><input required type="text" class="newseller-field"  value="<?php echo $description;?>" name="description" ><br>
                            <input hidden type="text" class="newseller-field"  value="<?php echo $pdv_ID;?>" name="original_pdvID" ><br>

                            <div id="validationOK">
                                <input readonly onclick="if(confirm('CAMBIARA LOS DATOS DEL PDV, DESEA CONTINUAR?')){
                                this.form.submit();}
                                else{ alert('Operacion Cancelada');}" style="transform:translatex(400px);height:50px;color:white;" type="submit" class="menu-button" value="Actualizar" name="update">
                            </div>

                            <div hidden id="validationERROR" style="transform:translatey(60px);">
                                <strong style="color:white; background:red; padding:5px;">ERROR. ID DE PUNTO DE VENTA YA EXISTE.</strong><BR>
                                <strong style="color:white; background:red; padding:5px;">UTILICE UN ID QUE NO ESTE EN USO O MANTENGA SU ID ORIGINAL.</strong>

                                <div id="table-pdv">
                                    <table style="transform:translate(420px,-200px);">
                                        <tr>
                                            <th>PV</th>
                                            <th>Descripcion</th>
                                        </tr>
                                        <?php foreach ($PDV as $Unique) { ?>
                                            <tr>
                                                <td><?php echo $Unique['ID'];?></td>
                                                <td><?php echo $Unique['description'];?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>

                            <div hidden id="popup-pdv">
                                <input style="float:right; padding:10px; background:blue; color:white;cursor:pointer;margin:10px;margin-buttom:0px;border-radius:10px;" type="button" id="close-popup" value="Cerrar"><br><br><br>
                                <table style="margin:10px; heigth:150px;">
                                        <tr>
                                            <th style="width:150px;">PV</th>
                                            <th style="width:600px;">Descripcion</th>
                                        </tr>
                                        <?php $PDV=$conexion->query("SELECT * from pdv");?>
                                        <?php foreach ($PDV as $Unique) { ?>
                                            <tr>
                                                <td><?php echo $Unique['ID'];?></td>
                                                <td><?php echo $Unique['description'];?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                            </div>

                    </form>
                <?php } ?>
            </div>


    </div>
    
</body>
</html>