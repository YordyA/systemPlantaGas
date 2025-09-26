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
WHERE
  facturasresumen.IDResumenVenta = ?
  AND facturasresumen.NFacturaFiscal = 0');
$consulta->execute([$NroVenta]);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

// Calcular altura dinámica según cantidad de ítems
$alturaPorItem = 6;
$alturaBase = 40;
$cantidadItems = count($consulta);
$alturaTotal = $alturaBase + ($alturaPorItem * $cantidadItems);

// Crear PDF para 58mm de ancho
$pdf = new FPDF('P', 'mm', array(58, $alturaTotal));
$pdf->AddPage();
$pdf->SetMargins(3, 3, 3);

// Título
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(52, 5, 'NOTA DE DESPACHO DE GAS', 0, 1, 'C');
$pdf->Ln(2);

// Número de venta
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(52, 4, 'No. VENTA: ' . $consulta[0]['NVentaResumen'], 0, 1, 'L');
$pdf->Ln(1);

// Nombre del cliente
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(52, 4, 'CLIENTE: ' . utf8_decode($consulta[0]['NombreCliente']), 0, 1, 'L');
$pdf->Ln(2);

// Encabezado de ítems
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(30, 4, 'DESCRIPCION', 0, 0, 'L');
$pdf->Cell(10, 4, 'CANTIDAD CILINDROS', 0, 0, 'R');
$pdf->Cell(12, 4, 'CANTIDAD GAS', 0, 1, 'R');

// Línea separadora
$pdf->Cell(52, 1, '', 'B', 1);
$pdf->Ln(1);

// Ítems
$pdf->SetFont('Arial', '', 7);
$total = 0;

foreach ($consulta as $row) {
    $descripcion = $row['DescripcionTipo'] . ' ' . $row['DescripcionProducto'];
    
    // Acortar descripción si es muy larga
    if (strlen($descripcion) > 25) {
        $descripcion = substr($descripcion, 0, 22) . '...';
    }
    
    $pdf->Cell(30, 4, utf8_decode($descripcion), 0, 0, 'L');
    $pdf->Cell(10, 4, number_format($row['Cantidad'], 3), 0, 0, 'R');
    $pdf->Cell(12, 4, number_format($row['Cantidad'] * $row['Cantidad'] , 2), 0, 1, 'R');
    
    $total += $row['Cantidad'] * $row['Precio'];
    $totalGas += $row['Cantidad'] * $row['Precio'];
}

// Línea separadora
$pdf->Cell(52, 1, '', 'B', 1);
$pdf->Ln(1);

// Total
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(30, 5, 'TOTAL:', 0, 0, 'L');
$pdf->Cell(22, 5, number_format($total, 2, ',', '.'), 0, 1, 'R');

// Pie del documento
$pdf->Ln(3);
$pdf->SetFont('Arial', 'I', 6);
$pdf->Cell(52, 3, date('d/m/Y H:i'), 0, 1, 'C');
$pdf->Output('I', 'NotaDespacho_' . $consulta[0]['NVentaResumen'] . '.pdf');