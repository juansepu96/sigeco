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
        $this->Cell(160,10,$_SESSION['company.name'].' - LISTADO DE PDV - SiGeUsu v3',1,0,'C');
        // Salto de línea
        $this->Cell(60);
        #$this->Cell(120,10,'FECHA: '.date('d/m/Y'),1,0,'C');
        #$this->Cell(120,10,'HORA: '.date('H:m:s'),1,0,'C');
        $this->Ln(20);


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
        // Anchuras de las columnas
        $w = array(20, 35, 25, 10, 43, 150);
        // Cabeceras
        $this->Cell($w[2],7,'ID',1,0,'C');
        $this->Cell($w[5],7,'Descripcion',1,0,'C');
        $this->Ln();
        // Datos
        $conexion = new PDO('mysql:host=localhost;dbname='.$_SESSION['company.dbname'],'root','');
        $data=$conexion->query("SELECT * from pdv");
        foreach($data as $row)
         {
        $this->Cell($w[2],6,$row['ID'],'LR');
        $this->Cell($w[5],6,$row['description'],'LR');
        $this->Ln();
         }
        // Línea de cierre
        $this->Cell(175,0,'','T');
}
}

// Creación del objeto de la clase heredada
$pdf= new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Cell(130);
$pdf->Cell(0,10,'FECHA:'.date('d/m/Y'),0,1);
$pdf->Cell(130);
$pdf->Cell(0,10,'HORA: '.date('H:m:s'),0,1);
$pdf->Tabla();
$pdf->Output();
?>