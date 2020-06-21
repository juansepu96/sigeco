<?php


require_once "pdo.php";

$busqueda='false';
$validate1=strpos($_SESSION['user.role'],"PDV");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['search'])){
    $busqueda='true';
    $field=$_POST['search-list'];
    $text=$_POST['search-text'];
    $text="%".$text."%";
    switch ($field){
        case "ID":
            $Consulta=$conexion->prepare("SELECT * FROM pdv WHERE ID LIKE :texto");  
            $_SESSION['a.imprimir.consulta']="SELECT * FROM pdv WHERE ID LIKE :texto";
            $description="ID: ".$text;
            break;
        case "description":
            $Consulta=$conexion->prepare("SELECT * FROM pdv WHERE description LIKE :texto"); 
            $_SESSION['a.imprimir.consulta']="SELECT * FROM pdv WHERE description LIKE :texto"; 
            $description="Descripcion: ".$text;
            break;
    }
    $_SESSION['a.imprmir.dato']=$text;
    $Consulta->bindParam(':texto',$text);
    $Consulta->execute();
    $description=str_replace("%","",$description);
    $movement="CONSULTAR PUNTOS DE VENTA ";
    $description="PARAMETROS DE LA BUSQUEDA: ".$description;
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
    $NewMovement->bindParam(':user',$_SESSION['user.name']);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->bindParam(':description',$description);
    $NewMovement->execute();
}

if(isset($_POST['print'])){
    $_SESSION['a.imprimir']="SELECT * from pdv";
    echo "<script>window.open('pdv-print2.php', '_blank');</script>";
}

if(isset($_POST['pdf'])){
    $_SESSION['a.imprimir']="SELECT * from pdv";
    echo "<script>window.open('pdv-pdf2.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    $_SESSION['a.imprimir']="SELECT * from pdv";
    echo "<script>window.open('pdv-excel2.php', '_blank');</script>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Buscar Puntos de Venta - SiGeCo v1.0</title>
</head>
<body>
  <?php if ($sepuede=='true') { ?>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "pdv_menu.php"; ?>
    </div>

    <div class="content">
        <div class="table">
            <h2 style="text-align:center; padding:10px;" >Seleccione un campo para realizar la busqueda.</h2>
            <form action="find-pdv.php" method="post" class="search-form">
                <select name="search-list" class="list" autocomplete="off">
                    <option value='ID'>ID</option>
                    <option value='description'>Descripcion</option>
                </select>
                <input type="text" id="search-text" name="search-text" placeholder="Ingrese un valor">
                <input type="submit" class="menu2-button" style="color:white; font-weight:bold; width:100px;" value="Buscar" name="search">
            </form>

            <?php if ($busqueda=='true') { ?>
                <form method="post" action="find-pdv.php" style="padding:5px;display:inline-block;">
                <input type="submit" style="margin-left:150px; padding:10px; color:white; background:blue; border-radius:10px;cursor:pointer;font-width:bold;" value="IMPRIMIR" name="print">
                <input type="submit" class="action-button" value="EXPORTAR A PDF" name="pdf">
                <input type="submit" class="action-button" value="EXPORTAR A EXCEL" name="excel">
                </form>
                <h2 style="text-align:center; padding:10px;" >Resultado de la busqueda.</h2>
                <table>
                    <tr>
                        <th style="width: 150px;">ID</th>
                        <th style="width: 720px;">Descripcion</th>
                    </tr>
                    <?php foreach ($Consulta as $PDV) { ?>
                            <tr>
                                <td><?php echo $PDV['ID'];?></td>
                                <td><?php echo $PDV['description'];?></td>
                            </tr>
                    <?php } ?>

                </table>

            <?php } ?>

        </div>

    </div>

    <?php } ?>
    
</body>
</html>