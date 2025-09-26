<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'fpdf/fpdf.php';
require_once '../cobrar/CobrarMain.php';
require_once '../dependencias.php';
$consulta = consultarDonacionPorNroDonacion([desencriptar($_GET['id'])])->fetchAll(PDO::FETCH_ASSOC);
$nameRetiros = array(
  'DONACIÓN',
  'AUTO CONSUMO',
  'DONACIÓN TRABAJADOR'
);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 15);
$textypos = 5;
$pdf->setY(12);
$pdf->setX(65);
$pdf->Cell(5, $textypos, utf8_decode(RAZONSOCIAL));
$pdf->SetFont('Arial', '', 8);
$pdf->setY(19);
$pdf->setX(30);
$pdf->Cell(5, $textypos, utf8_decode(DOMICILIOFISCAL));
$pdf->SetFont('Courier', 'B', 12);
$pdf->setY(25);
$pdf->setX(10);
$pdf->Cell(5, $textypos, utf8_decode($_SESSION['PlantaGas']['Planta']));

$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(35);
$pdf->setX(10);
$pdf->Cell(5, $textypos, utf8_decode($nameRetiros[$consulta[0]['TipoConsumo']]) . ' NRO ' . $consulta[0]['NDonacion']);
$pdf->setY(40);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "FECHA: " . date('d-m-Y', strtotime($consulta[0]['Fecha'])));

$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(45);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "DOCUMENTO DE IDENTIFICACION: " . $consulta[0]['RifCliente']);

$pdf->setY(50);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "RAZON SOCIAL: " . utf8_decode($consulta[0]['NombreCliente']));

$pdf->Image('../../logo.png', 140, 25, 50, 25, 'PNG', '');
$pdf->setY(50);
$pdf->setX(150);
$pdf->Cell(6, $textypos, RIF);
//$pdf->Image('img/FONDO.png', 17, 45, 180, 150, 'PNG', '');

// La cabecera de la tabla (en azulito sobre fondo rojo)
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, 65);
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell(60, 7, utf8_decode("DESCRIPCION"), 1, 0, "C");
$pdf->Cell(30, 7, utf8_decode("CANTIDAD"), 1, 0, "C");
$pdf->Cell(33, 7, "PRECIO BS", 1, 0, "C");
$pdf->Cell(34, 7, "MONTO TOTAL BS", 1, 0, "C");
$pdf->Cell(34, 7, "MONTO TOTAL USD", 1, 1, "C");

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetX(10);
$total = 0;
foreach ($consulta as $row) {
  $total += $row['SubTotal'];
  $pdf->Cell(60, 7, $row['DescripcionTipo']. ' ' . $row['DescripcionProducto'], 1, 0, "C");
  $pdf->Cell(30, 7, number_format($row['Cantidad'], 3), 1, 0, "C");
  $pdf->Cell(33, 7, number_format($row['Precio'], 2), 1, 0, "C");
  $pdf->Cell(34, 7, "Bs " .  number_format($row['SubTotal'], 2), 1, 0, "C");
  $pdf->Cell(34, 7, "$ " . number_format($row['SubTotal'] / $row['TasaRefUSD'], 2), 1, 1, "C");
}

$pdf->SetFont('Arial', 'B', 10);

$pdf->SetX(133);
$pdf->Cell(34, 5, "Bs " . number_format($total, 2), 1, 0, "C");
$pdf->Cell(34, 5, "$ " . number_format($total / $consulta[0]['TasaRefUSD'], 2), 1, 1, "C");


$pdf->Output();
