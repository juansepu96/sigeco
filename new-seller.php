<?php


require_once "pdo.php";

$validate1=strpos($_SESSION['user.role'],"VENDEDORES");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['send'])){
    $Consulta=$conexion->prepare("INSERT INTO sellers (DNI,name,birthdate,phone,email,pdv_ID,zone_ID) VALUES (:dni,:name,:birthdate,:phone,:email,:pdv_ID,:zone_ID)");
    $Consulta->bindParam(':dni',$_POST['dni']);
    $Consulta->bindParam(':name',$_POST['name']);
    $Consulta->bindParam(':birthdate',$_POST['birthdate']);
    $Consulta->bindParam(':phone',$_POST['phone']);
    $Consulta->bindParam(':email',$_POST['email']);
    $Consulta->bindParam(':pdv_ID',$_POST['pdv_ID']);
    $Consulta->bindParam(':zone_ID',$_POST['zone_ID']);
    $Consulta->execute();
    $movement="NUEVO VENDEDOR";
    $description="CARGA DEL VENDEDOR: ".$_POST['name'];
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
    $NewMovement->bindParam(':user',$_SESSION['user.name']);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->bindParam(':description',$description);
    $NewMovement->execute();
    header('Location:sellers.php');
}

$PDV=$conexion->query("SELECT * from pdv");
$Zones=$conexion->query("SELECT * from zones");



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
    <script type="text/javascript" src="new-seller.js"></script>
    <title>Dar de alta vendedores - SiGeCo v1.0</title>
</head>
<body>

