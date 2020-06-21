<?php


require_once "pdo.php";

$editar='false';

$validate1=strpos($_SESSION['user.role'],"USERS");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['open'])){
    $IDNumber=$_POST['id-number'];
    $_SESSION['user.ID.numer']=$IDNumber;
    $EditUser=$conexion->prepare("SELECT * FROM users WHERE ID=:idnumber");
    $EditUser->bindParam(':idnumber',$IDNumber);
    $EditUser->execute();

    foreach ($EditUser as $User){
        $name=$User['name'];
        $username=$User['user'];
        $role=$User['role'];
    }

    $_SESSION['OName']=$name;
    $_SESSION['OUser']=$username;
    $_SESSION['ORole']=$role;

    $editar='true';
}


if(isset($_POST['update'])){
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
    if(!isset($_POST['password']) OR empty($_POST['password'])){
        $Update=$conexion->prepare("UPDATE users SET name=:name, user=:user, role=:role WHERE ID=:id");
        $Update->bindParam(':name',$_POST['name']);
        $Update->bindParam(':user',$_POST['username']);
        $Update->bindParam(':role',$role);
        $Update->bindParam(':id',$_SESSION['user.ID.numer']);
        $Update->execute();     
        $movement="MODIFICAR USUARIO";
        $description="";
        if($_SESSION['OName']!=$_POST['name']){
            $description=$description."SE CAMBIO EL NOMBRE".$_SESSION['OName']." POR ".$_POST['name']."<br>";
        }
        if($_SESSION['OUser']!=$_POST['username']){
            $description=$description."SE CAMBIO EL USUARIO".$_SESSION['OUser']." POR ".$_POST['username']."<br>";
        }
        if($_SESSION['ORole']!=$role){
            $description=$description."SE CAMBIO EL ROL".$_SESSION['ORole']." POR ".$role."<br>";
        }
        $description=$description." <br> "."NO HUBO CAMBIO EN LA CONTRASEÑA";
        $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
        $NewMovement->bindParam(':user',$_SESSION['user.name']);
        $NewMovement->bindParam(':date',$date);
        $NewMovement->bindParam(':time',$time);
        $NewMovement->bindParam(':movement',$movement);
        $NewMovement->bindParam(':description',$description);
        $NewMovement->execute();
    }else{
        $Update=$conexion->prepare("UPDATE users SET name=:name, user=:user, role=:role, password=:password WHERE ID=:id");
        $Update->bindParam(':name',$_POST['name']);
        $Update->bindParam(':user',$_POST['username']);
        $Update->bindParam(':role',$role);
        $Update->bindParam(':password',$_POST['password']);
        $Update->bindParam(':id',$_SESSION['user.ID.numer']);
        $Update->execute();     
        $movement="MODIFICAR USUARIO";
        if($_SESSION['OName']!=$_POST['name']){
            $description=$description."SE CAMBIO EL NOMBRE ".$_SESSION['OName']." POR ".$_POST['name']."<br>";
        }
        if($_SESSION['OUser']!=$_POST['username']){
            $description=$description."SE CAMBIO EL USUARIO ".$_SESSION['OUser']." POR ".$_POST['username']."<br>";
        }
        if($_SESSION['ORole']!=$_POST['role']){
            $description=$description."SE CAMBIO EL ROL ".$_SESSION['ORole']." POR ".$role."<br>";
        }
        $description=$description." <br> "."HUBO CAMBIO EN LA CONTRASEÑA";
        $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
        $NewMovement->bindParam(':user',$_SESSION['user.name']);
        $NewMovement->bindParam(':date',$date);
        $NewMovement->bindParam(':time',$time);
        $NewMovement->bindParam(':movement',$movement);
        $NewMovement->bindParam(':description',$description);
        $NewMovement->execute();

    }

   $_SESSION['user.ID.numer']="";

}

$Consulta=$conexion->query("SELECT * from users");


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
    <script type="text/javascript" src="change-users.js"></script>
    <title>Modificar Usuarios - SiGeCo v1.0</title>
</head>
<body>

