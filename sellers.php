<?php


require_once "pdo.php";
$validate1=strpos($_SESSION['user.role'],"VENDEDORES");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    $movement="LISTAR VENDEDORES";
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement) VALUES (:user,:date,:time,:movement)");
    $NewMovement->bindParam(':user',$_SESSION['user.name']);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->execute();
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['print'])){
    $_SESSION['a.imprimir']="SELECT * FROM sellers";
    echo "<script>window.open('sellers-print.php', '_blank');</script>";
}

if(isset($_POST['pdf'])){
    $_SESSION['a.imprimir']="SELECT * FROM sellers";
    echo "<script>window.open('sellers-pdf.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    $_SESSION['a.imprimir']="SELECT * FROM sellers";
    echo "<script>window.open('sellers-excel.php');</script>";
}


$Consulta=$conexion->query("SELECT * from sellers");


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Listar vendedores - SiGeCo v1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "sellers_menu.php"; ?>
    </div>

    <div class="content">
        <div class="table">
            <form method="post" action="sellers.php" style="padding:5px;display:inline-block;" autocomplete="off">
                <input type="submit" class="action-button"  value="IMPRIMIR" name="print">
                <input type="submit" class="action-button" value="EXPORTAR A PDF" name="pdf">
                <input type="submit" class="action-button" value="EXPORTAR A EXCEL" name="excel">
            </form>
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