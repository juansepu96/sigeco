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

if(isset($_POST['disable'])){
    $active='NO';
    $IDNumber=$_POST['id-number'];
    $GetUser=$conexion->prepare("SELECT * FROM users WHERE ID=:idnumber");
    $GetUser->bindParam(':idnumber',$IDNumber);
    $GetUser->execute();
    foreach ($GetUser as $User){
        $Name=$User['name'];
        $UserName=$User['user'];
    }
    $DisableUser=$conexion->prepare("UPDATE users SET active=:active WHERE ID=:idnumber");
    $DisableUser->bindParam(':idnumber',$IDNumber);
    $DisableUser->bindParam(':active',$active);
    $DisableUser->execute();
    $movement="DESACTIVAR USUARIOS";
    $description="DESACTIVAR USUARIO: NOMBRE: ".$Name." USUARIO: ".$UserName;
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
    $NewMovement->bindParam(':user',$_SESSION['user.name']);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->bindParam(':description',$description);
    $NewMovement->execute();
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
    <title>Eliminar vendedores - SiGeCo v1.0</title>
</head>
<body>

<div class="header">
        <img src="/sigeusu v3/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "users_menu.php"; ?>
    </div>

    <div class="content">
        <div class="table">
            <table>
                <tr>
                    <th style="width: 35%;">Nombre</th>
                    <th style="width: 30%">Usuario</th>
                    <th style="width: 15%;">Rol</th>
                    <th style="width: 15%;">Activo</th>
                    <th style="width: 40%;">DESACTIVAR</th>
                </tr>
                <?php foreach ($Consulta as $User) { ?>
                    <form method="post" action="delete-users.php" autocomplete="off">
                        <tr> 
                            <td><?php echo $User['name'];?></td>
                            <td><?php echo $User['user'];?></td>
                            <td><?php echo $User['role'];?></td>
                            <td><?php echo $User['active'];?></td>
                            <td hidden><input type="text"  name="id-number" value="<?php echo $User['ID'];?>"></td>
                            <td style="background:red;cursor:pointer;"><input readonly onclick="if(confirm('DESACTIVARA ESTE USUARIO, DESEA CONTINUAR?')){
                                this.form.submit();}
                                else{ alert('Operacion Cancelada');}" style="cursor:pointer;background:red;color:white;text-align:center;" value="DESACTIVAR" name="disable"></td>
                        </tr>
                     </form>
                <?php } ?>

            </table>

        </div>

    </div>
    
</body>
</html>