<?php

require_once "pdo.php";

$user="SUPERADMINISTRADOR";

function EXPORT_DATABASE($host,$user,$pass,$name,$tables=false, $backup_name=false)
{ 
	set_time_limit(3000); $mysqli = new mysqli($host,$user,$pass,$name); $mysqli->select_db($name); $mysqli->query("SET NAMES 'utf8'");
	$queryTables = $mysqli->query('SHOW TABLES'); while($row = $queryTables->fetch_row()) { $target_tables[] = $row[0]; }	if($tables !== false) { $target_tables = array_intersect( $target_tables, $tables); } 
	$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";
	foreach($target_tables as $table){
		if (empty($table)){ continue; } 
		$result	= $mysqli->query('SELECT * FROM `'.$table.'`');  	$fields_amount=$result->field_count;  $rows_num=$mysqli->affected_rows; 	$res = $mysqli->query('SHOW CREATE TABLE '.$table);	$TableMLine=$res->fetch_row(); 
		$content .= "\n\n".$TableMLine[1].";\n\n";   $TableMLine[1]=str_ireplace('CREATE TABLE `','CREATE TABLE IF NOT EXISTS `',$TableMLine[1]);
		for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
			while($row = $result->fetch_row())	{ //when started (and every after 100 command cycle):
				if ($st_counter%100 == 0 || $st_counter == 0 )	{$content .= "\nINSERT INTO ".$table." VALUES";}
					$content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}  else{$content .= '""';}	   if ($j<($fields_amount-1)){$content.= ',';}   }        $content .=")";
				//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
				if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";";} else {$content .= ",";}	$st_counter=$st_counter+1;
			}
		} $content .="\n\n\n";
	}
	$content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
	$backup_name = $backup_name ? $backup_name : $name.'___('.date('H-i-s').'_'.date('d-m-Y').').sql';
	ob_get_clean(); header('Content-Type: application/octet-stream');  header("Content-Transfer-Encoding: Binary");  header('Content-Length: '. (function_exists('mb_strlen') ? mb_strlen($content, '8bit'): strlen($content)) );    header("Content-disposition: attachment; filename=\"".$backup_name."\""); 
	echo $content; exit;
}

function IMPORT_TABLES($host,$user,$pass,$dbname, $sql_file_OR_content){
    set_time_limit(3000);  
    
	$SQL_CONTENT = (strlen($sql_file_OR_content) > 300 ?  $sql_file_OR_content : file_get_contents($sql_file_OR_content)  );  
    $allLines = explode("\n",$SQL_CONTENT); 
    if(is_int(strpos($SQL_CONTENT,$_SESSION['company.dbname']))){
        $mysqli = new mysqli($host, $user, $pass, $dbname); if (mysqli_connect_errno()){echo "Failed to connect to MySQL: " . mysqli_connect_error();} 
		$zzzzzz = $mysqli->query('SET foreign_key_checks = 0');	        preg_match_all("/\nCREATE TABLE(.*?)\`(.*?)\`/si", "\n". $SQL_CONTENT, $target_tables); foreach ($target_tables[2] as $table){$mysqli->query('DROP TABLE IF EXISTS '.$table);}         $zzzzzz = $mysqli->query('SET foreign_key_checks = 1');    $mysqli->query("SET NAMES 'utf8'");	
	$templine = '';	// Temporary variable, used to store current query
	foreach ($allLines as $line)	{											// Loop through each line
		if (substr($line, 0, 2) != '--' && $line != '') {$templine .= $line; 	// (if it is not a comment..) Add this line to the current segment
			if (substr(trim($line), -1, 1) == ';') {		// If it has a semicolon at the end, it's the end of the query
				if(!$mysqli->query($templine)){ print('Error performing query \'<strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');  }  $templine = ''; // set variable to empty, to start picking up the lines after ";"
			}
		}
    }	return 'Importing finished. Now, Delete the import file.';
    }else{
        echo "<script language='JavaScript'>alert('No se puede restaurar esta copia de seguridad debido a que no corresponde a esta empresa...');</script>"; 
        header("Location: backups.php");
    }
}

$restore='false';


if(isset($_POST['create'])){
    
    $movement="SE REALIZO COPIA DE SEGURIDAD";
    $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement) VALUES (:user,:date,:time,:movement)");
    $NewMovement->bindParam(':user',$user);
    $NewMovement->bindParam(':date',$date);
    $NewMovement->bindParam(':time',$time);
    $NewMovement->bindParam(':movement',$movement);
    $NewMovement->execute();
    
    EXPORT_DATABASE("localhost", "root", "", $dbname);

}

if(isset($_POST['restore'])){
    $restore='true';
}

