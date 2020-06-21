<?php

session_start();


require('fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo
        $this->Image('logos/'.$_SESSION['company.logo'],10,8,33);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(60);
        // Título
        $this->Ln(10);
        $this->Cell(60);
        $this->Cell(160,10,$_SESSION['company.name'].' - PLAN DE CUENTAS - SiGeCo v1.0',1,0,'C');
        // Salto de línea
        $this->Cell(60);
        #$this->Cell(120,10,'FECHA: '.date('d/m/Y'),1,0,'C');
        #$this->Cell(120,10,'HORA: '.date('H:m:s'),1,0,'C');
        $this->Ln(20);
        // Anchuras de las columnas
        $w = array(20, 30, 21, 10, 35, 140, 65);
        // Cabeceras
        $this->Ln(5);
        $this->Cell(0.1);
        $this->Cell($w[2],7,'Nro.',1,0,'C');
        $this->Cell($w[6],7,'CODIGO',1,0,'C');
        $this->Cell($w[5],7,'NOMBRE',1,0,'C');
        $this->Cell($w[2],7,'IMP.',1,0,'C');
        $this->Cell($w[2],7,'AJ. INF.',1,0,'C');
        $this->Ln();

    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
    }

    // Una tabla
    function Tabla()
    {
        $w = array(20, 30, 21, 10, 35, 140, 65);
        // Datos
        $conexion = new PDO('mysql:host=localhost;dbname='.$_SESSION['company.dbname'],'root','');
        $data=$conexion->query("SELECT * from accounts ORDER BY code ASC");
        foreach($data as $row)
         {
        $this->Cell($w[2],6,$row['ID'],'LR');
        $this->Cell($w[6],6,$row['code'],'LR');
        $this->Cell($w[5],6,$row['name'],'LR');
        ($row['imputable']==0) ? $imputable='NO' : $imputable='SI';
        $this->Cell($w[2],6,$imputable,'LR');
        ($row['inflacion']==0) ? $inflacion='NO' : $inflacion='SI';
        $this->Cell($w[2],6,substr($inflacion,0,60),'LR');
        $this->Ln();
         }
        // Línea de cierre
        $this->Cell(275,0,'','T');
}
}

// Creación del objeto de la clase heredada


$pdf= new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->Tabla();
$pdf->Output();
?>