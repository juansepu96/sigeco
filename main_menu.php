<?php


$validate1=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");
$validate2=strpos($_SESSION['user.role'],"USERS");
$validate3=strpos($_SESSION['user.role'],"AUDITOR");
$validate4=strpos($_SESSION['user.role'],"VENDEDORES");
$validate5=strpos($_SESSION['user.role'],"PDV");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>MainMenu - SiGeUsu v3</title>
    <style>
        *{ 
            overflow-y:hidden;
            overflow-x:hidden;
        }
    </style>
</head>
<body>
        <div class="MainMenu" style="display:block;padding-top:5px;">
            <?php if (is_int($validate1)) { ?>
                <div style="display:inline-flex;margin-left:200px;">
                    <a href="change-company.php"><img src="img/company.png"><h4>Mi Empresa</h4></a>
                    <a href="sellers.php"><img src="img/sellers.png"><h4>Vendedores</h4></a>
                    <a href="pdv.php"><img src="img/pdv.png"><h4>Puntos de Venta</h4></a>
                    <a href="contabilidad.php"><img src="img/contabilidad.png"><h4>Contabilidad</h4></a>
                </div>
                <div style="display:inline-flex;margin-left:210px;padding-top:10px;">
                    <a href="facturacion.php"><img src="img/facturacion.png"><h4>Facturacion</h4></a>
                    <a href="users.php"><img src="img/users.png"><h4>Usuarios</h4></a>
                    <a href="auditory.php"><img src="img/auditory.png"><h4>Auditorias</h4></a>
                    <a href="checkout.php"><img src="img/checkout.png"><h4>Cerrar Sesión</h4></a>
                </div>
            <?php }else{ ?>
                <?php if (is_int($validate2)) { ?>
                    <a href="users.php"><img src="img/users.png"><h4>Usuarios</h4></a>
                <?php } ?>
                <?php if (is_int($validate3)) { ?>
                    <a href="auditory.php"><img src="img/auditory.png"><h4>Auditorias</h4></a>
                <?php } ?>
                <?php if (is_int($validate4)) { ?>
                    <a href="sellers.php"><img src="img/sellers.png"><h4>Vendedores</h4></a>
                <?php } ?>
                <?php if (is_int($validate5)) { ?>
                    <a href="pdv.php"><img src="img/pdv.png"><h4>Puntos de Venta</h4></a>
                <?php } ?>

                <a href="checkout.php"><img src="img/checkout.png"><h4>Cerrar Sesión</h4></a>

            <?php } ?>
            
        </div>
</body>
</html>