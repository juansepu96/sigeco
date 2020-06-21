<?php


require_once "pdo.php";

$validate1=strpos($_SESSION['user.role'],"VENDEDORES");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['delete'])){
    $IDNumber=$_POST['id-number'];
    $GetSeller=$conexion->prepare("SELECT * FROM sellers WHERE ID=:idnumber");
    $GetSeller->bindParam(':idnumber',$IDNumber);
    $GetSeller->execute();
    foreach ($GetSeller as $Seller){
        $Name=$Seller['name'];
    }
    $DeleteSeller=$conexion->prepare("DELETE FROM sellers WHERE ID=:idnumber");
    $DeleteSeller->bindParam(':idnumber',$IDNumber);
    $DeleteSeller->execute();
    $movement="ELIMINAR VENDEDORES";
    $description="ELIMINAR VENDEDOR ID: ".$IDNumber." NOMBRE: ".$Name;
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
    $NewMovement->bindParam(':user',$_SESSION['user.name']);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->bindParam(':description',$description);
    $NewMovement->execute();
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
    <title>Eliminar vendedores - SiGeCo v1.0</title>
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
            <table>
                <tr>
                    <th style="width: 10%;">DNI</th>
                    <th style="width: 25%;">Nombre</th>
                    <th>F. de Nacimiento</th>
                    <th style="width: 15%;">Telefono</th>
                    <th style="width: 30%;">E-mail</th>
                    <th style="width: 15%;">BAJA</th>
                </tr>
                <?php foreach ($Consulta as $Vendedor) { ?>
                    <form method="post" action="delete-seller.php">
                        <tr>
                            <td><?php echo $Vendedor['DNI'];?></td>
                            <td><?php echo $Vendedor['name'];?></td>
                            <td><?php echo date_format(date_create_from_format('Y-m-d', $Vendedor['birthdate']), 'd/m/Y');?></td>
                            <td><?php echo $Vendedor['phone'];?></td>
                            <td><?php echo $Vendedor['email'];?></td>
                            <td hidden><input type="text"  name="id-number" value="<?php echo $Vendedor['ID'];?>"></td>
                            <td style="background:red;cursor:pointer;"><input readonly onclick="if(confirm('BORRARA UN REGISTRO, DESEA CONTINUAR?')){
                                this.form.submit();}
                                else{ alert('Operacion Cancelada');}" style="cursor:pointer;background:red;color:white;text-align:center;width:40px;" value="BAJA" name="delete"></td>
                        </tr>
                     </form>
                <?php } ?>

            </table>

        </div>

    </div>
    
</body>
</html>