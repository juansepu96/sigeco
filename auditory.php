<?php


require_once "pdo.php";

$validate1=strpos($_SESSION['user.role'],"AUDITOR");
$validate2=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");


$Consulta=$conexion->query("SELECT * from auditory ORDER BY ID desc");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND ( is_int($validate1) OR is_int($validate2))){
    $sepuede='true';
    $movement="ABRIR AUDITORIA";
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

if(isset($_POST['filter'])){
    $Query="SELECT * FROM auditory WHERE ID>0";
    $table=$_POST['table'];
    $user=$_POST['user'];
    $fechadesde=$_POST['fechadesde'];
    $fechahasta=$_POST['fechahasta'];
    if($table!="TODAS"){
        switch ($table){
            case "VENDEDORES":
                $Query=$Query." AND (movement LIKE '%VENDEDOR%')";
            break;
            case "PDV":
                $Query=$Query." AND (movement LIKE '%PUNTO%')";
            break;
            case "USUARIOS":
                $Query=$Query." AND (movement LIKE '%USUARIO%')";
            break;
            case "BACKUPS":
                $Query=$Query." AND (movement LIKE '%COPIA%')";
            break;
            case "EMPRESAS":
                $Query=$Query." AND (movement LIKE '%EMPRESA%')";
            break;
        }
    }

    if($user=="TODOS"){
        $user=0;
        $Query=$Query." AND (ID>:user)";
    }else{
        $Query=$Query." AND (user=:user)";
    }

    if($fechadesde!=""){
        $Query=$Query." AND (date>=:fechadesde)";
    }else{
        $fechadesde=0;
        $Query=$Query." AND (ID>:fechadesde)";
    }

    if($fechahasta!=""){
        $Query=$Query." AND (date<=:fechahasta)";
    }else{
        $fechahasta=0;
        $Query=$Query." AND (ID>:fechahasta)";
    }
    $Query=$Query." ORDER BY ID desc";
    $_SESSION['a.imprimir.2']=$Query;
    $Consulta=$conexion->prepare($Query);
    $Consulta->bindParam(':user',$user);
    $_SESSION['a.imprimir.date']=$user;
    $Consulta->bindParam(':fechadesde',$fechadesde);
    $_SESSION['a.imprimir.date2']=$fechadesde;
    $Consulta->bindParam(':fechahasta',$fechahasta);
    $_SESSION['a.imprimir.date3']=$fechahasta;
    $Consulta->execute();
    $_SESSION['filtre']='true';
}

$Users=$conexion->query("SELECT * FROM users");

if(isset($_POST['print'])){
    if(isset($_SESSION['filtre']) AND $_SESSION['filtre']=='false'){
        $_SESSION['a.imprimir']="SELECT * from auditory ORDER BY ID desc";
        echo "<script>window.open('auditory-print.php', '_blank');</script>";
    }else{
        if(!isset($_SESSION['filtre'])){
            $_SESSION['a.imprimir']="SELECT * from auditory ORDER BY ID desc";
             echo "<script>window.open('auditory-print.php', '_blank');</script>";
        }else{
            echo "<script>window.open('auditory-print2.php', '_blank');</script>";
        }
    }
}

if(isset($_POST['pdf'])){
    if(isset($_SESSION['filtre']) AND $_SESSION['filtre']=='false'){
        $_SESSION['a.imprimir']="SELECT * from auditory ORDER BY ID desc";
        echo "<script>window.open('auditory-pdf.php', '_blank');</script>";
    }else{
        if(!isset($_SESSION['filtre'])){
            $_SESSION['a.imprimir']="SELECT * from auditory ORDER BY ID desc";
             echo "<script>window.open('auditory-pdf.php', '_blank');</script>";
        }else{
            echo "<script>window.open('auditory-pdf2.php', '_blank');</script>";
        }
    }
}


if(isset($_POST['excel'])){
    if(isset($_SESSION['filtre']) AND $_SESSION['filtre']=='false'){
        $_SESSION['a.imprimir']="SELECT * from auditory ORDER BY ID desc";
        echo "<script>window.open('auditory-excel.php', '_blank');</script>";
    }else{
        if(!isset($_SESSION['filtre'])){
            $_SESSION['a.imprimir']="SELECT * from auditory ORDER BY ID desc";
             echo "<script>window.open('auditory-excel.php', '_blank');</script>";
        }else{
            echo "<script>window.open('auditory-excel2.php', '_blank');</script>";
        }
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
    <title>Auditorias - SiGeCo v1.0</title>
</head>
<body>

<div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
        <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <?php require_once "auditory_menu.php"; ?>
    </div>

    <div class="content">
        <div class="table" style="height:100%; width:1330px; padding:0px; margin:5px;">
            <form method="post" action="auditory.php">
                <div id="auditory-filters">
                    <strong style="margin-left:10px;">Tabla:</strong>
                    <select style="width:180px;" name="table" class="list">
                        <option value="TODAS">TODAS</option>
                        <option value="VENDEDORES">VENDEDORES</option>
                        <option value="PDV">PUNTOS DE VENTA</option>
                        <option value="USUARIOS">USUARIOS</option>
                        <option value="EMPRESAS">EMPRESAS</option>
                        <option value="BACKUPS">COPIAS DE SEG.</option>
                    </select>

                    <strong>Usuario:</strong>
                    <select style="width:150px;" name="user" class="list">
                        <option value="TODOS">TODOS</option>
                        <?php foreach ($Users as $User) { ?>
                            <option value="<?php echo $User['name'];?>"><?php echo $User['name'];?></option>
                        <?php } ?>
                    </select>

                    <strong style="margin-right:10px;">Fecha desde:</strong><input type="date" style="padding:10px;width:130px;" name="fechadesde"></strong>
                    <strong style="margin-right:10px;">Fecha hasta:</strong><input type="date" style="padding:10px;width:130px;" name="fechahasta"></strong>
                    <input type="submit" class="menu2-button" style="width:100px;" name="filter" value="Filtrar">
                </div>
                <table style="width:1330px;">
                    <tr>
                        <th>USUARIO</th>
                        <th>FECHA</th>
                        <th>HORA</th>
                        <th>MOVIMIENTO</th>
                        <th >DETALLES</th>
                    </tr>
                    <?php foreach ($Consulta as $Movement) { ?>
                        <tr>
                            <td><?php echo $Movement['user'];?></td>
                            <td><?php echo date_format(date_create_from_format('Y-m-d', $Movement['date']), 'd/m/Y');?></td>
                            <td><?php echo $Movement['time'];?></td>
                            <td><?php echo $Movement['movement'];?></td>
                            <td><?php echo $Movement['description'];?></td>
                        </tr>
                    <?php } ?>

                </table>
            </form>

        </div>

    </div>
    
</body>
</html>