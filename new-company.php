<?php 

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

$date=date("Y-m-d");
$time=date("H:i:s");


try {
	$conexion = new PDO('mysql:host=localhost;dbname=sigeusuv3','root','');

    }catch(PDOException $e){
            echo "Error" . $e->getMessage();

}


if(isset($_POST['submit'])){
    $companyname=$_POST['company_name'];
    $bd_name=trim($companyname);
    $bd_name=str_replace(' ','',$bd_name);
    $bd_name=str_replace('/','',$bd_name);
    $bd_name=str_replace('.','',$bd_name);
    $bd_name=str_replace(':','',$bd_name);
    $bd_name=str_replace(',','',$bd_name);
    $bd_name=str_replace('ñ','n',$bd_name);
    $bd_name=str_replace('Ñ','N',$bd_name);
    $template=$_POST['company_style'];
    $unique_key=$_POST['unique_key'];
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
    $uploadFileDir = '../sigeco/logos/';
    $dest_path = $uploadFileDir . $newFileName;
    move_uploaded_file($fileTmpPath, $dest_path);    

    $InsertCompany=$conexion->prepare("INSERT INTO general_companydata (name,dbname,logo,style) VALUES (:name,:dbname,:logo,:style)");
    $InsertCompany->bindParam(':name',$companyname);
    $InsertCompany->bindParam(':dbname',$bd_name);
    $InsertCompany->bindParam(':logo',$newFileName);
    $InsertCompany->bindParam(':style',$template);
    $InsertCompany->execute();

    $conexion = new PDO('mysql:host=localhost','root','');

    $CreateDatabase=$conexion->query("CREATE DATABASE `$bd_name`");

	$conexion2 = new PDO('mysql:host=localhost;dbname='.$bd_name,'root','');

    $CreateTables=$conexion2->query("CREATE TABLE auditory (ID INT(11) AUTO_INCREMENT PRIMARY KEY,user VARCHAR(50) NOT NULL,date date NOT NULL,time time NOT NULL,movement VARCHAR(200) NOT NULL,description text)");

    $CreateTables=$conexion2->query("CREATE TABLE companydata (ID INT(11) AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,dbname VARCHAR(50) NOT NULL,logo VARCHAR(50) NOT NULL,style VARCHAR(50) NOT NULL,tribut_id VARCHAR(30) NOT NULL,customertype VARCHAR(100) NOT NULL,location VARCHAR(100) NOT NULL,inicio_fiscal DATE,fin_fiscal DATE, validatekey VARCHAR (200))");

    $InsertCompanyData=$conexion2->prepare("INSERT INTO companydata (name,dbname,logo,style,tribut_id,customertype,location) VALUES (:name,:bdname,:logo,:style,:tribut_id,:customer_type,:location)");

    $InsertCompanyData->bindParam(':name',$companyname);
    $InsertCompanyData->bindParam(':bdname',$bd_name);
    $InsertCompanyData->bindParam(':logo',$newFileName);
    $InsertCompanyData->bindParam(':style',$template);
    $InsertCompanyData->bindParam(':tribut_id',$company_cuit);
    $InsertCompanyData->bindParam(':customer_type',$IVA_Status);
    $InsertCompanyData->bindParam(':location',$company_location);
    $InsertCompanyData->execute();


    $CreateTables=$conexion2->query("CREATE TABLE pdv (ID INT(11) AUTO_INCREMENT PRIMARY KEY,description VARCHAR(200) NOT NULL)");

    $CreateTables=$conexion2->query("CREATE TABLE sellers (ID INT(11) AUTO_INCREMENT PRIMARY KEY,DNI INT(11) NOT NULL,name VARCHAR(200) NOT NULL,birthdate date NOT NULL,phone BIGINT(20) NOT NULL,email VARCHAR(200) NOT NULL,pdv_ID INT(11) NOT NULL,zone_ID INT(11) NOT NULL)");

    $CreateTables=$conexion2->query("CREATE TABLE users (ID INT(11) AUTO_INCREMENT PRIMARY KEY,name VARCHAR(200) NOT NULL,active VARCHAR(2) NOT NULL,password VARCHAR(200) NOT NULL,role VARCHAR(50) NOT NULL,user VARCHAR(200) NOT NULL)");

    $InsertUser=$conexion2->query("INSERT INTO users (name,active,password,role,user) VALUES('ADMIN','SI','admin','SUPERADMINISTRADOR','admin')");

    $CreateTables=$conexion2->query("CREATE TABLE zones (ID INT(11) AUTO_INCREMENT PRIMARY KEY,description VARCHAR(50) NOT NULL)");

    $InsertUser=$conexion2->query("INSERT INTO zones (ID,description) VALUES('1','NORTE')");
    $InsertUser=$conexion2->query("INSERT INTO zones (ID,description) VALUES('2','SUR')");
    $InsertUser=$conexion2->query("INSERT INTO zones (ID,description) VALUES('3','CENTRO')");
    $InsertUser=$conexion2->query("INSERT INTO zones (ID,description) VALUES('4','ESTE')");
    $InsertUser=$conexion2->query("INSERT INTO zones (ID,description) VALUES('5','OESTE')");

    $CreateTables=$conexion2->query("CREATE TABLE accounts (ID INT(11) AUTO_INCREMENT PRIMARY KEY,code VARCHAR(200) NOT NULL,name VARCHAR(200) NOT NULL,dad VARCHAR (200),type VARCHAR(2) NOT NULL,level INT(5) NOT NULL,active VARCHAR(2) NOT NULL,inflacion VARCHAR(2) NOT NULL,imputable VARCHAR(2) NOT NULL)");

    $CreateTables=$conexion2->query("CREATE TABLE assets (ID INT(11) AUTO_INCREMENT PRIMARY KEY,date DATE NOT NULL,status VARCHAR(100),type INT(11))");

    $CreateTables=$conexion2->query("CREATE TABLE assets_row (ID INT(11) AUTO_INCREMENT PRIMARY KEY,asset_number INT (11),row_number VARCHAR(200) NOT NULL,account VARCHAR(200) NOT NULL,date_op DATE NOT NULL,date_ven DATE NOT NULL,suc VARCHAR(200) NOT NULL,secc VARCHAR(200),concep TEXT NOT NULL,type INT(2) NOT NULL,import VARCHAR(100),comprobante VARCHAR (200),status INT (11))");

    $CreateTables=$conexion2->query("CREATE TABLE balances (ID INT(11) AUTO_INCREMENT PRIMARY KEY,code VARCHAR (200),saldoi VARCHAR(200) NOT NULL,debe VARCHAR(200) NOT NULL,haber VARCHAR(200),type INT (11) NOT NULL,level INT(11) NOT NULL,dad VARCHAR(200),num INT(11))");

    $CreateTables=$conexion2->query("CREATE TABLE lib_diario (ID INT(11) AUTO_INCREMENT PRIMARY KEY,date DATE NOT NULL)");



    $conexion = new PDO('mysql:host=localhost;dbname=sigeusuv3','root','');

    $GetUses=$conexion->prepare("SELECT * FROM uniques_keys WHERE unique_key=:id");
    $GetUses->bindParam(':id',$unique_key);
    $GetUses->execute();
    foreach($GetUses as $Use){
        $uses=$Use['uses']-1;
    }

    $EditUses=$conexion->prepare("UPDATE uniques_keys SET uses=:uses WHERE unique_key=:id");
    $EditUses->bindParam(':id',$unique_key);
    $EditUses->bindParam(':uses',$uses);
    $EditUses->execute();

    header("Location: index.php");
}

