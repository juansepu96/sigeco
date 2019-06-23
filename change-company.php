<?php 


$sepuede='false';

require_once "pdo.php";

$validate1=strpos($_SESSION['user.role'],"SUPERADMINISTRADOR");

if(isset($_SESSION['user.login']) AND $_SESSION['user.login']==="true" AND is_int($validate1)){
    $sepuede='true';
    }else{
        $sepuede='false';
        header('Location: index2.php');
}

$GetCompanyData=$conexion->query("SELECT * from companydata");

foreach ($GetCompanyData as $Company) {
    $id=$Company['ID'];
    $company_name=$Company['name'];
    $logo=$Company['logo'];
    $style=$Company['style'];
    $customer_type=$Company['customertype'];
    $tribut_id=$Company['tribut_id'];
    $location=$Company['location'];
}

if(isset($_POST['update'])){
    
    if($_FILES["company_logo"]["name"]==""){ //NO SE CARGO LOGO NUEVO

        $movement="SE MODIFICARON LOS DATOS DE LA EMPRESA";
        $description="";
        if($company_name!=$_POST['company_name']){
            $description=$description."SE CAMBIO EL NOMBRE ".$company_name." POR ".$_POST['company_name']."<br>";
        }
        if($style!=$_POST['company_style']){
            $description=$description."SE CAMBIO EL ESTILO ".$style." POR ".$_POST['company_style']."<br>";
        }
        if($customer_type!=$_POST['IVA_Status']){
            $description=$description."SE CAMBIO LA SIT. FRENTE AL IVA ".$customer_type." POR ".$_POST['IVA_Status']."<br>";
        }
        if($tribut_id!=$_POST['company_cuit']){
            $description=$description."SE CAMBIO EL CUIT ".$tribut_id." POR ".$_POST['company_cuit']."<br>";
        }
        if($location!=$_POST['company_location']){
            $description=$description."SE CAMBIO LA DIRECCION ".$location." POR ".$_POST['company_location']."<br>";
        }
        $description=$description."<br>NO SE MODIFICO EL LOGO<br>";

        $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
        $NewMovement->bindParam(':user',$_SESSION['user.name']);
        $NewMovement->bindParam(':date',$date);
        $NewMovement->bindParam(':time',$time);
        $NewMovement->bindParam(':movement',$movement);
        $NewMovement->bindParam(':description',$description);
        $NewMovement->execute();

        $companyname=$_POST['company_name'];
        $template=$_POST['company_style'];
        $IVA_Status=$_POST['IVA_Status'];
        $company_cuit=$_POST['company_cuit'];
        $company_location=$_POST['company_location'];

        $InsertCompany=$conexion->prepare("UPDATE companydata SET name=:name,style=:style,tribut_id=:tribut_id,customertype=:customertype,location=:location WHERE ID=:id");
        $InsertCompany->bindParam(':id',$id);
        $InsertCompany->bindParam(':name',$companyname);
        $InsertCompany->bindParam(':style',$template);
        $InsertCompany->bindParam(':tribut_id',$company_cuit);
        $InsertCompany->bindParam(':customertype',$IVA_Status);
        $InsertCompany->bindParam(':location',$company_location);
        $InsertCompany->execute();

        $conexion = new PDO('mysql:host=localhost;dbname=sigeusuv3','root','');

        $InsertCompanyData=$conexion->prepare("UPDATE general_companydata SET name=:name,style=:style WHERE name=:name2");
        $InsertCompanyData->bindParam(':name2',$company_name);
        $InsertCompanyData->bindParam(':name',$companyname);
        $InsertCompanyData->bindParam(':style',$template);
        $InsertCompanyData->execute();


    }else{ //SE CARGO NUEVO LOGO
        $movement="SE MODIFICARON LOS DATOS DE LA EMPRESA";
        $description="";
        if($company_name!=$_POST['company_name']){
            $description=$description."SE CAMBIO EL NOMBRE ".$company_name." POR ".$_POST['company_name']."<br>";
        }
        if($style!=$_POST['company_style']){
            $description=$description."SE CAMBIO EL ESTILO ".$style." POR ".$_POST['company_style']."<br>";
        }
        if($customer_type!=$_POST['IVA_Status']){
            $description=$description."SE CAMBIO LA SIT. FRENTE AL IVA ".$customer_type." POR ".$_POST['IVA_Status']."<br>";
        }
        if($tribut_id!=$_POST['company_cuit']){
            $description=$description."SE CAMBIO EL CUIT ".$tribut_id." POR ".$_POST['company_cuit']."<br>";
        }
        if($location!=$_POST['company_location']){
            $description=$description."SE CAMBIO LA DIRECCION ".$location." POR ".$_POST['company_location']."<br>";
        }
        $description=$description."<br>SE MODIFICO EL LOGO<br>";

        $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement,description) VALUES (:user,:date,:time,:movement,:description)");
        $NewMovement->bindParam(':user',$_SESSION['user.name']);
        $NewMovement->bindParam(':date',$date);
        $NewMovement->bindParam(':time',$time);
        $NewMovement->bindParam(':movement',$movement);
        $NewMovement->bindParam(':description',$description);
        $NewMovement->execute();


        $companyname=$_POST['company_name'];
        $bd_name=trim($companyname);
        $bd_name=str_replace(' ','',$bd_name);
        $bd_name=str_replace('/','',$bd_name);
        $bd_name=str_replace('.','',$bd_name);
        $template=$_POST['company_style'];
        $IVA_Status=$_POST['IVA_Status'];
        $company_cuit=$_POST['company_cuit'];
        $company_location=$_POST['company_location'];
        $fileTmpPath = $_FILES['company_logo']['tmp_name'];
        $fileName = $_FILES['company_logo']['name'];
        $fileSize = $_FILES['company_logo']['size'];
        $fileType = $_FILES['company_logo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = $bd_name . '.' . $fileExtension;
        $uploadFileDir = '../sigeusu v3/logos/';
        $dest_path = $uploadFileDir . $newFileName;
        move_uploaded_file($fileTmpPath, $dest_path);    

        $InsertCompany=$conexion->prepare("UPDATE companydata SET name=:name,style=:style,logo=:logo,tribut_id=:tribut_id,customertype=:customertype,location=:location WHERE ID=:id");
        $InsertCompany->bindParam(':id',$id);
        $InsertCompany->bindParam(':name',$companyname);
        $InsertCompany->bindParam(':style',$template);
        $InsertCompany->bindParam(':logo',$newFileName);
        $InsertCompany->bindParam(':tribut_id',$company_cuit);
        $InsertCompany->bindParam(':customertype',$IVA_Status);
        $InsertCompany->bindParam(':location',$company_location);
        $InsertCompany->execute();

        $conexion = new PDO('mysql:host=localhost;dbname=sigeusuv3','root','');

        $InsertCompanyData=$conexion->prepare("UPDATE general_companydata SET name=:name,style=:style,logo=:logo WHERE name=:name2");
        $InsertCompanyData->bindParam(':name2',$company_name);
        $InsertCompanyData->bindParam(':name',$companyname);
        $InsertCompanyData->bindParam(':logo',$newFileName);
        $InsertCompanyData->bindParam(':style',$template);
        $InsertCompanyData->execute();
    }
        
   header("Location: index.php"); 

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Modificar empresa - SiGeUsu v3</title>
</head>
<body>
    <div class="header">
            <img src="/sigeusu v3/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
            <p class="header-text">Usuario: <?php echo $_SESSION['user.name'];?></p>
            <p class="header-text">Rol: <?php echo $_SESSION['user.role'];?></p>
            <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
            <?php require_once "menu.php"; ?>
    </div>
    
    <div id="new-company-form">

        <form action="change-company.php" id="newseller-form"  method="post" autocomplete="off" enctype="multipart/form-data">
        
        <h2 style="text-align:center; padding:10px;">MODIFICAR MI EMPRESA</h2>

            <p style="font-weight:bold;">Nombre: <input style="margin-left:90px;" type="text" name="company_name" id="company_name" class="newseller-field" value="<?php echo $company_name;?>"></p>
            <p style="font-weight:bold;">Logo: <input style="margin-left:110px;" accept="image/*" type="file" name="company_logo" id="company_logo" class="newseller-field"></p>
            <p style="font-weight:bold;">Plantilla: 
            <select name="company_style" class="newseller-field" style="background:white;margin-left:86px;">
                
                <?php if($style=="skyblue.css"){ ?>
                    <option selected value="skyblue.css">Celeste</option>
                <?php }else{ ?>
                    <option value="skyblue.css">Celeste</option>
                <?php } ?>

                <?php if($style=="yellow.css"){ ?>
                    <option selected value="yellow.css">Amarillo</option>
                <?php }else{ ?>
                    <option value="yellow.css">Amarillo</option>
                <?php } ?>

                <?php if($style=="orange.css"){ ?>
                    <option selected value="orange.css">Naranja</option>
                <?php }else{ ?>
                    <option value="orange.css">Naranja</option>
                <?php } ?>

                <?php if($style=="red.css"){ ?>
                    <option selected value="red.css">Morado</option>
                <?php }else{ ?>
                    <option value="red.css">Morado</option>
                <?php } ?>                
                        
            </select>
            </p>
            <p style="font-weight:bold;">Sit. frente al IVA: 
                    <select name="IVA_Status" class="newseller-field" style="background:white;margin-left:30px;">

                        <?php if ($customer_type=="Consumidor Final") { ?>
                            <option selected value="Consumidor Final">Consumidor Final</option>
                        <?php }else{ ?>
                            <option value="Consumidor Final">Consumidor Final</option>
                        <?php } ?>

                        <?php if ($customer_type=="Responsable Inscripto") { ?>
                            <option selected value="Responsable Inscripto">Responsable Inscripto</option>
                        <?php }else{ ?>
                            <option value="Responsable Inscripto">Responsable Inscripto</option>
                        <?php } ?>

                        <?php if ($customer_type=="IVA Sujeto Exento") { ?>
                            <option selected value="IVA Sujeto Exento">IVA Sujeto Exento</option>
                        <?php }else{ ?>
                            <option value="IVA Sujeto Exento">IVA Sujeto Exento</option>
                        <?php } ?>

                        <?php if ($customer_type=="Responsable Monotributo") { ?>
                            <option selected value="Responsable Monotributo">Responsable Monotributo</option>
                        <?php }else{ ?>
                            <option value="Responsable Monotributo">Responsable Monotributo</option>
                        <?php } ?>

                        <?php if ($customer_type=="Monotributista Social") { ?>
                            <option selected value="Monotributista Social">Monotributista Social</option>
                        <?php }else{ ?>
                            <option value="Monotributista Social">Monotributista Social</option>
                        <?php } ?>                                     
                        
                    </select>
            </p>
            <p style="font-weight:bold;">CUIT: <input style="margin-left:105px;" type="text" name="company_cuit" id="company_cuit" class="newseller-field" value="<?php echo $tribut_id;?>"></p>
            <p style="font-weight:bold;">Direccion: <input style="margin-left:75px;" type="text" name="company_location" id="company_location" class="newseller-field" value="<?php echo $location;?>"></p>
            <input type="submit" style="color:white;transform:translateX(500px);margin:20px;height:50px;" name="update" id="update" class="menu-button" value="Actualizar">

        </form>

        <a href="index2.php"><p class="back-text" style="transform:translate(-300px,200px);">VOLVER</p></a>

    </div>

</body>
</html>

