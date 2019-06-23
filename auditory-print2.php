<?php


require_once "pdo.php";

$Consulta=$conexion->prepare($_SESSION['a.imprimir.2']);

$dato=$_SESSION['a.imprimir.date'];
$dato2=$_SESSION['a.imprimir.date2'];
$dato3=$_SESSION['a.imprimir.date3'];

$Consulta->bindParam(':user',$dato);
$Consulta->bindParam(':fechadesde',$dato2);
$Consulta->bindParam(':fechahasta',$dato3);

$Consulta->execute();

$_SESSION['filtre']='false';


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
    <h1 style="padding-top:20px;"> AUDITORIAS - <?php echo $_SESSION['company.name'];?> - SiGeUsu v3  </h2>
        <h2>FECHA: <?php echo date_format(date_create_from_format('Y-m-d', $date), 'd/m/Y');;?></h2>
        <h2>HORA: <?php echo $time;?></h2>
        <div class="table" style="transform:translateX(50px);">
        <table style="width:1150px;">
                    <tr>
                        <th>USUARIO</th>
                        <th>FECHA</th>
                        <th>HORA</th>
                        <th>MOVIMIENTO</th>
                        <th>DETALLES</th>
                    </tr>
                    <?php foreach ($Consulta as $Movement) { ?>
                        <tr>
                            <td><?php echo $Movement['user'];?></td>
                            <td><?php echo $Movement['date'];?></td>
                            <td><?php echo $Movement['time'];?></td>
                            <td><?php echo $Movement['movement'];?></td>
                            <td><?php echo $Movement['description'];?></td>
                        </tr>
                    <?php } ?>

                </table>
        </div>
    </div>
    
</body>
</html>