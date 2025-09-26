<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once 'fpdf/fpdf.php';
$NroVenta = desencriptar($_GET['id']);
$consulta = conexion()->prepare('SELECT
  *
FROM
  facturasresumen
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN productos ON facturasdetalle.IDProducto = productos.IDProducto
  INNER JOIN tipo_productos on productos.IDTipoProducto = tipo_productos.IDTipo
  INNER JOIN facturasmediopago ON facturasresumen.IDResumenVenta = facturasmediopago.NVenta
WHERE
  facturasresumen.IDResumenVenta = ?
  AND facturasresumen.NFacturaFiscal = 0');
$consulta->execute([$NroVenta]);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
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

$pdf->setY(30);
$pdf->setX(10);
$pdf->SetFont('Courier', 'B', 12);
$pdf->Cell(15, 7, "FECHA: " . date('d-m-Y', strtotime($consulta[0]['FechaHora'])), 0, 1);

$pdf->SetFont('Courier', 'B', 12);
$pdf->setY(36);
$pdf->setX(10);
$pdf->Cell(40, 7, "NOTA DE ENTREGA: " . $consulta[0]['NVentaResumen'], 0, 1);


$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(50);
$pdf->setX(10);
$pdf->Cell(23, 8, "RIF/CEDULA: " . $consulta[0]['RifCliente'], 0, 1);

$pdf->setY(55);
$pdf->setX(10);
$pdf->Cell(28.7, 8, "RAZON SOCIAL: " . $consulta[0]['NombreCliente'], 0, 1);

$pdf->Image('../../logo.png', 140, 25, 50, 25, 'PNG', '');
$pdf->setY(50);
$pdf->setX(150);
$pdf->Cell(6, $textypos, "RIF: G-200166037");
//$pdf->Image('img/FONDO.png', 17, 45, 180, 150, 'PNG', '');

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
  $pdf->Cell(60, 7, $row['DescripcionTipo']. ' ' . $row['DescripcionProducto'], 1, 0, "C");
  $pdf->Cell(30, 7, number_format($row['Cantidad'], 3), 1, 0, "C");
  $pdf->Cell(33, 7, number_format($row['Precio'], 2), 1, 0, "C");
  $pdf->Cell(34, 7, "Bs " .  number_format(0, 2), 1, 0, "C");
  $pdf->Cell(34, 7, "$ " . number_format(0, 2), 1, 1, "C");
}
$pdf->SetX(133);
$pdf->Cell(34, 7, 'Bs ' . number_format($total, 2, ',', '.'), 1, 0, "C");
$pdf->Cell(34, 7, '$ ' . number_format(0, 2, ',', '.'), 1, 1, "C");

$pdf->ln(20);
$pdf->SetX(15);
$pdf->SetFont('Courier', 'B', 12);
$pdf->Cell(92.50, 8, 'ENTREGA CONFORME', 0, 0, 'C');
$pdf->Cell(92.50, 8, 'RECIBE CONFORME', 0, 1, 'C');
$pdf->Cell(90, 8, '', 'B', 0, 'C');
$pdf->Cell(10, 8, '', 0, 0, 'C');
$pdf->Cell(90, 8, '', 'B', 1, 'C');


$pdf->Output();
