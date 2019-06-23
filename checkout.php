<?php

require_once "pdo.php";



$movement="HA CERRADO SESION.";
$NewMovement=$conexion->prepare("INSERT INTO auditory (user,date,time,movement) VALUES (:user,:date,:time,:movement)");
$NewMovement->bindParam(':user',$_SESSION['user.name']);
$NewMovement->bindParam(':date',$date);
$NewMovement->bindParam(':time',$time);
$NewMovement->bindParam(':movement',$movement);
$NewMovement->execute();

$db=$_SESSION['company.dbname'];
$name=$_SESSION['company.name'];
$logo=$_SESSION['company.logo'];
$style=$_SESSION['company.style'];

session_destroy();

session_start();

$_SESSION['company.dbname']=$db;
$_SESSION['company.name']=$name;
$_SESSION['company.logo']=$logo;
$_SESSION['company.style']=$style;


header('Location:login.php');

?>