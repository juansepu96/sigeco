<?php


require_once "pdo.php";
$validate1=strpos($_SESSION['user.role'],"PDV");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $movement="LISTAR PUNTOS DE VENTA";
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
    $_SESSION['a.imprimir']="SELECT * from pdv";
    echo "<script>window.open('pdv-print.php', '_blank');</script>";

}
if(isset($_POST['pdf'])){
    $_SESSION['a.imprimir']="SELECT * from pdv";
    echo "<script>window.open('pdv-pdf.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    $_SESSION['a.imprimir']="SELECT * from pdv";
    echo "<script>window.open('pdv-excel.php', '_blank');</script>";
}



$Consulta=$conexion->query("SELECT * from pdv");


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Listar Punto de Ventas - SiGeCo v1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "pdv_menu.php"; ?>
    </div>

    <div class="content">
        <div class="table">
        <form method="post" action="pdv.php" style="padding:5px;display:inline-block;">
                <input type="submit" class="action-button" style="margin-left:150px;" value="IMPRIMIR" name="print">
                <input type="submit" class="action-button" value="EXPORTAR A PDF" name="pdf">
                <input type="submit" class="action-button" value="EXPORTAR A EXCEL" name="excel">
        </form>

            <table>
                <tr>
                    <th style="width: 200px">ID</th>
                    <th style="width: 1300px">Descripcion</th>
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