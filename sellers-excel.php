<?php 

require_once "pdo.php";

$Consulta=$conexion->query($_SESSION['a.imprimir']); 

header("Pragma: public");
header("Expires: 0");
$filename = "sigeusuv3-Sellers Export-.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EXCEL LISTADO - SiGeUsu v3</title>
</head>
<body style="background:white;" onload="window.print();window.close();">

    <div class="content" style="display:block;content-align:center;text-align:center;">
    <h1 style="padding-top:20px;"> LISTADO DE VENDEDORES - <?php echo $_SESSION['company.name'];?> - SiGeUsu v3  </h2>
        <h2>FECHA: <?php echo date_format(date_create_from_format('Y-m-d', $date), 'd/m/Y');;?></h2>
        <h2>HORA: <?php echo $time;?></h2>
        <div class="table" style="transform:translateX(200px);">
            <table>
                <tr>
                    <th style="width: 10%;">DNI</th>
                    <th style="width: 25%;">Nombre</th>
                    <th>F. de Nacimiento</th>
                    <th style="width: 15%;">Telefono</th>
                    <th style="width: 30%;">E-mail</th>
                    <th>P. de Venta</th>
                    <th>Descripcion</th>
                    <th>Zona</th>
                    <th>Descripcion</th>
                </tr>
                <?php foreach ($Consulta as $Vendedor) { ?>
                    <tr>
                        <td><?php echo $Vendedor['DNI'];?></td>
                        <td><?php echo $Vendedor['name'];?></td>
                        <td><?php echo date_format(date_create_from_format('Y-m-d', $Vendedor['birthdate']), 'd/m/Y');?></td>
                        <td><?php echo $Vendedor['phone'];?></td>
                        <td><?php echo $Vendedor['email'];?></td>
                        <td><?php echo $Vendedor['pdv_ID'];?></td>
                        <?php 
                            $PDVDescription=$conexion->prepare("SELECT * FROM pdv WHERE ID=:id");
                            $PDVDescription->bindParam(':id',$Vendedor['pdv_ID']);
                            $PDVDescription->execute();
                            foreach ($PDVDescription as $Row){
                                $description=$Row['description'];
                            }
                        ?>
                        <td><?php echo $description;?></td>
                        <td><?php echo $Vendedor['zone_ID'];?></td>
                        <?php 
                            $ZoneDescription=$conexion->prepare("SELECT * FROM zones WHERE ID=:id");
                            $ZoneDescription->bindParam(':id',$Vendedor['zone_ID']);
                            $ZoneDescription->execute();
                            foreach ($ZoneDescription as $Row){
                                $descriptionzone=$Row['description'];
                            }
                        ?>
                        <td><?php echo $descriptionzone;?></td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>
    
</body>
</html>