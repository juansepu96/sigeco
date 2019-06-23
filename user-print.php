<?php


require_once "pdo.php";

$Consulta=$conexion->query($_SESSION['a.imprimir']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>IMPRIMIR LISTADO - SiGeUsu v3</title>
</head>
<body style="background:white;" onload="window.print();window.close();">

    <div class="content" style="display:block;content-align:center;text-align:center;">
    <h1 style="padding-top:20px;"> LISTADO DE USUARIOS - <?php echo $_SESSION['company.name'];?> - SiGeUsu v3  </h2>
        <h2>FECHA: <?php echo date_format(date_create_from_format('Y-m-d', $date), 'd/m/Y');;?></h2>
        <h2>HORA: <?php echo $time;?></h2>
        <div class="table" style="transform:translateX(50px);">
        <table>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Activo</th>
                </tr>
                <?php foreach ($Consulta as $User) { ?>
                    <tr>
                        <td><?php echo $User['name'];?></td>
                        <td><?php echo $User['user'];?></td>
                        <td><?php echo $User['role'];?></td>
                        <td><?php echo $User['active'];?></td>
                    </tr>
                <?php } ?>

            </table>

        </div>
    </div>
    
</body>
</html>
