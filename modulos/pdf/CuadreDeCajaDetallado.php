<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once 'fpdf/fpdf.php';
require_once '../reportesVentas/reportesVentas_main.php';

$del = $_GET['i'];
$hasta = $_GET['f'];

$fecha1 = date("d-m-Y", strtotime($del));
$fecha2 = date("d-m-Y", strtotime($hasta));

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$textypos = 5;
$pdf->setY(12);
$pdf->setX(65);
// Agregamos los datos de la empresa
$pdf->Cell(5, $textypos, "" . utf8_decode($_SESSION['PlantaGas']['Planta']));
$pdf->SetFont('Arial', '', 8);
$pdf->setY(16);
$pdf->setX(62);
$pdf->Cell(5, $textypos, utf8_decode(""));

$pdf->SetFont('Arial', 'B', 18);
$pdf->setY(55);
$pdf->setX(125);
$pdf->Cell(5, $textypos, "Cuadre de Caja Detallado");
$pdf->SetFont('Arial', 'B', 11);
$pdf->setY(40);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "Fecha: " . $fecha1 . " al " . $fecha2);

$pdf->SetFont('Arial', '', 11);
$pdf->setY(45);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "Fecha de Emision: " . date('d-m-Y H:i:s'));

$pdf->SetFont('Arial', 'B', 11);
//$pdf->Image('img/logo.png', 200, 20, 50, 25, 'PNG', '');
$pdf->setY(40);
$pdf->setX(210);
$pdf->setTextColor(255, 255, 255);
$pdf->Cell(6, $textypos, "Rif: ");

// Nombre tablas
//$pdf->Image('img/FONDO.png', 17, 20, 265, 150, 'PNG', '');


// La cabecera de la tabla (en azulito sobre fondo rojo)
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetXY(10, 70);
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell(15, 5, "Fecha", 0, 0, "C");
$pdf->Cell(15, 5, "Caja", 0, 0, "C");
$pdf->Cell(15, 5, "Nro Venta", 0, 0, "C");
$pdf->Cell(15, 5, "Nro Fiscal", 0, 0, "C");
$pdf->Cell(105, 5, "Cliente", 0, 0, "C");
$pdf->Cell(15, 5, "Total Factura", 0, 0, "C");
$pdf->Cell(15, 5, "Efectivo", 0, 0, "C");
$pdf->Cell(15, 5, "BioPago", 0, 0, "C");
$pdf->Cell(15, 5, "Tarjeta", 0, 0, "C");
$pdf->Cell(15, 5, "PagoM/Transf", 0, 0, "C");
$pdf->Cell(15, 5, "Vuelto", 0, 0, "C");
$pdf->Cell(15, 5, "CxC", 0, 0, "C");
$pdf->Cell(15, 5, "Dev", 0, 1, "C");



$pdf->SetFont('Arial', '', 6);
// Los datos (en negro)
$pdf->SetTextColor(0, 0, 0);
$totalGeneral = 0;
$totalEfectivo = 0;
$totalBiopago = 0;
$totaltarjeta = 0;
$totaltransferencia = 0;
$totalpercivido = 0;
$totalCxC = 0;
$totaldevoculciones = 0;





foreach (CuadreDeCajaDetallado([$del,$hasta,$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  if($row['Estatus'] == 1){

    $cxc=$row['Efectivo'];

  }else{

    $cxc=0.00;
  }
  if ($row['Estatus'] == 0) {

   $Efectivo=$row['Efectivo'];

  }else{

    $Efectivo=0.00;
  }
  if ($row['Estatus'] == 2) {

    $dev=$row['BioPago']+$row['Tarjeta']+$row['Efectivo']+$row['Transferencia'];

  }else{

    $dev=0.00;
  }

  $totalGeneral +=  $row['Transferencia']+$row['Tarjeta']+ $row['BioPago']+ $Efectivo;
  $totalEfectivo +=     $Efectivo;
  $totalBiopago += $row['BioPago'];
  $totaltarjeta += $row['Tarjeta'];
  $totaltransferencia += $row['Transferencia'];
  $totalCxC +=  $cxc;
  $totaldevoculciones += $dev;

  $pdf->SetX(10);
  $pdf->Cell(15, 5, $row['Fecha'], 0, 0, "C");
  $pdf->Cell(15, 5, $row['IDCaja'], 0, 0, "C");
  $pdf->Cell(15, 5, $row['NVenta'], 0, 0, "C");
  $pdf->Cell(15, 5, $row['NFacturaFiscal'], 0, 0, "C");
  $pdf->Cell(105, 5, $row['RifCliente']. ' - ' .$row['NombreCliente'], 0, 0, "L");
  $pdf->Cell(15, 5, number_format($row['TotalFactura'],2), 0, 0, "C");
  $pdf->Cell(15, 5, number_format( $Efectivo,2), 0, 0, "C");
  $pdf->Cell(15, 5, number_format($row['BioPago'],2), 0, 0, "C");
  $pdf->Cell(15, 5, number_format($row['Tarjeta'],2), 0, 0, "C");
  $pdf->Cell(15, 5, number_format($row['Transferencia'],2), 0, 0, "C");
  $pdf->Cell(15, 5, number_format($row['Vuelto'],2), 0, 0, "C");
  $pdf->Cell(15, 5, number_format($cxc,2), 0, 0, "C");
  $pdf->Cell(15, 5, number_format($dev,2), 0, 1, "C");
}

$pdf->SetFont('Arial', 'B', 6);
$pdf->SetX(72);
$pdf->Cell(215, 0, "", 1, 1, "C");
$pdf->SetX(175);
$pdf->Cell(15, 5, number_format($totalGeneral, 2) , 0, 0, "C");
$pdf->Cell(15, 5, number_format($totalEfectivo, 2) , 0, 0, "C");
$pdf->Cell(15, 5, number_format($totalBiopago, 2) , 0, 0, "C");
$pdf->Cell(15, 5, number_format($totaltarjeta, 2) , 0, 0, "C");
$pdf->Cell(15, 5, number_format($totaltransferencia, 2) , 0, 0, "C");
$pdf->Cell(15, 5, number_format($totalpercivido, 2) , 0, 0, "C");
$pdf->Cell(15, 5, number_format($totalCxC, 2) , 0, 0, "C");
$pdf->Cell(15, 5, number_format($totaldevoculciones, 2) , 0, 1, "C");


// El documento enviado al navegador
$pdf->Output();