if(isset($_POST['restore2'])){

    $companyname=$_SESSION['company.name'];
    $bd_name=trim($companyname);
    $bd_name=str_replace(' ','',$bd_name);
    $bd_name=str_replace('/','',$bd_name);
    $bd_name=str_replace('.','',$bd_name);
    $fileTmpPath = $_FILES['company_backup']['tmp_name'];
    $fileName = $_FILES['company_backup']['name'];
    $fileSize = $_FILES['company_backup']['size'];
    $fileType = $_FILES['company_backup']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $newFileName = $bd_name . '.' . $fileExtension;
    $uploadFileDir = '../sigeco/backups/';
    $dest_path = $uploadFileDir . $newFileName;
    move_uploaded_file($fileTmpPath, $dest_path);    

    $SQL_CONTENT = (strlen($dest_path) > 300 ?  $dest_path : file_get_contents($dest_path) );  

    if(is_int(strpos($SQL_CONTENT,$_SESSION['company.dbname']))){    

        $DeleteTable=$conexion->query("DROP TABLE companydata");
        $DeleteTable=$conexion->query("DROP TABLE auditory");
        $DeleteTable=$conexion->query("DROP TABLE pdv");
        $DeleteTable=$conexion->query("DROP TABLE sellers");
        $DeleteTable=$conexion->query("DROP TABLE users");
        $DeleteTable=$conexion->query("DROP TABLE zones");
        $DeleteTable=$conexion->query("DROP TABLE accounts");
        $DeleteTable=$conexion->query("DROP TABLE assets");
        $DeleteTable=$conexion->query("DROP TABLE assets_row");
        $DeleteTable=$conexion->query("DROP TABLE balances");
        $DeleteTable=$conexion->query("DROP TABLE lib_diario");



        IMPORT_TABLES("localhost", "root", "",$dbname, $dest_path);


        $movement="SE RESTAURÓ COPIA DE SEGURIDAD";
        $NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement) VALUES (:user,:date,:time,:movement)");
        $NewMovement->bindParam(':user',$user);
        $NewMovement->bindParam(':date',$date);
        $NewMovement->bindParam(':time',$time);
        $NewMovement->bindParam(':movement',$movement);
        $NewMovement->execute();

        header ("Location: login.php");

        echo "<script language='JavaScript'>alert('COPIA DE SEGURIDAD RESTAURADA CON EXITO');</script>"; 


    }else{
        echo "<script language='JavaScript'>alert('No se puede restaurar esta copia de seguridad debido a que no corresponde a esta empresa...');</script>"; 
    } 
    

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script type="text/javascript" src="animate.js"></script>
    <link rel="stylesheet" href="<?php echo $_SESSION['company.style'];?>">
    <title>Copias de Seguridad - SiGeCo v1.0</title>
</head>
<body>

    <div class="header">
        <img src="/sigeco/logos/<?php echo $_SESSION['company.logo'];?>" class="header-logo">
        <p class="header-text">Empresa: <?php echo $_SESSION['company.name'];?></p>
        <ul class="MainMenu"> 
            <a href="login.php" style="transform:translateY(70px);"><li class="menu-button" >VOLVER</li></a>
        </ul>
    </div>

    <div class="content">
        <div class="table" style="margin-left:80px;width:1200px;">

            <form method="post" action="backups.php" style="padding:50px;padding-bottom:70px;" autocomplete="off" enctype="multipart/form-data">

            <?php if($restore=='false'){ ?>
                 <input type="submit" style="height:100px;color:white; width:85%;transform:translate(100px,20px);" class="menu-button" value="REALIZAR COPIA DE SEGURIDAD" name="create">
                 <input type="submit" style="height:100px;color:white; width:85%;transform:translate(100px,40px);" class="menu-button" value="RESTAURAR COPIA DE SEGURIDAD" name="restore">
            <?php } ?>

            <?php if($restore=='true'){ ?>
                
                <h3 id="alert" style="text-align:center;padding:10px;border-radius:20px;" class="none">RECUERDE QUE ESTA ACCION NO SE PUEDE DESHACER Y QUE SE VAN A REEMPLAZAR TODOS LOS DATOS POR LOS CONTENIDOS EN LA COPIA DE SEGURIDAD<br><br>

                ASEGÚRESE DE ESTAR SELECCIONANDO LA COPIA DE SEGURIDAD CORRECTA</h3><br><br>

                <strong>Seleccione el archivo: <br> <input style="margin-left:110px;" type="file" accept=".sql"  name="company_backup" id="company_backup" class="form-input"></strong>

                <input readonly style="color:white;transform:translate(500px,50px);height:50px;" class="menu-button" name="restore2" id="restore2" value="Restaurar" onclick="if(confirm('ESTA A PUNTO DE INICIAR UNA RESTAURACION. DESEA CONTINUAR?')){
                                this.form.submit();}
                                else{ alert('Operacion Cancelada');}">

            <?php } ?>

            </form>

        </div>
    </div>

    
    
</body>
</html>