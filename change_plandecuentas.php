<?php

    require_once "pdo.php";

    $GetAccounts=$conexion->query('SELECT * FROM accounts ORDER BY code ASC');

    if(isset($_POST['changename'])){
        $_SESSION['Account']=$_POST['ID'];
        header ("Location: Change-Account.php");
    }

    if(isset($_POST['changeaj'])){
        $ID=$_POST['ID'];
        $inflacion=$_POST['inflacion'];
        if($inflacion=='SI'){
            $new_infla='0';
        }else{
            $new_infla='1';
        }
        $UpdateAjuste=$conexion->prepare("UPDATE accounts SET inflacion=:inflacion WHERE ID=:id");
        $UpdateAjuste->bindParam(':id',$ID);
        $UpdateAjuste->bindParam(':inflacion',$new_infla);
        $UpdateAjuste->execute();
        header("Location: change_plandecuentas.php");
    }

    if(isset($_POST['changetodisabled'])){
        $ID=$_POST['ID'];
        $UpdateStatus=$conexion->prepare("UPDATE accounts SET active=0 WHERE ID=:id");
        $UpdateStatus->bindParam(':id',$ID);
        $UpdateStatus->execute();
        header("Location: change_plandecuentas.php");
    }

    if(isset($_POST['changetoactive'])){
        $ID=$_POST['ID'];
        $UpdateStatus=$conexion->prepare("UPDATE accounts SET active=1 WHERE ID=:id");
        $UpdateStatus->bindParam(':id',$ID);
        $UpdateStatus->execute();
        header("Location: change_plandecuentas.php");
    }

    if(isset($_POST['changetype'])){
        $ID=$_POST['ID'];

        $encontre=0;
        $Codigo=$_POST['CODE'];
        $ObtenerCuentas=$conexion->query("SELECT * from accounts");
        foreach ($ObtenerCuentas as $Cuenta){
            if($Cuenta['dad']==$Codigo){
                $encontre=1;
                break;
            }
        }
        if($encontre==1){
            echo "<script type=\"text/javascript\">alert(\"No puedes cambiar el titulo a cuenta porque tiene hijos.\");</script>"; 

        }else{
            $UpdateType=$conexion->prepare("UPDATE accounts SET type=1,imputable=1 WHERE ID=:id");
            $UpdateType->bindParam(':id',$ID);
            $UpdateType->execute();
            header("Location: change_plandecuentas.php");
        }
    }

    if(isset($_POST['changetype2'])){
        $ID=$_POST['ID'];
        $encontre=0;
        $Codigo=$_POST['CODE'];
        $ObtenerAsientos=$conexion->query("SELECT * from assets WHERE status<>0");
        foreach ($ObtenerAsientos as $Asiento){
            if($Asiento['account']==$Codigo){
                $encontre=1;
                break;
            }
        }
        if($encontre==1){
            echo "<script type=\"text/javascript\">alert(\"No puedes cambiar la cuenta a titulos porque existe un asiento asociado a esta cuenta.\");</script>"; 

        }else{
            $UpdateType=$conexion->prepare("UPDATE accounts SET type=0,imputable=0 WHERE ID=:id");
            $UpdateType->bindParam(':id',$ID);
            $UpdateType->execute();
            header("Location: change_plandecuentas.php");
        }
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Modificar una Cuenta - SiGeCo v 1.0</title>    
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "plandecuentas_menu.php"; ?>
    </div>

    <div class="content">

        <div class="table">

        <form method="post" action="change_plandecuentas.php">

        <table style="width:100%">
                <tr>
                    <th>Codigo</th>
                    <th>Nro. de Cuenta</th>
                    <th>Nombre Cuenta</th>
                    <th>Imputable</th>
                    <th>Ajuste por Inflación</th>
                    <th>Cambiar Nombre</th>
                    <th>Convertir Tipo</th>
                    <th>Cambiar Aj. Infl.</th>
                    <th>Estado</th>
                </tr>
                <?php foreach ($GetAccounts as $Account) { ?>
                    <form method="post" action="change_plandecuentas.php">
                    <?php $nivel=$Account['level']; 

                        $espaciado='';

                        for ($i=$nivel;$i>1;$i--) {
                            $espaciado=$espaciado.'&nbsp;&nbsp;&nbsp;&nbsp;';
                        }

                    ?>

                    <?php if ($Account['type']==0) { ?>
                    <tr style="background:#EDE3E1;">
                        <td style="text-align:left;"><?php echo $Account['code'];?></td>
                        <td><?php echo $Account['ID'];?></td>
                        <input type="text" hidden name="ID" value="<?php echo $Account['ID'];?>">
                        <input type="text" hidden name="CODE" value="<?php echo $Account['code'];?>">

                        <td style="text-align:left;"><?php echo $espaciado.$Account['name'];?></td>
                        <?php  if ($Account['imputable']==0) { $imputable='NO'; }else{ $imputable='SI'; } ?>                        
                        <td><?php echo $imputable;?></td>
                        <?php  if ($Account['inflacion']==0) { $inflacion='NO'; }else{ $inflacion='SI'; } ?> 
                        <td><?php echo $inflacion;?></td>
                        <input type="text" hidden name="inflacion" value="<?php echo $inflacion?>">
                        <input type="text" hidden name="status" value="<?php echo $Account['active'];?>">
                        <td style="background:blue;cursor:pointer;"><input style="cursor:pointer;color:white; background:blue;" type="submit" value="Modificar" name="changename"></td>
                        <td style="background:green;cursor:pointer;"><input style="cursor:pointer;color:white; background:green;" type="submit" value="Convertir en cuenta" name="changetype"></td>
                        <td style="background:yellow;cursor:pointer;"><input style="cursor:pointer;color:black; background:yellow;" type="submit" value="Cambiar Aj." name="changeaj"></td>
                        <?php if ($Account['active']==0){ ?>
                            <td style="background:skyblue;cursor:pointer;"><input style="cursor:pointer;color:white; background:skyblue;" type="submit" value="Activar" name="changetoactive"></td>
                        <?php }else{ ?>
                            <td style="background:red;cursor:pointer;"><input style="cursor:pointer;color:white; background:red;" type="submit" value="Desactivar" name="changetodisabled"></td>

                        <?php }?>
                        


                    </tr>
                    <? }else { ?>
                        <tr>
                            <td style="text-align:left;"><?php echo $Account['code'];?></td>
                            <td><?php echo $Account['ID'];?></td>
                        <input type="text" hidden name="ID" value="<?php echo $Account['ID'];?>">
                        <input type="text" hidden name="CODE" value="<?php echo $Account['code'];?>">
                            <td style="text-align:left;"><?php echo $espaciado.$Account['name'];?></td>
                            <?php  if ($Account['imputable']==0) { $imputable='NO'; }else{ $imputable='SI'; } ?>                        
                            <td><?php echo $imputable;?></td>
                            <?php  if ($Account['inflacion']==0) { $inflacion='NO'; }else{ $inflacion='SI'; } ?> 
                            <td><?php echo $inflacion;?></td>
                            <input type="text" hidden name="inflacion" value="<?php echo $inflacion?>">
                            <input type="text" hidden name="status" value="<?php echo $Account['active'];?>">
                             
                            <td style="background:blue;cursor:pointer;"><input style="cursor:pointer;color:white; background:blue;" type="submit" value="Modificar" name="changename"></td>
                            <td style="background:green;cursor:pointer;"><input style="cursor:pointer;color:white; background:green;" type="submit" value="Convetir en Titulo" name="changetype2"></td>
                            <td style="background:yellow;cursor:pointer;"><input style="cursor:pointer;color:black; background:yellow;" type="submit" value="Cambiar Aj." name="changeaj"></td>
                            <?php if ($Account['active']==0){ ?>
                             <td style="background:skyblue;cursor:pointer;"><input style="cursor:pointer;color:white; background:skyblue;" type="submit" value="Activar" name="changetoactive"></td>
                            <?php }else{ ?>
                                <td style="background:red;cursor:pointer;"><input style="cursor:pointer;color:white; background:red;" type="submit" value="Desactivar" name="chantodisabled"></td>
                            <?php }?>
                        


                      </tr>

                    <? } ?>

                    </form>

                <?php } ?>

            </table>

            </form>
        </div>

    </div>

</body>
</html>