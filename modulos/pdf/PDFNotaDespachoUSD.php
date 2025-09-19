<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../despachos/despachosMain.php';
require_once 'fpdf/fpdf.php';


$IDDespachoResumen = desencriptar($_GET['id']);
$consulta = despachosConsultarDespachoResumidoXID([$IDDespachoResumen])->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF horizontal (apaisado) tamaño carta (279.4 x 215.9 mm)
$pdf = new FPDF('L', 'mm', 'Letter');
$pdf->AddPage();
$pdf->SetFont('Courier', 'B', 16);
$pdf->Image('img/fondo.png', 0, 0, 297, 210, 'png');

// Definir márgenes y espacios
$marginLeft = 10;
$marginTop = 15;
$noteWidth = 125;  // Ancho de cada nota
$spaceBetween = 15; // Espacio entre notas

// Primera nota (lado izquierdo)
createDespachoCopy($pdf, $consulta, $marginLeft, $marginTop, $noteWidth);

// Segunda nota (lado derecho)
createDespachoCopy($pdf, $consulta, $marginLeft + $noteWidth + $spaceBetween, $marginTop, $noteWidth);

// Línea divisoria punteada en el centro (opcional)
$pdf->SetLineWidth(0.2);
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line($marginLeft + $noteWidth + ($spaceBetween/2), $marginTop, 
           $marginLeft + $noteWidth + ($spaceBetween/2), $marginTop + 170);

$pdf->Output();

function createDespachoCopy($pdf, $consulta, $xOffset, $yOffset, $width) {
    $pdf->SetFont('Courier', 'B', 14);
    $pdf->SetXY($xOffset, $yOffset);
    $pdf->MultiCell($width, 7, utf8_decode(RAZONSOCIAL), 0, 'C');
    $pdf->SetFont('Arial', 'I', 6);
    $pdf->SetX($xOffset);
    $pdf->MultiCell($width, 5, utf8_decode(DOMICILIOFISCAL), 0, 'C');
    $pdf->SetFont('Courier', 'B', 9);
    $pdf->SetX($xOffset);
    $pdf->MultiCell($width, 6, utf8_decode('R.I.F: ' . RIF), 0, 'C');
    $pdf->Ln(3);

    $pdf->SetFont('Courier', 'B', 10);
    $pdf->SetX($xOffset);
    $pdf->Cell($width, 6, utf8_decode('FECHA: ' . date('d-m-Y', strtotime($consulta[0]['FechaDesp']))), 0, 'L');
    $pdf->Ln(5);
    $pdf->SetX($xOffset);
    $pdf->Cell($width, 6, utf8_decode('NRO DESPACHO: ' . generarCeros($consulta[0]['NroNota'], 5)), 0, 'L');
    $pdf->Ln(5);
    $pdf->SetX($xOffset);
    $pdf->Cell($width, 6, utf8_decode($consulta[0]['DescripcionTipoDesp']), 0, 'L');

    $pdf->Ln(5);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->SetX($xOffset);
    $pdf->Cell($width, 6, 'DATOS DEL CLIENTE / OBSERVACION', 1, 1, 'C');
    
    $pdf->SetFont('Courier', 'B', 9);
    $pdf->SetX($xOffset);
    $pdf->Cell(30, 6, 'R.I.F / CEDULA:', 1, 0, 'L');
    $pdf->MultiCell($width-30, 6, utf8_decode($consulta[0]['RifCedula']), 1, 'L');
    
    $pdf->SetX($xOffset);
    $pdf->Cell(30, 6, 'RAZON SOCIAL:', 1, 0, 'L');
    $pdf->MultiCell($width-30, 6, utf8_decode($consulta[0]['RazonSocial']), 1, 'L');
    
    $pdf->SetX($xOffset);
    $pdf->Cell(30, 6, 'DOMICILIO FISCAL:', 1, 0, 'L');
    $pdf->MultiCell($width-30, 6, utf8_decode($consulta[0]['DomicilioFiscal']), 1, 'L');
    
    $pdf->SetX($xOffset);
    $pdf->Cell(30, 6, 'OBSERVACION:', 1, 0, 'L');
    $pdf->SetFont('Courier', 'BI', 8);
    $pdf->MultiCell($width-30, 6, utf8_decode($consulta[0]['ObservacionDesp']), 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Courier', 'B', 9);
    $pdf->SetX($xOffset);
    $pdf->Cell($width-60, 6, 'DESCRIPCION', 1, 0, 'C');
    $pdf->Cell(20, 6, 'CANT', 1, 0, 'C');
    $pdf->Cell(20, 6, 'PRECIO', 1, 0, 'C');
    $pdf->Cell(20, 6, 'SUBTOTAL', 1, 1, 'C');

    $montoTotalUSD = 0;
    $pdf->SetFont('Arial', '', 7);
    foreach ($consulta as $row) {
        $subTotal = round($row['CantDesp'] * $row['PrecioVentaDespUSD'], 2);

        $pdf->SetX($xOffset);
        $yInicial = $pdf->GetY();
        $pdf->MultiCell($width-60, 5, utf8_decode($row['DescripcionProducto']), 1);
        $yFinal = $pdf->GetY();
        $altura = $yFinal - $yInicial;
        $pdf->SetY($yInicial);
        $pdf->SetX($xOffset + $width - 60);

        $pdf->Cell(20, $altura, number_format($row['CantDesp'], 2, ',', '.'), 1, 0, 'C');
        $pdf->Cell(20, $altura, number_format($row['PrecioVentaDespUSD'], 2, ',', '.'), 1, 0, 'C');
        $pdf->Cell(20, $altura, number_format($subTotal, 2, ',', '.'), 1, 1, 'C');
        $montoTotalUSD += $subTotal;
    }

    $pdf->SetX($xOffset);
    $pdf->Cell($width-20, 6, 'TOTAL', 1, 0, 'C');
    $pdf->Cell(20, 6, number_format($montoTotalUSD, 2, ',', '.'), 1, 1, 'C');
    $pdf->Ln(8);

    $pdf->SetFont('Courier', 'B', 10);
    $pdf->SetX($xOffset);
    $pdf->Cell($width/2-5, 6, 'ENTREGA CONFORME', 0, 0, 'C');
    $pdf->Cell($width/2-5, 6, 'RECIBE CONFORME', 0, 1, 'C');
    $pdf->SetX($xOffset);
    $pdf->Cell($width/2-10, 6, '', 'B', 0, 'C');
    $pdf->Cell(10, 6, '', 0, 0, 'C');
    $pdf->Cell($width/2-10, 6, '', 'B', 1, 'C');

    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetX($xOffset);
    $pdf->Cell($width/2-5, 6, utf8_decode($consulta[0]['ResponsableDesp']), 0, 0, 'C');
    $pdf->Cell($width/2-5, 6, utf8_decode($consulta[0]['Chofer']), 0, 1, 'C');
    $pdf->SetX($xOffset);
    $pdf->Cell($width/2-5, 6, '', 0, 0, 'C');
    $pdf->Cell($width/2-5, 6, utf8_decode($consulta[0]['ChoferCedula']), 0, 1, 'C');
}
