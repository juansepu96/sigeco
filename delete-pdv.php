<?php


require_once "pdo.php";

$validate1=strpos($_SESSION['user.role'],"PDV");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    
    }else{
        $sepuede='false';
        header('Location: index2.php');

}

if(isset($_POST['delete'])){
    $IDNumber=$_POST['ID'];
    $SearchSellers=$conexion->prepare("SELECT * FROM sellers WHERE pdv_ID=:id");
    $SearchSellers->bindParam(':id',$IDNumber);
    $SearchSellers->execute();
    $filas=$SearchSellers->rowcount();
    if($filas!=0){
        echo "<script language='JavaScript'>alert('No se puede eliminar este PDV porque hay vendedores vinculados a él. Debe eliminar todos los vendedores de ese PDV para poder continuar...');</script>"; 
    }else{
        $GetPDV=$conexion->prepare("SELECT * FROM pdv WHERE ID=:idnumber");
        $GetPDV->bindParam(':idnumber',$IDNumber);
        $GetPDV->execute();
        foreach ($GetPDV as $PDV){
            $Name=$PDV['description'];
        }
        $DeletePDV=$conexion->prepare("DELETE FROM pdv WHERE ID=:idnumber");
        $DeletePDV->bindParam(':idnumber',$IDNumber);
        $DeletePDV->execute();
        $movement="ELIMINAR PUNTO DE VENTA";
        $description="ELIMINAR PUNTO DE VENTA ID: ".$IDNumber." DESCRIPCION: ".$Name;
        $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
        $NewMovement->bindParam(':user',$_SESSION['user.name']);
        $NewMovement->bindParam(':date',$date);
        $NewMovement->bindParam(':time',$time);
        $NewMovement->bindParam(':movement',$movement);
        $NewMovement->bindParam(':description',$description);
        $NewMovement->execute();
    }
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
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src=”https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js”></script>
    <title>Eliminar PDV - SiGeCo v1.0</title>
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
            <table>
              <tr>
                    <th style="width:100px;">PV</th>
                    <th style="width:600px;">Descripcion</th>
                    <th style="width:160px;">Seleccionar</th>
                 </tr>
                <?php foreach ($Consulta as $PDV) { ?>
                    <form method="post" action="delete-pdv.php" id="formulario">
                        <tr>
                            <td hidden><input type="text"  id="ID" name="ID" value="<?php echo $PDV['ID'];?>"></td>
                            <td><?php echo $PDV['ID'];?></td>
                            <td><?php echo $PDV['description'];?>
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