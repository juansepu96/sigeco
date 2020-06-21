<?php


require_once "pdo.php";

$Consulta=$conexion->query($_SESSION['a.imprimir']);

header("Pragma: public");
header("Expires: 0");
$filename = "SiGeCo v1.0 - PDV Export-.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EXPORTAR LISTADO - SiGeCo v1.0</title>
</head>
<body style="background:white;" onload="window.print();window.close();">

    <div class="content" style="display:block;content-align:center;text-align:center;">
    <h1 style="padding-top:20px;"> LISTADO DE PDV - <?php echo $_SESSION['company.name'];?> - SiGeCo v1.0  </h2>
        <h2>FECHA: <?php echo date_format(date_create_from_format('Y-m-d', $date), 'd/m/Y');;?></h2>
        <h2>HORA: <?php echo $time;?></h2>
        <div class="table" style="transform:translateX(200px);">
        <table>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th>Descripcion</th>
                </tr>
                <?php foreach ($Consulta as $PV) { ?>
                    <tr>
                        <td style="width:100px;"><?php echo $PV['ID'];?></td>
                        <td><?php echo $PV['description'];?></td>
                    </tr>
                <?php } ?>

            </table>

        </div>
    </div>
    
</body>
</html>
