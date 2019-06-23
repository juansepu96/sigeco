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

$GetCompanyData=$conexion->query("SELECT * from general_companydata");

if(isset($_POST['submit'])){
    $companyID=$_POST['ID'];
    $GetCompany=$conexion->prepare("SELECT * from general_companydata WHERE ID=:id");
    $GetCompany->bindParam(':id',$companyID);
    $GetCompany->execute();
    foreach($GetCompany as $Company) {
        $_SESSION['company.name']=$Company['name'];
        
        $_SESSION['company.dbname']=$Company['dbname'];
        
        $_SESSION['company.logo']=$Company['logo'];
        
        $_SESSION['company.style']=$Company['style'];
        
    }
  header('Location: index2.php');
   
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Seleccione su empresa - SiGeUsu v3</title>
</head>
<body>
    <div id="ingreso">
        <h1>Bienvenido a SiGeUsu v3</h1>
        <h2>Seleccione la empresa para comenzar a operar</h2>    
        <a href="new-company.php" class="new-company-button"><img src="/sigeusu v3/new-company.png" class="new-company"><h4 class="new-company-text">NUEVA EMPRESA</h4></a>
        <div id="companies">   

            <?php foreach($GetCompanyData as $Company) { ?>
                <div id="company" class="company">
                    <form id="principal-form" name="principal-form" method="post" action="index.php">
                            <img src="/sigeusu v3/logos/<?php echo $Company['logo'];?>" class="logo">
                            <h3><?php echo $Company['name'];?></h3>
                            <input hidden type="text" id="ID" name="ID" value="<?php echo $Company['ID'];?>"></td>
                            <input class="form-submit" type="submit" id="submit" name="submit" value="INGRESAR">
                     </form>
                </div>                
                
            <?php } ?> 

        <div>           
    </div>
    
</body>
</html>

