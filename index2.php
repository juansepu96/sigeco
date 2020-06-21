<?php


require_once "pdo.php";


if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true"){
    $sepuede='true';
    }else{
        $sepuede='false';
        header('Location: login.php');
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Bienvenido! - SiGeCo v1.0</title>
</head>
<body>

<div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
</div>
    
 <?php require_once "main_menu.php"; ?>
    
    
</body>
</html>