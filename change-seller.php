<?php


require_once "pdo.php";

$editar='false';

$validate1=strpos($_SESSION['user.role'],"VENDEDORES");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    }else{
        $sepuede='false';
        header('Location: index2.php');
	}

if(isset($_POST['open'])){
    $IDNumber=$_POST['id-number'];
    $_SESSION['seller.ID.numer']=$IDNumber;
    $EditSeller=$conexion->prepare("SELECT * FROM sellers WHERE ID=:idnumber");
    $EditSeller->bindParam(':idnumber',$IDNumber);
    $EditSeller->execute();

    foreach ($EditSeller as $Seller){
        $dni=$Seller['DNI'];
        $name=$Seller['name'];
        $birthdate=$Seller['birthdate'];
        $phone=$Seller['phone'];
        $email=$Seller['email'];
        $pdv_ID=$Seller['pdv_ID'];
        $seller_ID=$Seller['ID'];
        $zone_ID=$Seller['zone_ID'];
    }

    $_SESSION['Odni']=$dni;
    $_SESSION['Oname']=$name;
    $_SESSION['Obirthdate']=$birthdate;
    $_SESSION['Ophone']=$phone;
    $_SESSION['Oemail']=$email;
    $_SESSION['Opdv_ID']=$pdv_ID;
    $_SESSION['Ozone_ID']=$zone_ID;



    $DescripcionPV=$conexion->prepare("SELECT * FROM pdv WHERE ID=:pdv_id");
    $DescripcionPV->bindParam(':pdv_id',$pdv_ID);
    $DescripcionPV->execute();

    foreach ($DescripcionPV as $PV){
        $description=$PV['description'];
    }

    $DescripcionZona=$conexion->prepare("SELECT * FROM zones WHERE ID=:zone_ID");
    $DescripcionZona->bindParam(':zone_ID',$zone_ID);
    $DescripcionZona->execute();

    foreach ($DescripcionZona as $Zone){
        $zonedescription=$Zone['description'];
    }

    $editar='true';
}


if(isset($_POST['refresh'])){
    $Update=$conexion->prepare("UPDATE sellers SET DNI=:dni, name=:name, birthdate=:birthdate, phone=:phone, email=:email, pdv_ID=:pdv_ID, zone_ID=:zone_ID WHERE ID=:id");
    $Update->bindParam(':dni',$_POST['dni']);
    $Update->bindParam(':name',$_POST['name']);
    $Update->bindParam(':birthdate',$_POST['birthdate']);
    $Update->bindParam(':phone',$_POST['phone']);
    $Update->bindParam(':email',$_POST['email']);
    $Update->bindParam(':pdv_ID',$_POST['pdv_ID']);
    $Update->bindParam(':zone_ID',$_POST['zone_ID']);
    $Update->bindParam(':id',$_SESSION['seller.ID.numer']);
    $Update->execute();
    $movement="MODIFICAR VENDEDOR";
    $description="";
    if($_SESSION['Odni']!=$_POST['dni']){
        $description=$description."SE CAMBIO EL DNI ".$_SESSION['Odni']." POR ".$_POST['dni']."<br>";
    }
    if($_SESSION['Oname']!=$_POST['name']){
        $description=$description."SE CAMBIO EL NOMBRE ".$_SESSION['Oname']." POR ".$_POST['name']."<br>";
    }
    if($_SESSION['Obirthdate']!=$_POST['birthdate']){
        $description=$description."SE CAMBIO LA FECHA DE NACIMIENTO ".$_SESSION['Obirthdate']." POR ".$_POST['birthdate']."<br>";
    }
    if($_SESSION['Ophone']!=$_POST['phone']){
        $description=$description."SE CAMBIO EL TELEFONO ".$_SESSION['Ophone']." POR ".$_POST['phone']."<br>";
    }
    if($_SESSION['Oemail']!=$_POST['email']){
        $description=$description."SE CAMBIO EL MAIL ".$_SESSION['Oemail']." POR ".$_POST['email']."<br>";
    }
    if($_SESSION['Opdv_ID']!=$_POST['pdv_ID']){
        $description=$description."SE CAMBIO EL PDV ".$_SESSION['Opdv_ID']." POR ".$_POST['pdv_ID']."<br>";
    }
    if($_SESSION['Ozone_ID']!=$_POST['zone_ID']){
        $description=$description."SE CAMBIO LA ZONA ".$_SESSION['Ozone_ID']." POR ".$_POST['zone_ID']."<br>";
    }
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
    $NewMovement->bindParam(':user',$_SESSION['user.name']);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->bindParam(':description',$description);
    $NewMovement->execute();
  		 $_SESSION['seller.ID.numer']="";

}

