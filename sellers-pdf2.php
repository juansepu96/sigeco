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
        $this->Cell(160,10,$_SESSION['company.name'].' - LISTADO DE VENDEDORES - SiGeCo v1.0',1,0,'C');
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
        $w = array(20, 35, 25, 10, 43, 65);
        // Cabeceras
        $this->Cell($w[2],7,'DNI',1,0,'C');
        $this->Cell($w[4],7,'Nombre',1,0,'C');
        $this->Cell($w[2],7,'F. Nacimiento',1,0,'C');
        $this->Cell($w[2],7,'Telefono',1,0,'C');
        $this->Cell($w[5],7,'Email',1,0,'C');
        $this->Cell($w[3],7,'PDV',1,0,'C');
        $this->Cell($w[2],7,'Descripcion',1,0,'C');
        $this->Cell($w[3],7,'Zona',1,0,'C');
        $this->Cell($w[0],7,'Descripcion',1,0,'C');
        $this->Ln();
        // Datos
        $conexion = new PDO('mysql:host=localhost;dbname='.$_SESSION['company.dbname'],'root','');
        $data=$conexion->prepare($_SESSION['a.imprimir.consulta']);
        $dato=$_SESSION['a.imprmir.dato'];
        $data->bindParam(':texto',$dato);
        $data->execute();

        foreach($data as $row)
         {
        $this->Cell($w[2],6,$row['DNI'],'LR');
        $this->Cell($w[4],6,$row['name'],'LR');
        $this->Cell($w[2],6,date_format(date_create_from_format('Y-m-d', $row['birthdate']), 'd/m/Y'),'LR');
        $this->Cell($w[2],6,$row['phone'],'LR');
        $this->Cell($w[5],6,$row['email'],'LR');
        $this->Cell($w[3],6,$row['pdv_ID'],'LR');
        $PDVDescription=$conexion->prepare("SELECT * FROM pdv WHERE ID=:id");
        $PDVDescription->bindParam(':id',$row['pdv_ID']);
        $PDVDescription->execute();
        foreach ($PDVDescription as $Row){
            $description=substr($Row['description'],0,10);
        }
        $this->Cell($w[2],6,$description,'LR');
        $this->Cell($w[3],6,$row['zone_ID'],'LR');
        $ZoneDescription=$conexion->prepare("SELECT * FROM zones WHERE ID=:id");
        $ZoneDescription->bindParam(':id',$row['zone_ID']);
        $ZoneDescription->execute();
            foreach ($ZoneDescription as $Row){
            $descriptionzone=substr($Row['description'],0,10);
        }
        $this->Cell($w[0],6,$descriptionzone,'LR');
        $this->Ln();
         }
        // Línea de cierre
        $this->Cell(248,0,'','T');
}
}

// Creación del objeto de la clase heredada
$pdf= new PDF('L','mm','A4');
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