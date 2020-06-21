<?php

    require_once "pdo.php";

    $GetAccounts=$conexion->query('SELECT * FROM accounts ORDER BY code ASC');

    if(isset($_POST['print'])){
        $_SESSION['a.imprimir']="SELECT * FROM accounts ORDER BY code ASC";
        echo "<script>window.open('accounts-print.php', '_blank');</script>";
    }

    if(isset($_POST['pdf'])){
        $_SESSION['a.imprimir']="SELECT * FROM accounts ORDER BY code ASC";
        echo "<script>window.open('accounts-pdf.php', '_blank');</script>";
    }

    if(isset($_POST['excel'])){
        $_SESSION['a.imprimir']="SELECT * FROM accounts ORDER BY code ASC";
        echo "<script>window.open('accounts-excel.php', '_blank');</script>";
    }


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Listar Plan de Cuentas - SiGeCo v 1.0</title>    
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

        <?php require_once "plan_cuentas_export.php"; ?>

        <table style="width:100%">
                <tr>
                    <th>Codigo</th>
                    <th>Nro. de Cuenta</th>
                    <th>Nombre Cuenta</th>
                    <th>Imputable</th>
                    <th>Ajuste por Inflación</th>
                </tr>
                <?php foreach ($GetAccounts as $Account) { ?>
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
                        <td style="text-align:left;"><?php echo $espaciado.$Account['name'];?></td>
                        <?php ($Account['imputable']==0) ? $imputable='NO' : $imputable='SI'; ?>
                        <td><?php echo $imputable;?></td>
                        <?php ($Account['inflacion']==0) ? $inflacion='NO' : $inflacion='SI'; ?>
                        <td><?php echo $inflacion;?></td>
                    </tr>
                    <? }else { ?>
                        <tr>
                            <td style="text-align:left;"><?php echo $Account['code'];?></td>
                            <td><?php echo $Account['ID'];?></td>
                            <td style="text-align:left;"><?php echo $espaciado.$Account['name'];?></td>
                            <?php ($Account['imputable']==0) ? $imputable='NO' : $imputable='SI'; ?>
                            <td><?php echo $imputable;?></td>
                            <?php ($Account['inflacion']==0) ? $inflacion='NO' : $inflacion='SI'; ?>
                            <td><?php echo $inflacion;?></td>
                      </tr>

                    <? } ?>

                <?php } ?>

            </table>

        </div>

    </div>

</body>
</html>