$Consulta=$conexion->query("SELECT * from sellers");
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
    <title>Modificar vendedores - SiGeCo v1.0</title>
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
        <?php if ($editar=='false') { ?>
            <div class="table">
                <table>
                    <tr>
                        <th style="width: 10%;">DNI</th>
                        <th style="width: 25%;">Nombre</th>
                        <th>F. de Nacimiento</th>
                        <th style="width: 15%;">Telefono</th>
                        <th style="width: 30%;">E-mail</th>
                        <th>SELECCIONAR</th>
                    </tr>
                    <?php foreach ($Consulta as $Vendedor) { ?>
                        <form method="post" action="change-seller.php">
                            <tr>
                                <td><?php echo $Vendedor['DNI'];?></td>
                                <td><?php echo $Vendedor['name'];?></td>
                                <td><?php echo date_format(date_create_from_format('Y-m-d', $Vendedor['birthdate']), 'd/m/Y');?></td>
                                <td><?php echo $Vendedor['phone'];?></td>
                                <td><?php echo $Vendedor['email'];?></td>
                                <td hidden><input type="text"  name="id-number" value="<?php echo $Vendedor['ID'];?>"></td>
                                <td style="background:green;cursor:pointer;"><input style="cursor:pointer;color:white; background:green;" type="submit" value="ABRIR" name="open"></td>
                            </tr>
                        </form>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>
        
        <?php if($editar=='true') { ?>
            <form action="change-seller.php" method="post" id="newseller-form">
                <h2 style="text-align:center; padding:10px;">Modificar datos de vendedor</h2>
                <strong style="margin-right:90px;">DNI:</strong><input required type="number" class="newseller-field" name="dni" value="<?php echo $dni;?>"><br>
                <strong style="margin-right:55px;">Nombre:</strong><input required type="text" class="newseller-field" name="name" value="<?php echo $name;?>"><br>
                <strong style="margin-right:5px;">F. Nacimiento:</strong><input required type="date" class="newseller-field" name="birthdate" style="width:177px;" value="<?php echo $birthdate;?>"><br>
                <strong style="margin-right:53px;">Telefono:</strong><input required type="number" class="newseller-field" name="phone" value="<?php echo $phone;?>"><br>
                <strong style="margin-right:80px;">email:</strong><input required type="email" class="newseller-field" name="email" value="<?php echo $email;?>"><br>
                <strong style="margin-right:30px;">P. de Venta:</strong><input required type="text" class="newseller-field" id="pdv_ID" name="pdv_ID" value="<?php echo $pdv_ID;?>"  onblur="ValidarPDV()" onfocus="PDVHELP()"><br>

                <div id="validationOK">
                    <strong style="margin-right:23px;">Descripción:</strong><input required type="text" class="newseller-field" id="description" value="<?php echo $description;?>"><br>
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
                                <th style="width:150px;">PV</th>
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

                 <strong style="margin-right:70px;">ZONA:</strong><input required type="text" class="newseller-field" name="zone_ID" id="zone_ID" value="<?php echo $zone_ID;?>"  onblur="ValidarZona()" onfocus="ZONEHELP()"><br>
                 
                 <div id="validationZoneOK">
                    <strong style="margin-right:23px;">Descripción:</strong><input required type="text" class="newseller-field" id="zone_description" value="<?php echo $zonedescription;?>"><br>
                    <input style="color:white;height:50px;transform:translatex(400px);" type="submit" class="menu-button" value="Actualizar" name="refresh">
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
                                <th style="width:50px;">Zona</th>
                                <th style="width:600px; content-align='center';">Descripcion</th>
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

        <?php } ?>

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