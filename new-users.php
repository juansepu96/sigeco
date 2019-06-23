<?php


require_once "pdo.php";

$validate1=strpos($_SESSION['user.role'],"USERS");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
     $sepuede='true';
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['send'])){
    $active='SI';
    $role="";
    if(isset($_POST['SUPERADMINISTRADOR'])){
        $role=$role."SUPERADMINISTRADOR - ";
    }
    if(isset($_POST['AUDITOR'])){
        $role=$role."AUDITOR - ";
    }
    if(isset($_POST['VENDEDORES'])){
        $role=$role."VENDEDORES - ";
    }
    if(isset($_POST['PDV'])){
        $role=$role."PDV - ";
    }
    if(isset($_POST['USERS'])){
        $role=$role."USERS - ";
    }

    $Consulta=$conexion->prepare("INSERT INTO users (name,active,password,role,user) VALUES (:name,:active,:password,:role,:user)");
    $Consulta->bindParam(':name',$_POST['name']);
    $Consulta->bindParam(':active',$active);
    $Consulta->bindParam(':password',$_POST['password']);
    $Consulta->bindParam(':role',$role);
    $Consulta->bindParam(':user',$_POST['username']);
    $Consulta->execute();
    $movement="NUEVO USUARIO";
    $description="CARGA DEL USUARIO: ".$_POST['name'];
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
    $NewMovement->bindParam(':user',$_SESSION['user.name']);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->bindParam(':description',$description);
    $NewMovement->execute();
    header('Location:users.php');
}

$PDV=$conexion->query("SELECT * from pdv");
$Zones=$conexion->query("SELECT * from zones");



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
    <script type="text/javascript" src="new-users.js"></script>
    <title>Dar de alta Usuarios - SiGeUsu v3</title>
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
            <form action="new-users.php" method="post" id="newseller-form" autocomplete="off">
                <h2 style="text-align:center; padding:10px;">Alta de un nuevo usuario</h2>
                <strong style="margin-right:55px;">Nombre:</strong><input required type="text" class="newseller-field" name="name"><br>
                <strong style="margin-right:5px;">Nombre de Usuario:</strong><input required type="text" class="newseller-field" name="username" style="width:177px;" onblur="ValidarUsername()"><br>
                <strong style="margin-right:53px;">Contraseña:</strong><input required type="password" class="newseller-field" name="password"><br>
                <strong style="margin-right:80px;">Permisos:</strong>
                        <input type="checkbox" name="SUPERADMINISTRADOR" value="1">SUPERADMINISTRADOR<br>
                        <input  style="margin-left:212px;" type="checkbox" name="AUDITOR" value="1">AUDITOR<br>
                        <input  style="margin-left:212px;" type="checkbox" name="VENDEDORES" value="1">ADMIN. VENDEDORES<br>
                        <input  style="margin-left:212px;" type="checkbox" name="PDV" value="1">ADMIN. PUNTOS DE VENTA<br>
                        <input  style="margin-left:212px;" type="checkbox" name="USERS" value="1">USERS<br>
                <div hidden id="validationOK">                    
                     <input style="color:white;transform:translate(500px,-10px);height:50px;" type="submit" class="menu-button" value="Cargar" name="send">
                </div>

                <div hidden id="validationERROR">
                    <strong style="color:white; background:red; padding:5px;">ERROR. USUARIO YA EXISTE.</strong><BR>
                    <strong style="color:white; background:red; padding:5px;">INTENTE CON OTRO.</strong>
                </div>
                
            </form>
            
        </div>

        <div>
            <ul class="SecondMenu"> 
                <a href="new-users.php"><li class="menu2-button" style="background:black;">ALTA</li></a>
                <a href="delete-users.php"><li class="menu2-button">BAJA</li></a>
                <a href="change-users.php"><li class="menu2-button">MODIFICAR</li></a>
                <a href="find-users.php"><li class="menu2-button" >CONSULTAR</li></a>
                <a href="users.php"><li class="menu2-button" >LISTAR</li></a>
            </ul>

        </div>

    </div>
    
</body>
</html>