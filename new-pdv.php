<?php


require_once "pdo.php";

$validate1=strpos($_SESSION['user.role'],"PDV");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['save'])){
    $Consulta=$conexion->prepare("INSERT INTO pdv (ID,description) VALUES (:ID,:description)");
    $Consulta->bindParam(':ID',$_POST['pdv_ID']);
    $Consulta->bindParam(':description',$_POST['description']);
    $Consulta->execute();
    $movement="NUEVO PUNTO DE VENTA";
    $description="CARGA DEL PUNTO DE VENTA: "."ID: ".$_POST['PDV']." DESCRIPCION: ".$_POST['description'];
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
    $NewMovement->bindParam(':user',$_SESSION['user.name']);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->bindParam(':description',$description);
    $NewMovement->execute();
    header('Location:pdv.php');
}

$PDV=$conexion->query("SELECT * from pdv");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src=”https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js”></script>
    <script type="text/javascript" src="new-pdv.js"></script>
    <title>Alta de PDV - SiGeUsu v3</title>
</head>
<body>

<div class="header">
        <img src="/sigeusu v3/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "menu.php"; ?>
    </div>

    <div class="content">
        <div class="table">
            <form action="new-pdv.php" method="post" id="newseller-form" autocomplete="off"> 
                <h2 style="text-align:center; padding:10px;">Alta de nuevo Punto De Venta</h2>
                <strong style="margin-right:90px;">ID:</strong><input required type="text" class="newseller-field" name="pdv_ID" onblur="ValidarPDV()"><br>
                <strong style="margin-right:10px;">Descripcion:</strong><input required type="text" class="newseller-field" name="description" ><br>

                <div hidden id="validationOK">
                    <input style="color:white;transform:translatex(400px);height:50px;" type="submit" class="menu-button" value="Cargar" name="save">
                </div>

                <div hidden id="validationERROR" style="transform:translatey(60px);">
                    <strong style="color:white; background:red; padding:5px;">ERROR. ID DE PUNTO DE VENTA YA EXISTE.</strong><BR>
                    <strong style="color:white; background:red; padding:5px;">UTILICE UN ID QUE NO ESTE EN USO.</strong>

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
            
        </div>

        <div>
            <ul class="SecondMenu"> 
                <a href="new-pdv.php"><li class="menu2-button" style="background:black;">ALTA</li></a>
                <a href="delete-pdv.php"><li class="menu2-button">BAJA</li></a>
                <a href="change-pdv.php"><li class="menu2-button">MODIFICAR</li></a>
                <a href="find-pdv.php"><li class="menu2-button" >CONSULTAR</li></a>
                <a href="pdv.php"><li class="menu2-button" >LISTAR</li></a>
            </ul>

        </div>

    </div>
    
</body>
</html>