<?php


require_once "pdo.php";

$busqueda='false';

$validate1=strpos($_SESSION['user.role'],"VENDEDORES");
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
        case "DNI":
            $Consulta=$conexion->prepare("SELECT * FROM sellers WHERE DNI LIKE :texto");  
            $description="DNI: ".$text;
            $_SESSION['a.imprimir.consulta']="SELECT * FROM sellers WHERE DNI LIKE :texto";
            break;
        case "name":
            $Consulta=$conexion->prepare("SELECT * FROM sellers WHERE name LIKE :texto");  
            $description="Nombre: ".$text;
            $_SESSION['a.imprimir.consulta']="SELECT * FROM sellers WHERE name LIKE :texto";
            break;
        case "phone":
            $Consulta=$conexion->prepare("SELECT * FROM sellers WHERE phone LIKE :texto"); 
            $description="Telefono: ".$text; 
            $_SESSION['a.imprimir.consulta']="SELECT * FROM sellers WHERE phone LIKE :texto";
            break;
        case "email":
            $Consulta=$conexion->prepare("SELECT * FROM sellers WHERE email LIKE :texto");  
            $description="Email: ".$text;
            $_SESSION['a.imprimir.consulta']="SELECT * FROM sellers WHERE email LIKE :texto";
            break;
        case "pdv_ID":
            $Consulta=$conexion->prepare("SELECT * FROM sellers WHERE pdv_ID LIKE :texto"); 
            $description="Punto de Venta: ".$text; 
            $_SESSION['a.imprimir.consulta']="SELECT * FROM sellers WHERE pdv_ID LIKE :texto";
            break;
    }
    $_SESSION['a.imprmir.dato']=$text;
    $Consulta->bindParam(':texto',$text);
    $Consulta->execute();
    $description=str_replace("%","",$description);
    $movement="CONSULTAR VENDEDORES ";
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
    echo "<script>window.open('sellers-print2.php', '_blank');</script>";
}

if(isset($_POST['pdf'])){
    echo "<script>window.open('sellers-pdf2.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    echo "<script>window.open('sellers-excel2.php');</script>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Buscar vendedores - SiGeCo v1.0</title>
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
            <h2 style="text-align:center; padding:10px;" >Seleccione un campo para realizar la busqueda.</h2>
            <form action="find-sellers.php" method="post" class="search-form" autocomplete="off">
                <select name="search-list" class="list">
                    <option value='DNI'>DNI</option>
                    <option value='name'>Nombre</option>
                    <option value='phone'>Telefono</option>
                    <option value='email'>Email</option>
                    <option value='pdv_ID'>P. de Venta</option>
                </select>
                <input type="text" id="search-text" name="search-text" placeholder="Ingrese un valor">
                <input type="submit" class="menu2-button" style="color:white; font-weight:bold; width:100px;" value="Buscar" name="search">
            </form>

            <?php if ($busqueda=='true') { ?>
                <form method="post" action="find-sellers.php" style="padding:5px;display:inline-block;">
                <input type="submit" class="action-button" value="IMPRIMIR" name="print">
                <input type="submit" class="action-button" value="EXPORTAR A PDF" name="pdf">
                <input type="submit" class="action-button" value="EXPORTAR A EXCEL" name="excel">
        </form>
                <h2 style="text-align:center; padding:10px;" >Resultado de la busqueda.</h2>
                <table>
                    <tr>
                        <th style="width: 10%;">DNI</th>
                        <th style="width: 25%;">Nombre</th>
                        <th>F. de Nacimiento</th>
                        <th style="width: 15%;">Telefono</th>
                        <th style="width: 30%;">E-mail</th>
                        <th>P. de Venta</th>
                    </tr>
                    <?php foreach ($Consulta as $Vendedor) { ?>
                            <tr>
                                <td><?php echo $Vendedor['DNI'];?></td>
                                <td><?php echo $Vendedor['name'];?></td>
                                <td><?php echo date_format(date_create_from_format('Y-m-d', $Vendedor['birthdate']), 'd/m/Y');?></td>
                                <td><?php echo $Vendedor['phone'];?></td>
                                <td><?php echo $Vendedor['email'];?></td>
                                <td><?php echo $Vendedor['pdv_ID'];?></td>
                            </tr>
                    <?php } ?>

                </table>

            <?php } ?>

        </div>

    </div>
    
</body>
</html>