<div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "sellers_menu.php"; ?>
    </div>

    <div class="content">
        <div class="table">
            <form action="new-seller.php" method="post" id="newseller-form" autocomplete="off">
                <h2 style="text-align:center; padding:10px;">Alta de un nuevo vendedor</h2>
                <strong style="margin-right:90px;">DNI:</strong><input required type="number" class="newseller-field" name="dni"><br>
                <strong style="margin-right:55px;">Nombre:</strong><input required type="text" class="newseller-field" name="name"><br>
                <strong style="margin-right:5px;">F. Nacimiento:</strong><input required type="date" class="newseller-field" name="birthdate" style="width:177px;"><br>
                <strong style="margin-right:53px;">Telefono:</strong><input required type="number" class="newseller-field" name="phone"><br>
                <strong style="margin-right:80px;">email:</strong><input required type="email" class="newseller-field" name="email"><br>
                <strong style="margin-right:30px;">P. de Venta:</strong><input required type="text" class="newseller-field" id="pdv_ID" name="pdv_ID" onblur="ValidarPDV()" onfocus="PDVHELP()"><br>

                <div hidden id="validationOK">
                    <strong style="margin-right:23px;">Descripción:</strong><input required readonly type="text" class="newseller-field" id="description"><br>
                </div>

                <div hidden id="validationERROR">
                    <strong style="color:white; background:red; padding:5px;">ERROR. PUNTO DE VENTA NO EXISTE.</strong><BR>
                    <strong style="color:white; background:red; padding:5px;">REINGRESE EL PUNTO DE VENTA.</strong>

                    <div id="table-pdv">
                        <table style="transform:translate(420px,-200px);">
                            <tr>
                                <th>PV</th>
                                <th>Descripcion</th>
                            </tr>
                            <?php foreach ($PDV as $Unique) { ?>
                                <tr>
                                    <td style="cursor:pointer;" id="<?php echo $Unique['ID'];?>" onclick="Completar(<?php echo $Unique['ID'];?>);" ><?php echo $Unique['ID'];?></td>
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
                                <th style="width:150px;">PDV</th>
                                <th style="width:600px;">Descripcion</th>
                            </tr>
                            <?php $PDV=$conexion->query("SELECT * from pdv");?>
                            <?php foreach ($PDV as $Unique) { ?>
                                <tr>
                                <td style="cursor:pointer;" id="<?php echo $Unique['ID'];?>" onclick="Completar2(<?php echo $Unique['ID'];?>);" ><?php echo $Unique['ID'];?></td>
                                    <td><?php echo $Unique['description'];?></td>
                                </tr>
                            <?php } ?>
                        </table>
                </div>

                <strong style="margin-right:70px;">ZONA:</strong><input required id="zone_ID" type="text" class="newseller-field" name="zone_ID" onblur="ValidarZona()" onfocus="ZONEHELP()"><br>
                <div hidden id="validationZoneOK">
                    <strong style="margin-right:23px;">Descripción:</strong><input required readonly type="text" class="newseller-field" id="zone_description"><br>
                    <input style="color:white;margin-left:30px;height:50px;transform:translatex(400px);" type="submit" class="menu-button" value="Cargar" name="send">
                </div>

                <div hidden id="validationZoneERROR">
                    <strong style="color:white; background:red; padding:5px;">ERROR. ZONA NO EXISTE.</strong><BR>
                    <strong style="color:white; background:red; padding:5px;">REINGRESE LA ZONA.</strong>

                    <div id="table-zone">
                        <table style="transform:translate(420px,-200px);">
                            <tr>
                                <th>Zona</th>
                                <th>Descripcion</th>
                            </tr>
                            <?php foreach ($Zones as $Zone) { ?>
                                <tr>
                                <td style="cursor:pointer;" id="<?php echo $Zone['ID'];?>" onclick="Completar3(<?php echo $Zone['ID'];?>);" ><?php echo $Zone['ID'];?></td>
                                    <td><?php echo $Zone['description'];?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>                
                <div hidden id="popup-zone">
                      <input style="float:right; padding:10px; background:blue; color:white;cursor:pointer;margin:10px;margin-buttom:0px;border-radius:10px;" type="button" id="close-popup" value="Cerrar"><br><br><br>
                      <table style="margin:10px; heigth:150px;">
                            <tr>
                                <th style="width:150px;">Zona</th>
                                <th style="width:600px;">Descripcion</th>
                            </tr>
                            <?php $Zones=$conexion->query("SELECT * from zones");?>
                            <?php foreach ($Zones as $Zone) { ?>
                                <tr>
                                <td style="cursor:pointer;" id="<?php echo $Zone['ID'];?>" onclick="Completar4(<?php echo $Zone['ID'];?>);" ><?php echo $Zone['ID'];?></td>
                                    <td><?php echo $Zone['description'];?></td>
                                </tr>
                            <?php } ?>
                        </table>
                 </div>
                
            </form>
            
        </div>

        <div>
            <ul class="SecondMenu"> 
                <a href="new-seller.php"><li class="menu2-button" style="background:black;">ALTA</li></a>
                <a href="delete-seller.php"><li class="menu2-button">BAJA</li></a>
                <a href="change-seller.php"><li class="menu2-button">MODIFICAR</li></a>
                <a href="find-sellers.php"><li class="menu2-button" >CONSULTAR</li></a>
                <a href="sellers.php"><li class="menu2-button" >LISTAR</li></a>
            </ul>

        </div>

    </div>

    <script type="text/javascript">
        //Clickeable tabla ERROR PDV
        function Completar(id_fila) {
            $("#pdv_ID").val(id_fila);
            $('#table-pdv').hide();
            $('#validationERROR').hide();
            ValidarPDV();
        }
        //Clickeable popup ERROR PDV
        function Completar2(id_fila) {
            $("#pdv_ID").val(id_fila);
            $('#popup-pdv').hide();
            $('#validationERROR').hide();
            ValidarPDV();
        }
        //Clickeable tabla ERROR ZONE
        function Completar3(id_fila) {
            $("#zone_ID").val(id_fila);
            $('#table-zone').hide();
            $('#validationZoneERROR').hide();
            ValidarZona();
        }
        //Clickeable popup ERROR PDV
        function Completar4(id_fila) {
            $("#zone_ID").val(id_fila);
            $('#popup-zone').hide();
            $('#validationZoneERROR').hide();
            ValidarZona();
        }
    </script>
    
</body>
</html>