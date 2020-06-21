<?php

    require_once "pdo.php";

$GetAccount=$conexion->prepare('SELECT * FROM accounts WHERE ID=:id');
$GetAccount->bindParam(':id',$_SESSION['Account']);
$GetAccount->execute();

foreach($GetAccount as $Account){
    $nombre=$Account['name'];
    break;
}

if(isset($_POST['update'])){
    $ID=$_SESSION['Account'];
    $new_name=$_POST['name'];
    $UpdateAccount=$conexion->prepare("UPDATE accounts SET name=:nombre WHERE (ID=:id)");
    $UpdateAccount->bindParam(':id',$ID);
    $UpdateAccount->bindParam(':nombre',$new_name);
    $UpdateAccount->execute();
    header ("Location: plan_cuentas.php");
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Editar plan de cuenta - SiGeCo v 1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "plandecuentas_menu.php"; ?>
    </div>

    <div class="content">

        <div class="table">

            <form method="post" action="Change-Account.php" id="newseller-form" autocomplete="off" style="transform:translateX(150px);">

                <h2 style="text-align:center; padding:10px;">Editar nombre de Cuenta</h2>

                <strong style="margin-right:75px;">Nombre:</strong><input type="text" class="newseller-field" name="name" value="<?php echo $nombre;?>" style="font-size:1em;"> <br><br>

                <input type="submit" value="Actualizar" name="update" class="menu2-button" style="color:white;transform:translatex(500px);">
            </form>

        </div>

    </div>

</body>
</html>