$GetCompanyData=$conexion->query("SELECT * from companydata");


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src=”https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js”></script>
    <script type="text/javascript" src="validate_unique_key.js"></script>
    <title>Nueva empresa - SiGeUsu v3</title>
</head>
<body>
    <div id="ingreso">
        <h1>ALTA DE NUEVA EMPRESA</h1>
    </div>
    
    <div id="new-company-form">

        <form action="new-company.php" method="post" autocomplete="off" enctype="multipart/form-data">

            <p class="new-company-text2">Nombre: <input required style="margin-left:80px;" type="text" name="company_name" id="company_name" class="new-company-input"></p>
            <p class="new-company-text2">Logo: <input required style="margin-left:110px;" accept="image/*" type="file" name="company_logo" id="company_logo" class="new-company-input"></p>
            <p class="new-company-text2">Plantilla: 
                    <select name="company_style" class="new-company-input" style="background:white;margin-left:80px;">
                        <option value="skyblue.css">Celeste</option>
                        <option value="yellow.css">Amarillo</option>
                        <option value="orange.css">Naranja</option>
                        <option value="red.css">Morado</option>
                    </select>
            </p>
            <p class="new-company-text2">Sit. frente al IVA: 
                    <select name="IVA_Status" class="new-company-input" style="background:white;">
                        <option value="Consumidor Final">Consumidor Final</option>
                        <option value="Responsable Inscripto">Responsable Inscripto</option>
                        <option value="IVA Sujeto Exento">IVA Sujeto Exento</option>
                        <option value="Responsable Monotributo">Responsable Monotributo</option>
                        <option value="Monotributista Social">Monotributista Social</option>
                    </select>
            </p>
            <p class="new-company-text2">CUIT: <input required style="margin-left:110px;" type="text" name="company_cuit" id="company_cuit" class="new-company-input"></p>
            <p class="new-company-text2">Direccion: <input required style="margin-left:70px;" type="text" name="company_location" id="company_location" class="new-company-input"></p>
            <p class="new-company-text2">Clave única: <input required type="text" style="margin-left:50px;" name="unique_key" id="unique_key" class="new-company-input" onblur="ValidateKey();"></p>

            <input hidden type="submit" class="new-company-submit" name="submit" id="validationOK" value="Crear">

        </form>

        <a href="index.php"><p class="back-text" style="transform:translate(-300px,-100px);">VOLVER A EMPRESAS</p></a>

    </div>

</body>
</html>

