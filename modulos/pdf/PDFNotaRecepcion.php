<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../recepcion/recepcionMain.php';
require_once 'fpdf/fpdf.php';


$idventa = desencriptar(limpiarCadena($_GET['id']));
$consulta = ListaDeRepcionNota([$idventa])->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Courier', 'B', 16);
$pdf->Image('img/fondo.png', 7, 25, 200, 160, 'png');
if ($consulta[0]['EstadoRecepcion'] == 0) {
    $pdf->Image('img/despachoAnulado.png', 15, 1, 190, 0, 'PNG', '');
}
$pdf->setY(6);
$pdf->setX(10);
$pdf->MultiCell(190, 8, utf8_decode(RAZONSOCIAL), 0, 'C');
$pdf->SetFont('Arial', 'I', 6);
$pdf->MultiCell(190, 6, utf8_decode(DOMICILIOFISCAL), 0, 'C');
$pdf->SetFont('Courier', 'B', 10);
$pdf->MultiCell(190, 6, utf8_decode('R.I.F: ' . RIF), 0, 'C');
$pdf->Ln(5);

$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(190, 6, utf8_decode('FECHA: ' . date('d-m-Y', strtotime($consulta[0]['FechaRecepcionAnalisis']))), 0, 'L');
$pdf->Cell(190, 6, utf8_decode('NRO DE RECEPCION: ' . generarCeros($consulta[0]['NroRecepcion'], 5)), 0, 'L');
$pdf->Cell(190, 6, utf8_decode('TIPO DE PRODUCTOR: ' . $consulta[0]['Nombre']), 0, 'L');
$pdf->SetFont('Courier', 'B', 7);
$pdf->Cell(190, 6, utf8_decode('NOTA: TODOS NUESTROS PRECIOS ESTAN REFERENCIADOS EN MONEDA EXTRANJERA (USD). '), 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(190, 6, 'DATOS DEL CLIENTE / OBSERVACION', 1, 1, 'C');
$pdf->SetFont('Courier', '', 10);
$pdf->Cell(40, 5, 'R.I.F / CEDULA:', 1, 0, 'L');
$pdf->MultiCell(150, 5, utf8_decode($consulta[0]['RifCedula']), 1, 'L');
$pdf->Cell(40, 5, 'RAZON SOCIAL:', 1, 0, 'L');
$pdf->MultiCell(150, 5, utf8_decode($consulta[0]['RazonSocial']), 1, 'L');
$pdf->Cell(40, 5, 'DOMICILIO FISCAL:', 1, 0, 'L');
$pdf->MultiCell(150, 5, utf8_decode($consulta[0]['Comunidad'] . ', ' . $consulta[0]['Parroquia'] . ', ' . $consulta[0]['Municipio'] ), 1, 'L');
$pdf->Cell(40, 5, 'OBSERVACION:', 1, 0, 'L');
$pdf->SetFont('Courier', 'BI', 10);
$pdf->MultiCell(150, 5, utf8_decode(''), 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(190, 6, 'ANALISIS DE LABORATORIO', 1, 1, 'C');
$pdf->SetFont('Courier', '', 7);
$pdf->Cell(47.5, 5, 'KG RECIBIDOS: ' . $consulta[0]['KgRecibidos'], 1, 0, 'L');
$pdf->Cell(47.5, 5, '% DE DESCUENTO: ' . $consulta[0]['TotalDescuento'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'KG DESCONTADOS: ' . $consulta[0]['TotalKgDescontados'], 1, 0, 'L');
$pdf->Cell(47.5, 5, 'AFLATOXINAS: ' . $consulta[0]['Aflatoxina'], 1, 1, 'L');

$pdf->Cell(47.5, 5, 'HUMEDAD: ' . $consulta[0]['Humedad'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'IMPUREZAS: ' . $consulta[0]['Impurezas'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'INFESTACION: ' . $consulta[0]['Infestacion'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'SEMILLAS OBJETABLES: ' . $consulta[0]['SemillasObjetables'] . '%', 1, 1, 'L');
$pdf->Cell(47.5, 5,  utf8_decode('G.D POR GERMINACION: ' . $consulta[0]['Germinacion'] . '%'), 1, 0, 'L');
$pdf->Cell(47.5, 5, 'G.D POR CALOR: ' . $consulta[0]['Calor'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'G.D POR INSECTOS ' . $consulta[0]['Insectos'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'G.D MICROORGANISMOS: ' . $consulta[0]['Microorganismos'] . '%', 1, 1, 'L');

$pdf->Cell(47.5, 5, 'G. AMILACEOS: ' . $consulta[0]['Amilaceos'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'G. CRISTALIZADOS: ' . $consulta[0]['GranosCristalizados'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'G. PARTIDOS: ' . $consulta[0]['GranosPartidos'] . '%', 1, 0, 'L');
$pdf->Cell(47.5, 5, 'A. SENSORIAL: ' . $consulta[0]['Sensorial'] . '%', 1, 1, 'L');



$pdf->Ln(5);

$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(112, 7, 'DESCRIPCION', 1, 0, 'C');
$pdf->Cell(26, 7, 'CANT', 1, 0, 'C');
$pdf->Cell(26, 7, 'PRECIO', 1, 0, 'C');
$pdf->Cell(26, 7, 'SUBTOTAL', 1, 1, 'C');

$montoTotalUSD = 0;
$pdf->SetFont('Courier', '', 8);
foreach (ListaDeRepcionNota([$idventa]) as $row) {
    $subTotal = round($row['Cantidad'] * $row['Precio'], 2);
    $pdf->SetX(10);
    $yInicial = $pdf->GetY();
    $pdf->MultiCell(112, 6, utf8_decode($row['DescripcionProducto']), 1);
    $yFinal = $pdf->GetY();
    $altura = $yFinal - $yInicial;
    $pdf->SetY($yInicial);
    $pdf->SetX(122);

    $pdf->Cell(26, $altura, number_format($row['Cantidad'], 2, ',', '.'), 1, 0, 'C');
    $pdf->Cell(26, $altura, number_format($row['Precio'], 2, ',', '.'), 1, 0, 'C');
    $pdf->Cell(26, $altura, number_format($subTotal, 2, ',', '.'), 1, 1, 'C');
    $montoTotalUSD += $subTotal;
}
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(164, 6, 'TOTAL', 1, 0, 'C');
$pdf->Cell(26, 6, number_format($montoTotalUSD, 2, ',', '.'), 1, 1, 'C');
$pdf->Ln(5);

if ($consulta[0]['TipoRecepcion'] != 1) {
    $DeudaResumen = ConsultarDeuda([$consulta[0]['IDRecepcion']])->fetch(PDO::FETCH_ASSOC);
    $pdf->Cell(47.5, 5, 'HA FINANCIADAS: ' . $DeudaResumen['HAFinanciadas'], 1, 0, 'L');
    $pdf->Cell(47.5, 5, 'SALDO DEUDOR: ' . $DeudaResumen['SaldoDeudor'], 1, 0, 'L');
    $pdf->Cell(47.5, 5, 'SALDO ABONADO: ' . $montoTotalUSD, 1, 0, 'L');
    $pdf->Cell(47.5, 5, 'DEUDA/EXEDENTE ' . $montoTotalUSD - $DeudaResumen['SaldoDeudor'], 1, 1, 'L');
    $pdf->Ln(10);
}


$pdf->SetFont('Courier', 'B', 12);
$pdf->Cell(95, 8, 'ENTREGA CONFORME', 0, 0, 'C');
$pdf->Cell(95, 8, 'RECIBE CONFORME', 0, 1, 'C');
$pdf->Cell(90, 8, '', 'B', 0, 'C');
$pdf->Cell(10, 8, '', 0, 0, 'C');
$pdf->Cell(90, 8, '', 'B', 1, 'C');

$pdf->ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(95, 8, utf8_decode($consulta[0]['Responsable']), 0, 0, 'C');
$pdf->Cell(95, 8, utf8_decode($consulta[0]['RazonSocial']), 0, 1, 'C');
$pdf->Output();
