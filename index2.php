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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Bienvenido! - SiGeUsu v3</title>
</head>
<body>

<div class="header">
        <img src="/sigeusu v3/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "menu.php"; ?>
    </div>
    <div class="form" style="text-align:center; transform:translateY(160px);height:280px;"> 
        <h1 class="welcome-text">Bienvenido: <?php echo $_SESSION['user.name'];?> </h1>
        <h1 class="welcome-text">Has inicido sesion de forma existosa!</h1>
        <h1 class="welcome-text">Selecciona una opción del menu para empezar</h1>
    </div>
    
</body>
</html>