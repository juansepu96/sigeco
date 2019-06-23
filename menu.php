<?php


$validate1=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");
$validate2=strpos($_SESSION['user.role'],"USERS");
$validate3=strpos($_SESSION['user.role'],"AUDITOR");
$validate4=strpos($_SESSION['user.role'],"VENDEDORES");
$validate5=strpos($_SESSION['user.role'],"PDV");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>MainMenu - SiGeUsu v3</title>
</head>
<body>
        <ul class="MainMenu"> 
            <?php if (is_int($validate1)) { ?>
                <a href="change-company.php"><li class="menu-button" >Mi Empresa</li></a>
                <a href="sellers.php"><li class="menu-button" >Vendedores</li></a>
                <a href="pdv.php"><li class="menu-button" >Puntos de Venta</li></a>
                <a href="users.php"><li class="menu-button">Usuarios</li></a>
                <a href="auditory.php"><li class="menu-button">Auditorias</li></a>
            <?php }else{ ?>
                <?php if (is_int($validate2)) { ?>
                    <a href="users.php"><li class="menu-button">Usuarios</li></a>
                <?php } ?>
                <?php if (is_int($validate3)) { ?>
                    <a href="auditory.php"><li class="menu-button">Auditorias</li></a>
                <?php } ?>
                <?php if (is_int($validate4)) { ?>
                    <a href="sellers.php"><li class="menu-button" >Vendedores</li></a>
                <?php } ?>
                <?php if (is_int($validate5)) { ?>
                    <a href="pdv.php"><li class="menu-button">Puntos de Venta</li></a>
                <?php } ?>

            <?php } ?>
            
            <a href="checkout.php"><li class="menu-button">Cerrar Sesion</li></a>
        </ul>
</body>
</html>