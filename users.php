<?php


require_once "pdo.php";

$validate1=strpos($_SESSION['user.role'],"USERS");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    $movement="LISTAR USUARIOS";
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

$Consulta=$conexion->query("SELECT * from users");


if(isset($_POST['print'])){
    $_SESSION['a.imprimir']="SELECT * from users";
    echo "<script>window.open('user-print.php', '_blank');</script>";

}
if(isset($_POST['pdf'])){
    $_SESSION['a.imprimir']="SELECT * from users";
    echo "<script>window.open('user-pdf.php', '_blank');</script>";
}

if(isset($_POST['excel'])){
    $_SESSION['a.imprimir']="SELECT * from users";
    echo "<script>window.open('user-excel.php');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Listar Usuarios - SiGeUsu v3</title>
</head>
<body>

    <div class="header">
        <img src="/sigeusu v3/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>

        <?php require_once "menu.php"; ?>
        
    </div>

    <div class="content">
        <div class="table">
        <form method="post" action="users.php" style="padding:5px;display:inline-block;">
                <input type="submit" class="action-button" style="margin-left:150px;" value="IMPRIMIR" name="print">
                <input type="submit" class="action-button" value="EXPORTAR A PDF" name="pdf">
                <input type="submit" class="action-button" value="EXPORTAR A EXCEL" name="excel">
        </form>
            <table>
                <tr>
                    <th style="width: 40%;">Nombre</th>
                    <th style="width: 25%;">Usuario</th>
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

        </div>

        <div>
            <ul class="SecondMenu"> 
                <a href="new-users.php"><li class="menu2-button">ALTA</li></a>
                <a href="delete-users.php"><li class="menu2-button">BAJA</li></a>
                <a href="change-users.php"><li class="menu2-button">MODIFICAR</li></a>
                <a href="find-users.php"><li class="menu2-button">CONSULTAR</li></a>
                <a href="users.php"><li class="menu2-button" style="background:black;">LISTAR</li></a>
            </ul>

        </div>

    </div>
    
</body>
</html>