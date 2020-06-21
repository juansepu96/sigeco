<?php

    require_once "pdo.php";

if(isset($_POST['add'])){
    $nombre=$_POST['name'];
    $tipo=$_POST['type'];
    $code='';
    if(isset($_POST['dad'])){
        $dad=$_POST['dad'];
        }else{
            $dad=0;
        }
    if($tipo==0){
        $imputable=0;
    }else{
        $imputable=1;
    }
    if(isset($_POST['inflacion'])){
        $inflacion=1;
    }else{
        $inflacion=0;
    }
    $active=1;

    $contador=1;
    $buscando='true';

    if($dad!=""){
        $code=$dad.'-'.$contador;
        while ($buscando=='true'){
              $ObtenerAccounts=$conexion->query("SELECT * FROM accounts");
              foreach ($ObtenerAccounts as $Account) {
                    if($Account['code']==$code){
                        $contador=$contador+1;
                        $code=$dad.'-'.$contador;
                    }else{
                        $buscando='false';
                    }
              }
        }
    }else{
        $code=$code.'-'.$contador;
        while ($buscando=='true'){
            $ObtenerAccounts=$conexion->query("SELECT * FROM accounts");
            foreach ($ObtenerAccounts as $Account) {
                if($Account['code']==$code){
                    $contador=$contador+1;
                    $code=$dad.'-'.$contador;
                }else{
                    $buscando='false';
                }
             }
        }
    }

    $level=substr_count($code,'-')+1;   


    $CreateAccount=$conexion->prepare("INSERT INTO accounts (code,name,dad,type,level,active,imputable,inflacion) VALUES (:code,:name,:dad,:type,:level,:active,:imputable,:inflacion)");
    $CreateAccount->bindParam(':code',$code);
    $CreateAccount->bindParam(':name',$nombre);
    $CreateAccount->bindParam(':dad',$dad);
    $CreateAccount->bindParam(':type',$tipo);
    $CreateAccount->bindParam(':level',$level);
    $CreateAccount->bindParam(':active',$active);
    $CreateAccount->bindParam(':imputable',$imputable);
    $CreateAccount->bindParam(':inflacion',$inflacion);
    $CreateAccount->execute();

}

$GetAccounts=$conexion->query('SELECT * FROM accounts WHERE type=0 ORDER BY code ASC');

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Nuevo Plan de Cuentas - SiGeCo v 1.0</title>
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

            <form method="post" action="new_plandecuentas.php" id="newseller-form" autocomplete="off" style="transform:translateX(150px);">

                <h2 style="text-align:center; padding:10px;">Nueva Cuenta</h2>

                <strong style="margin-right:75px;">Nombre:</strong><input type="text" class="newseller-field" name="name" style="font-size:1em;"> <br><br>

                <strong style="margin-right:0px;">Tipo de Cuenta:</strong>

                <select required name="type" id="type" style="padding:15px;border-radius:15px;width:200px;background:white;font-size:1em;" class="newseller-field" onChange="ValidarTipo();">

                    <option selected disabled value=""></option>
                        <option value="0">Titulo</option>
                        <option value="1">Cuenta</option>                
                </select> <br> <br>

                <div id="dad">

                    <strong style="margin-right:25px;">Titulo Padre:</strong>

                    <select required name="dad" style="padding:15px;border-radius:15px;width:200px;background:white;font-size:1em;" class="newseller-field">

                        <option selected disabled value=""></option>
                        <?php foreach ($GetAccounts as $Account) { ?>
                              <?php $nivel=$Account['level']; 

                                $espaciado='';

                                for ($i=$nivel;$i>1;$i--) {
                                    $espaciado=$espaciado.'&nbsp;&nbsp;&nbsp;&nbsp;';
                                }

                              
                              ?>
                              
                              <option value="<?php echo $Account['code'];?>"><?php echo $espaciado.$Account['name'];?></option>
                        <?php } ?>

                    </select> 
                </div>
                
                <br> <br>

               <strong style="margin-right:15px;">Ajuste por inflación:</strong><input type="checkbox" name="inflacion" value="inflacion" class="newseller-field" style="-webkit-transform: scale(2); pading:10px;">
                
                <br><br>

                <input type="submit" value="Agregar" name="add" class="menu2-button" style="color:white;transform:translatex(500px);">
            </form>

        </div>

    </div>

</body>
</html>