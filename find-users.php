<?php


require_once "pdo.php";

$busqueda='false';


$validate1=strpos($_SESSION['user.role'],"USERS");
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
        case "name":
            $Consulta=$conexion->prepare("SELECT * FROM users WHERE name LIKE :texto");  
            $_SESSION['a.imprimir.consulta']="SELECT * FROM users WHERE name LIKE :texto"; 
            $description="Nombre: ".$text;
            break;
        case "user":
            $Consulta=$conexion->prepare("SELECT * FROM users WHERE user LIKE :texto");  
            $_SESSION['a.imprimir.consulta']="SELECT * FROM users WHERE user LIKE :texto"; 
            $description="Usuario: ".$text;
            break;
        case "role":
            $Consulta=$conexion->prepare("SELECT * FROM users WHERE role LIKE :texto"); 
            $_SESSION['a.imprimir.consulta']="SELECT * FROM users WHERE role LIKE :texto"; 
            $description="Rol: ".$text; 
            break;
        case "active":
            $Consulta=$conexion->prepare("SELECT * FROM users WHERE active LIKE :texto");  
            $_SESSION['a.imprimir.consulta']="SELECT * FROM users WHERE active LIKE :texto"; 
            $description="Activo: ".$text;
            break;
    }
    $Consulta->bindParam(':texto',$text);
    $_SESSION['a.imprmir.dato']=$text;
    $Consulta->execute();
    $description=str_replace("%","",$description);
    $movement="CONSULTAR USUARIOS ";
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
    $_SESSION['a.imprimir']="SELECT * from users";
    echo "<script>window.open('user-print2.php', '_blank');</script>";
}

if(isset($_POST['pdf'])){
    $_SESSION['a.imprimir']="SELECT * from users";
    echo "<script>window.open('user-pdf2.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    $_SESSION['a.imprimir']="SELECT * from users";
    echo "<script>window.open('user-excel2.php');</script>";
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Buscar Usuarios - SiGeCo v1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "users_menu.php"; ?>
    </div>

    <div class="content">
        <div class="table">
            <h2 style="text-align:center; padding:10px;" >Seleccione un campo para realizar la busqueda.</h2>
            <form action="find-users.php" method="post" class="search-form" autocomplete="off">
                <select name="search-list" class="list">
                    <option value='name'>Nombre</option>
                    <option value='user'>Usuario</option>
                    <option value='role'>Rol</option>
                    <option value='active'>Activo</option>
                </select>
                <input type="text" id="search-text" name="search-text" placeholder="Ingrese un valor">
                <input type="submit" class="menu2-button" style="color:white; font-weight:bold; width:100px;" value="Buscar" name="search">
            </form>

            <?php if ($busqueda=='true') { ?>
                <form method="post" action="find-users.php" style="padding:5px;display:inline-block;">
                <input type="submit" style="margin-left:150px; padding:10px; color:white; background:blue; border-radius:10px;cursor:pointer;font-width:bold;" value="IMPRIMIR" name="print">
                <input type="submit" class="action-button" value="EXPORTAR A PDF" name="pdf">
                <input type="submit" class="action-button" value="EXPORTAR A EXCEL" name="excel">
                </form>
                <h2 style="text-align:center; padding:10px;" >Resultado de la busqueda.</h2>
                <table>
                    <tr>
                        <th style="width: 35%;">Nombre</th>
                        <th style="width: 35%;">Usuario</th>
                        <th style="width: 30%;">Rol</th>
                        <th style="width: 15%;">Activo</th>
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

            <?php } ?>

        </div>

        <div>
            <ul class="SecondMenu"> 
                <a href="new-users.php"><li class="menu2-button">ALTA</li></a>
                <a href="delete-users.php"><li class="menu2-button">BAJA</li></a>
                <a href="change-users.php"><li class="menu2-button">MODIFICAR</li></a>
                <a href="find-users.php"><li class="menu2-button" style="background:black;">CONSULTAR</li></a>
                <a href="users.php"><li class="menu2-button" >LISTAR</li></a>
            </ul>

        </div>

    </div>
    
</body>
</html>