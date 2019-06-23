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
        $this->Cell(160,10,$_SESSION['company.name'].' - AUDITORIAS - SiGeUsu v3',1,0,'C');
        // Salto de línea
        $this->Cell(60);
        #$this->Cell(120,10,'FECHA: '.date('d/m/Y'),1,0,'C');
        #$this->Cell(120,10,'HORA: '.date('H:m:s'),1,0,'C');
        $this->Ln(20);
        // Anchuras de las columnas
        $w = array(20, 30, 21, 10, 35, 140, 65);
        // Cabeceras
        $this->Cell(130);
        $this->Cell(0,10,'FECHA:'.date('d/m/Y'),0,1);
        $this->Cell(130);
        $this->Cell(0,10,'HORA: '.date('H:m:s'),0,1);
        $this->Cell($w[4],7,'USUARIO',1,0,'C');
        $this->Cell($w[2],7,'FECHA',1,0,'C');
        $this->Cell($w[2],7,'HORA',1,0,'C');
        $this->Cell($w[6],7,'MOVIMIENTO',1,0,'C');
        $this->Cell($w[5],7,'DETALLES',1,0,'C');
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
        // Anchuras de las columnas
        $w = array(20, 30, 21, 10, 35, 140, 65);
        
        // Datos
        $conexion = new PDO('mysql:host=localhost;dbname='.$_SESSION['company.dbname'],'root','');
        $data=$conexion->prepare($_SESSION['a.imprimir.2']);
        $dato=$_SESSION['a.imprimir.date'];
        $dato2=$_SESSION['a.imprimir.date2'];
        $dato3=$_SESSION['a.imprimir.date3'];
        $data->bindParam(':user',$dato);
        $data->bindParam(':fechadesde',$dato2);
        $data->bindParam(':fechahasta',$dato3);
        $data->execute();

        foreach($data as $row)
         {
        $this->Cell($w[4],6,$row['user'],'LR');
        $this->Cell($w[2],6,date_format(date_create_from_format('Y-m-d', $row['date']), 'd/m/Y'),'LR');
        $this->Cell($w[2],6,$row['time'],'LR');
        $this->Cell($w[6],6,$row['movement'],'LR');
        $this->Cell($w[5],6,substr($row['description'],0,60),'LR');
        $this->Ln();
         }
        // Línea de cierre
        $this->Cell(282,0,'','T');
}
}

// Creación del objeto de la clase heredada
$_SESSION['filtre']='false';

$pdf= new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Tabla();
$pdf->Output();
?>