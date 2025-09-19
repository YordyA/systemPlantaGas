<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../despachos/despachosMain.php';
require_once 'fpdf/fpdf.php';

$IDDespachoResumen = desencriptar($_GET['id']);
$consulta = despachosConsultarDespachoResumidoXID([$IDDespachoResumen])->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Courier', 'B', 16);
$pdf->Image('img/fondo.png', 0, 12, 210, 297, 'png');
$pdf->setY(6);
$pdf->setX(10);
$pdf->MultiCell(190, 8, utf8_decode(RAZONSOCIAL), 0, 'C');
$pdf->SetFont('Arial', 'I', 6);
$pdf->MultiCell(190, 6, utf8_decode(DOMICILIOFISCAL), 0, 'C');
$pdf->SetFont('Courier', 'B', 10);
$pdf->MultiCell(190, 6, utf8_decode('R.I.F: ' . RIF), 0, 'C');
$pdf->Ln(5);

$pdf->SetFont('Courier', 'B', 12);
$pdf->Cell(190, 6, utf8_decode('FECHA: ' . date('d-m-Y', strtotime($consulta[0]['FechaDesp']))), 0, 'L');
$pdf->Cell(190, 6, utf8_decode('NRO DESPACHO: ' . generarCeros($consulta[0]['NroNota'], 5)), 0, 'L');
$pdf->Cell(190, 6, utf8_decode($consulta[0]['DescripcionTipoDesp']), 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Courier', 'B', 12);
$pdf->Cell(190, 6, 'DATOS DEL CLIENTE / OBSERVACION', 1, 1, 'C');
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(40, 7, 'R.I.F / CEDULA:', 1, 0, 'L');
$pdf->MultiCell(150, 7, utf8_decode($consulta[0]['RifCedula']), 1, 'L');
$pdf->Cell(40, 7, 'RAZON SOCIAL:', 1, 0, 'L');
$pdf->MultiCell(150, 7, utf8_decode($consulta[0]['RazonSocial']), 1, 'L');
$pdf->Cell(40, 7, 'DOMICILIO FISCAL:', 1, 0, 'L');
$pdf->MultiCell(150, 7, utf8_decode($consulta[0]['DomicilioFiscal']), 1, 'L');
$pdf->Cell(40, 7, 'OBSERVACION:', 1, 0, 'L');
$pdf->SetFont('Courier', 'BI', 10);
$pdf->MultiCell(150, 7, utf8_decode($consulta[0]['ObservacionDesp']), 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(112, 7, 'DESCRIPCION', 1, 0, 'C');
$pdf->Cell(26, 7, 'CANT', 1, 0, 'C');
$pdf->Cell(26, 7, 'PRECIO', 1, 0, 'C');
$pdf->Cell(26, 7, 'SUBTOTAL', 1, 1, 'C');

$montoExento = 0;
$baseImponible = 0;

$pdf->SetFont('Arial', 'B', 8);
foreach ($consulta as $row) {
  $subTotal = round($row['CantDesp'] * $row['PrecioVentaDespBS'], 2);
  if ($row['ValorAlicuotaDesp'] == 0) {
    $montoExento += round($subTotal, 2);
  } else {
    $baseImponible += round($subTotal, 2);
  }

  $pdf->SetX(10);
  $yInicial = $pdf->GetY();
  $pdf->MultiCell(112, 6, utf8_decode($row['DescripcionProducto']), 1);
  $yFinal = $pdf->GetY();
  $altura = $yFinal - $yInicial;
  $pdf->SetY($yInicial);
  $pdf->SetX(122);

  $pdf->Cell(26, $altura, number_format($row['CantDesp'], 2, ',', '.'), 1, 0, 'C');
  $pdf->Cell(26, $altura, number_format($row['PrecioVentaDespBS'], 2, ',', '.'), 1, 0, 'C');
  $pdf->Cell(26, $altura, number_format($subTotal, 2, ',', '.'), 1, 1, 'C');
}

$pdf->Cell(112, 6, '', 0, 0, 'C');
$pdf->Cell(52, 6, 'MONTO EXENTO', 1, 0, 'C');
$pdf->Cell(26, 6, number_format($montoExento, 2, ',', '.'), 1, 1, 'C');
$pdf->Cell(112, 6, '', 0, 0, 'C');
$pdf->Cell(52, 6, 'BASE IMPONIBLE', 1, 0, 'C');
$pdf->Cell(26, 6, number_format($baseImponible, 2, ',', '.'), 1, 1, 'C');
$pdf->Cell(112, 6, '', 0, 0, 'C');
$pdf->Cell(52, 6, '(IVA)', 1, 0, 'C');
$pdf->Cell(26, 6, number_format($baseImponible * 0.16, 2, ',', '.'), 1, 1, 'C');
$pdf->Cell(112, 6, '', 0, 0, 'C');
$pdf->Cell(52, 6, 'TOTAL A PAGAR', 1, 0, 'C');
$pdf->Cell(26, 6, number_format($montoExento + $baseImponible + ($baseImponible * 0.16), 2, ',', '.'), 1, 1, 'C');
$pdf->Ln(15);

$pdf->SetFont('Courier', 'B', 12);
$pdf->Cell(95, 8, 'ENTREGA CONFORME', 0, 0, 'C');
$pdf->Cell(95, 8, 'RECIBE CONFORME', 0, 1, 'C');
$pdf->Cell(90, 8, '', 'B', 0, 'C');
$pdf->Cell(10, 8, '', 0, 0, 'C');
$pdf->Cell(90, 8, '', 'B', 1, 'C');

$pdf->ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(95, 8, utf8_decode($consulta[0]['ResponsableDesp']), 0, 0, 'C');
$pdf->Cell(95, 8, utf8_decode($consulta[0]['Chofer']), 0, 1, 'C');
$pdf->Cell(95, 8, '', 0, 0, 'C');
$pdf->Cell(95, 8, utf8_decode($consulta[0]['ChoferCedula']), 0, 1, 'C');
$pdf->Output();