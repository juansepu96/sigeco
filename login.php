<?php

require_once "pdo.php";


if(isset($_POST['login'])){
    $user=$_POST['user'];
    $password=$_POST['password'];
    $Consulta=$conexion->query('SELECT * FROM users');	

    foreach ($Consulta as $Usuario) {
        if($Usuario['user']===$user and $Usuario['password']===$password and $Usuario['active']==="SI"){
            $_SESSION['user.name']=$Usuario['name'];
            $_SESSION['user.login']='true';
            $_SESSION['user.role']=$Usuario['role'];
            $movement="HA INICIADO SESION.";
            $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement) VALUES (:user,:date,:time,:movement)");
            $NewMovement->bindParam(':user',$_SESSION['user.name']);
            $NewMovement->bindParam(':date',$date);
            $NewMovement->bindParam(':time',$time);
            $NewMovement->bindParam(':movement',$movement);
            $NewMovement->execute();
            header('Location: index2.php');
            break;
        }else{
            $mensaje="ERROR AL INICIAR SESION. <br> Compruebe que su usuario y contraseña sean correctos";
        }
    }
}

if(isset($_POST['backup'])){
    header ('Location: backups.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Inicio de Sesion - SiGeUsu v3</title>
    <style>
        #auth{
            width:320px;
            height:120px;
            background:black;
            z-index:2;
            padding:10px;
            transform:translate(200px,-250px);
            color:white;
        }

    </style>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src=”https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js”></script>
    <script type="text/javascript" src="backup.js"></script>
</head>
<body>
    <div class="form">
        <img src="/sigeusu v3/logos/<?php echo $_SESSION['company.logo'];?>" id="logo">
        <form method="post" action="login.php" autocomplete="off">

            <h1 class="form-title">¡ Bienvenido a SiGeUsu v3.0 !</h1>

            <h1 class="form-title">Debe iniciar sesion para ingresar al sistema.</h1>

            <input required class="form-input" type="text" placeholder="Usuario:" name="user">

            <input required class="form-input" type="password" placeholder="Contraseña:" name="password">

            <input class="form-submit" type="submit" value="Entrar" name="login">

            <?php if(isset($mensaje)) { ?>
                <h3 style="margin-top:10px;color:white;font-size:1em;padding:1px;background: rgba(255, 0, 0, 0.5);text-align:center;"> <?php echo $mensaje;?> </h3>
            <?php } ?>

            <a href="index.php"><p class="back-text">VOLVER A EMPRESAS</p></a>

            <a onclick="Backup()"><p class="back-text2" style="cursor:pointer;text-align:center;">COPIAS DE SEGURIDAD</p></a>

            </form>

            <div hidden id="auth">
            <input style="float:right; padding:10px; background:blue; color:white;cursor:pointer;margin:10px;margin-buttom:0px;border-radius:10px;" type="button" id="close-popup" value="Cerrar">

                <form method="post" action="login.php" autocomplete="off">

                <h3>Ingrese su clave única para acceder.</h3>

                <input type="text" class="newseller-field" style="margin-top:15px;padding:15px;" name="backup_ID" onblur="ValidateBackup()"><br>

                <input hidden class="form-submit" type="submit" id="submit-backup" style="width:60px;height:50px;font-size:0.8em;transform:translate(220px,-60px);" value="Entrar" name="backup">

                </form>

            </div>

        </form>
    </div>
</body>
</html>