<div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "users_menu.php"; ?>
    </div>

    <div class="content">
        <?php if ($editar=='false') { ?>
            <div class="table">
            <table>
                <tr>
                    <th style="width: 35%;">Nombre</th>
                    <th style="width: 30%">Usuario</th>
                    <th style="width: 15%;">Rol</th>
                    <th style="width: 15%;">Activo</th>
                    <th style="width: 40%;">Abrir</th>
                </tr>
                <?php foreach ($Consulta as $User) { ?>
                    <form method="post" action="change-users.php">
                        <tr>
                            <td><?php echo $User['name'];?></td>
                            <td><?php echo $User['user'];?></td>
                            <td><?php echo $User['role'];?></td>
                            <td><?php echo $User['active'];?></td>
                            <td hidden><input type="text"  name="id-number" value="<?php echo $User['ID'];?>"></td>
                            <td style="background:green;cursor:pointer;"><input readonly style="cursor:pointer;background:green;color:white;text-align:center;" type="submit" value="ABRIR" name="open"></td>
                        </tr>
                     </form>
                <?php } ?>

            </table>

        </div>
        <?php } ?>
        
        <?php if($editar=='true') { ?>
            <form action="change-users.php" method="post" id="newseller-form">
                <h2 style="text-align:center; padding:10px;">Modificar datos del Usuario</h2>
                <input hidden type="text" class="newseller-field"  value="<?php echo $username;?>" name="original_username" >
                <strong style="margin-right:90px;">Nombre:</strong><input required type="text" class="newseller-field" name="name" value="<?php echo $name;?>"><br>
                <strong style="margin-right:55px;">Usuario:</strong><input required type="text" class="newseller-field" name="username" value="<?php echo $username;?>" onblur="ValidarUsername()"><br>
                <strong style="margin-right:5px;">Contraseña:</strong><input type="password" class="newseller-field" name="password" style="width:177px;" placeholder="Complete solo si quiere cambiar la contraseña"><br>
                <strong style="margin-right:80px;">Rol:</strong>
                    
                        <?php if (is_int(strpos($role,"SUPERADMINISTRADOR"))){ ?>
                            <input checked type="checkbox" name="SUPERADMINISTRADOR" value="1">SUPERADMINISTRADOR<br>
                        <?php }else{ ?>
                            <input type="checkbox" name="SUPERADMINISTRADOR" value="1">SUPERADMINISTRADOR<br>
                        <?php } ?>

                        <?php if (is_int(strpos($role,"AUDITOR"))){ ?>
                            <input checked style="margin-left:160px;" type="checkbox" name="AUDITOR" value="1">AUDITOR<br>
                        <?php }else{ ?>
                            <input style="margin-left:160px;" type="checkbox" name="AUDITOR" value="1">AUDITOR<br>
                        <?php } ?>

                        <?php if (is_int(strpos($role,"VENDEDORES"))){ ?>
                            <input checked style="margin-left:160px;" type="checkbox" name="VENDEDORES" value="1">ADMIN. VENDEDORES<br>
                        <?php }else{ ?>
                            <input  style="margin-left:160px;" type="checkbox" name="VENDEDORES" value="1">ADMIN. VENDEDORES<br>
                        <?php } ?>

                        <?php if (is_int(strpos($role,"PDV"))){ ?>
                            <input checked style="margin-left:160px;" type="checkbox" name="PDV" value="1">ADMIN. PUNTOS DE VENTA<br>
                        <?php }else{ ?>
                            <input  style="margin-left:160px;" type="checkbox" name="PDV" value="1">ADMIN. PUNTOS DE VENTA<br>
                        <?php } ?>

                        <?php if (is_int(strpos($role,"USERS"))){ ?>
                            <input checked style="margin-left:160px;margin-buttom:20px;" type="checkbox" name="USERS" value="1">USERS<br><br>
                        <?php }else{ ?>
                            <input  style="margin-left:160px;margin-buttom:20px;" type="checkbox" name="USERS" value="1">USERS<br><br>
                        <?php } ?>                    
                
                <div id="validationOK">
                    <input style="margin-left:-50px; color:white; width:200px;margin-top:50px;height:50px;" type="submit" class="menu-button" value="ACTUALIZAR" name="update">
                </div>

                <div hidden id="validationERROR">
                    <strong style="color:white; background:red; padding:5px;">ERROR. NOMBRE DE USUARIO EN USO.</strong><BR>
                    <strong style="color:white; background:red; padding:5px;">ELIJA UNO DISTINTO.</strong>
                </div>          

             </form>
        <?php } ?>


    </div>
    
</body>
</html>