<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../reportesVentas/reportesVentas_main.php';
require_once 'vendor/autoload.php';

$del = LimpiarCadena($_GET['d']);
$hasta = LimpiarCadena($_GET['h']);
$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];
$sucursal = $_SESSION['PlantaGas']['Planta'];

$consulta = reporteLibroDeVentas([$del, $hasta, $IDSucursal]);
$datos = [];
foreach ($consulta as $row) {
  if ($row['SerialMaquinaFiscal'] == '') {
    continue;
  }
  $serial = $row['SerialMaquinaFiscal'];

  if (!isset($datos[$serial])) {
    $datos[$serial] = [
      'serialMaquinaFiscal' => $serial,
      'detalle' => []
    ];
  }
  $datos[$serial]['detalle'][] = $row;
}

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

$excel = new SpreadSheet;
$excel->removeSheetByIndex(0);

$h1 = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'font' => [
    'bold' => true,
    'size' => 12,
  ],
];

$h3 = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
  ],
  'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => [
      'argb' => '808080',
    ],
  ],
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
      'color' => ['argb' => 'FF000000'],
    ],
  ],
  'font' => [
    'bold' => true,
    'size' => 8,
  ],
];

$text = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
      'color' => ['argb' => 'FF000000'],
    ],
  ],
  'font' => [
    'bold' => true,
    'size' => 8,
  ],
];

$texto = [
  'font' => [
    'bold' => true,
    'size' => 8,
  ],
];

foreach ($datos as $row) {
  $hojaActiva = $excel->createSheet();
  $hojaActiva->setTitle($row['serialMaquinaFiscal']);

  $fila = 1;
  $hojaActiva->mergeCells('B' . $fila . ':X' . $fila);
  $hojaActiva->getStyle('B' . $fila)->applyFromArray($h1);
  $hojaActiva->setCellValue('B' . $fila, $sucursal);
  $fila++;

  $hojaActiva->getColumnDimension('A')->setWidth(1);
  $hojaActiva->getColumnDimension('B')->setWidth(5);
  $hojaActiva->getColumnDimension('C')->setWidth(8);
  $hojaActiva->getColumnDimension('D')->setWidth(9);
  $hojaActiva->getColumnDimension('E')->setWidth(20);
  $hojaActiva->getColumnDimension('F')->setWidth(10);
  $hojaActiva->getColumnDimension('G')->setWidth(10);
  $hojaActiva->getColumnDimension('H')->setWidth(10);
  $hojaActiva->getColumnDimension('I')->setWidth(10);
  $hojaActiva->getColumnDimension('J')->setWidth(10);
  $hojaActiva->getColumnDimension('K')->setWidth(10);
  $hojaActiva->getColumnDimension('L')->setWidth(10);
  $hojaActiva->getColumnDimension('M')->setWidth(10);
  $hojaActiva->getColumnDimension('N')->setWidth(10);
  $hojaActiva->getColumnDimension('O')->setWidth(10);
  $hojaActiva->getColumnDimension('P')->setWidth(10);
  $hojaActiva->getColumnDimension('Q')->setWidth(10);
  $hojaActiva->getColumnDimension('R')->setWidth(10);
  $hojaActiva->getColumnDimension('S')->setWidth(10);
  $hojaActiva->getColumnDimension('T')->setWidth(10);
  $hojaActiva->getColumnDimension('U')->setWidth(10);
  $hojaActiva->getColumnDimension('V')->setWidth(8);
  $hojaActiva->getColumnDimension('W')->setWidth(8);
  $hojaActiva->getColumnDimension('X')->setWidth(8);


  $hojaActiva->mergeCells('B' . $fila . ':E' . $fila);
  $hojaActiva->setCellValue('B' . $fila, 'FECHA: ' . $del . ' AL ' . $hasta);
  $fila++;
  $hojaActiva->mergeCells('B' . $fila . ':E' . $fila);
  $hojaActiva->setCellValue('B' . $fila, 'FECHA DE EMICION: ' . date('d-m-Y H:i:s'));

  $fila++;
  $hojaActiva->mergeCells('N' . $fila . ':U' . $fila);
  $hojaActiva->getStyle('N' . $fila . ':U' . $fila)->applyFromArray(styleArray: $h3);
  $hojaActiva->setCellValue('N' . $fila, value: 'VENTAS INTERNAS O EXPORTACIONES GRAVADAS');

  $hojaActiva->mergeCells('V' . $fila . ':X' . ($fila + 1));
  $hojaActiva->getStyle('V' . $fila . ':X' . $fila)->applyFromArray(styleArray: $h3);
  $hojaActiva->setCellValue('V' . $fila, 'IVA RETENIDO POR EL COMPRADOR');
  $fila++;
  $hojaActiva->mergeCells('N' . $fila . ':Q' . $fila);
  $hojaActiva->getStyle('N' . $fila . ':U' . $fila)->applyFromArray(styleArray: $h3);
  $hojaActiva->setCellValue('N' . $fila, 'VENTAS A CONTRIBUYENTES');

  $hojaActiva->mergeCells('R' . $fila . ':U' . $fila);
  $hojaActiva->setCellValue('R' . $fila, 'VENTAS NO CONTRIBUYENTES');
  $fila++;
  $hojaActiva->getStyle('B' . $fila . ':X' . $fila)->applyFromArray(styleArray: $h3);
  $hojaActiva->getStyle('B' . $fila . ':X' . $fila)->getAlignment()->setWrapText(true);
  $hojaActiva->setCellValue('B' . $fila, 'OPER. NRO.');
  $hojaActiva->setCellValue('C' . $fila, 'FECHA DE LA FACTURA');
  $hojaActiva->setCellValue('D' . $fila, 'RIF / CEDULA');
  $hojaActiva->setCellValue('E' . $fila, 'RAZON SOCIAL');
  $hojaActiva->setCellValue('F' . $fila, 'Nº PLANILLA EXPORTANCION');
  $hojaActiva->setCellValue('G' . $fila, 'Nº DE FACTURA');
  $hojaActiva->setCellValue('H' . $fila, 'Nº DE CONTROL');
  $hojaActiva->setCellValue('I' . $fila, 'Nº DE NOTA DE DEBITO');
  $hojaActiva->setCellValue('J' . $fila, 'Nº DE NOTA DE CREDITO');
  $hojaActiva->setCellValue('K' . $fila, 'TIPO DE TRANSAC');
  $hojaActiva->setCellValue('L' . $fila, 'Nº FACTURA AFECTADA');
  $hojaActiva->setCellValue('M' . $fila, 'TOTAL  DE VENTAS INCLUYENDO EL IVA');
  $hojaActiva->setCellValue('N' . $fila, 'VENTAS INTERNAS NO GRAVADAS');
  $hojaActiva->setCellValue('O' . $fila, 'BASE IMPONIBLE');
  $hojaActiva->setCellValue('P' . $fila, 'ALICUOTA GENERAL O REDUCIDA');
  $hojaActiva->setCellValue('Q' . $fila, 'IMPUESTO IVA');
  $hojaActiva->setCellValue('R' . $fila, 'VENTAS INTERNAS NO GRAVADA');
  $hojaActiva->setCellValue('S' . $fila, 'BASE IMPONIBLE');
  $hojaActiva->setCellValue('T' . $fila, 'ALICUOTA GENERAL  REDUCIDA');
  $hojaActiva->setCellValue('U' . $fila, 'IMPUESTO IVA');
  $hojaActiva->setCellValue('V' . $fila, 'FECHA DEL COMPROBANTE');
  $hojaActiva->setCellValue('W' . $fila, 'NUMERO COMPROBANTE RETENCION');
  $hojaActiva->setCellValue('X' . $fila, 'IVA RETENIDO');

  $ii = 1;
  $totalFactura = 0;
  $totalExento = 0;
  $totalBaseImponible = 0;
  $totalIva = 0;

  $totalFacturaNot = 0;
  $totalExentoNot = 0;
  $totalBaseImponibleNot = 0;
  $totalIvaNot = 0;
  foreach ($row['detalle'] as $i => $row) {
    $totalFactura += round($row['Estatus'] != 1 ? $row['total'] : 0, 2);
    $fila++;
    $hojaActiva->getStyle('B' . $fila . ':X' . $fila)->applyFromArray(styleArray: $text);
    $hojaActiva->setCellValue('B' . $fila, $ii);
    $hojaActiva->setCellValue('C' . $fila, $row['Fecha']);
    $hojaActiva->setCellValue('D' . $fila, value: $row['Estatus'] != 1 ? $row['RifCliente'] : 'NO APLICA');
    $hojaActiva->setCellValue('E' . $fila, $row['Estatus'] != 1 ? $row['NombreCliente'] : 'ANULADA');
    $hojaActiva->setCellValue('F' . $fila, '');
    $hojaActiva->setCellValue('G' . $fila, $row['NFacturaFiscal']);
    $hojaActiva->setCellValue('H' . $fila, $row['SerialMaquinaFiscal']);
    $hojaActiva->setCellValue('I' . $fila, '');
    $hojaActiva->setCellValue('J' . $fila, $row['Estatus'] == 1 ? $row['NNotaCredito'] : '');
    $hojaActiva->setCellValue('K' . $fila, '');
    $hojaActiva->setCellValue('L' . $fila, $row['Estatus'] == 1 ? $row['NFacturaFiscal'] : '');
    $hojaActiva->setCellValue('M' . $fila, $row['total']);

    if (validarRifCedula($row['RifCliente'])) {
      $totalExento += round($row['Estatus'] != 1 ? $row['Exento'] : 0, 2);
      $totalBaseImponible += round($row['Estatus'] != 1 ? $row['Gravado'] : 0, 2);
      $totalIva += round($row['Estatus'] != 1 ? $row['Iva'] : 0, 2);

      $hojaActiva->setCellValue('N' . $fila, $row['Estatus'] != 1 ? $row['Exento'] : '');
      $hojaActiva->setCellValue('O' . $fila, value: $row['Estatus'] != 1 ? $row['Gravado'] : '');
      $hojaActiva->setCellValue('P' . $fila, '16%');
      $hojaActiva->setCellValue('Q' . $fila, value: $row['Estatus'] != 1 ? $row['Iva'] : '');
    } else {
      $hojaActiva->setCellValue('N' . $fila, $row['Estatus'] != 1 ? 0 : '');
      $hojaActiva->setCellValue('O' . $fila, value: $row['Estatus'] != 1 ? 0 : '');
      $hojaActiva->setCellValue('P' . $fila, $row['Estatus'] != 1 ? '16%' : '');
      $hojaActiva->setCellValue('Q' . $fila, value: $row['Estatus'] != 1 ? 0 : '');
    }


    if (!validarRifCedula($row['RifCliente'])) {
      $totalExentoNot += round($row['Estatus'] != 1 ? $row['Exento'] : 0, 2);
      $totalBaseImponibleNot += round($row['Estatus'] != 1 ? $row['Gravado'] : 0, 2);
      $totalIvaNot += round($row['Estatus'] != 1 ? $row['Iva'] : 0, 2);

      $hojaActiva->setCellValue('R' . $fila, $row['Estatus'] != 1 ? $row['Exento'] : '');
      $hojaActiva->setCellValue('S' . $fila, $row['Estatus'] != 1 ? $row['Gravado'] : '');
      $hojaActiva->setCellValue('T' . $fila, '16%');
      $hojaActiva->setCellValue('U' . $fila, $row['Estatus'] != 1 ? $row['Iva'] : '');
    } else {
      $hojaActiva->setCellValue('R' . $fila, $row['Estatus'] != 1 ? 0 : '');
      $hojaActiva->setCellValue('S' . $fila, $row['Estatus'] != 1 ? 0 : '');
      $hojaActiva->setCellValue('T' . $fila, $row['Estatus'] != 1 ? '16%' : '');
      $hojaActiva->setCellValue('U' . $fila, $row['Estatus'] != 1 ? 0 : '');
    }

    $hojaActiva->setCellValue('V' . $fila, '');
    $hojaActiva->setCellValue('W' . $fila, '');
    $hojaActiva->setCellValue('X' . $fila, '');
    $ii++;
  }
  $fila += 2;
  $hojaActiva->getStyle('L' . $fila . ':O' . $fila)->applyFromArray(styleArray: $text);
  $hojaActiva->setCellValue('L' . $fila, 'TOTALES');
  $hojaActiva->setCellValue('M' . $fila, $totalFactura);
  $hojaActiva->setCellValue('N' . $fila, $totalExento);
  $hojaActiva->setCellValue('O' . $fila, $totalBaseImponible);

  $hojaActiva->getStyle('Q' . $fila . ':U' . $fila)->applyFromArray(styleArray: $text);
  $hojaActiva->setCellValue('Q' . $fila, $totalIva);
  $hojaActiva->setCellValue('R' . $fila, $totalExentoNot);
  $hojaActiva->setCellValue('S' . $fila, $totalBaseImponibleNot);
  $hojaActiva->setCellValue('U' . $fila, $totalIvaNot);
  $hojaActiva->getStyle('X' . $fila . ':X' . $fila)->applyFromArray(styleArray: $text);
  $hojaActiva->setCellValue('X' . $fila, 0);

  $fila += 2;
  $hojaActiva->getStyle('M' . $fila . ':Q' . $fila)->applyFromArray(styleArray: $text);
  $hojaActiva->mergeCells('M' . $fila . ':N' . $fila);
  $hojaActiva->setCellValue('M' . $fila, 'BASE IMPONIBLE');
  $hojaActiva->mergeCells('O' . $fila . ':P' . $fila);
  $hojaActiva->setCellValue('O' . $fila, 'DEBITO FISCAL');
  $hojaActiva->setCellValue('Q' . $fila, 'IVA RETENIDO');

  $fila++;
  $hojaActiva->getStyle('F' . $fila . ':Q' . ($fila + 5))->applyFromArray(styleArray: $texto);
  $hojaActiva->getStyle('M' . $fila . ':Q' . ($fila + 5))->applyFromArray(styleArray: $text);
  $hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
  $hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS INTERNAS NO GRAVADAS');
  $hojaActiva->setCellValue('M' . $fila, '40');
  $hojaActiva->setCellValue('N' . $fila, $totalExento + $totalExentoNot);
  $fila++;
  $hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
  $hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS DE EXPORTACION');
  $hojaActiva->setCellValue('M' . $fila, '41');
  $fila++;
  $hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
  $hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS INTERNAS AFECTADAS SOLO ALICUOTA GENERAL');
  $hojaActiva->setCellValue('M' . $fila, '42');
  $hojaActiva->setCellValue('N' . $fila, ($totalBaseImponible + $totalBaseImponibleNot));
  $hojaActiva->setCellValue('O' . $fila, '43');
  $hojaActiva->setCellValue('P' . $fila, ($totalIva + $totalIvaNot));
  $fila++;
  $hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
  $hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS INTERNAS AFECTADAS SOLO ALICUOTA GENERAL + ADICIONAL');
  $hojaActiva->setCellValue('M' . $fila, '442');
  $hojaActiva->setCellValue('O' . $fila, '452');
  $fila++;
  $hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
  $hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS INTERNAS AFECTADAS EN ALICUOTA REDUCIDA');
  $hojaActiva->setCellValue('M' . $fila, '443');
  $hojaActiva->setCellValue('O' . $fila, '453');
  $fila++;
  $hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
  $hojaActiva->setCellValue('F' . $fila, 'TOTAL');
  $hojaActiva->setCellValue('M' . $fila, '46');
  $hojaActiva->setCellValue('O' . $fila, '47');
  $hojaActiva->setCellValue('N' . $fila, ($totalBaseImponible + $totalBaseImponibleNot) + ($totalExento + $totalExentoNot));
  $hojaActiva->setCellValue('P' . $fila, ($totalIva + $totalIvaNot));
}

$hojaActiva = $excel->createSheet();
$hojaActiva->setTitle('Consolidado');

$fila = 1;
$hojaActiva->mergeCells('B' . $fila . ':X' . $fila);
$hojaActiva->getStyle('B' . $fila)->applyFromArray(styleArray: $h1);
$hojaActiva->setCellValue('B' . $fila, $sucursal);
$fila++;

$hojaActiva->getColumnDimension('A')->setWidth(1);
$hojaActiva->getColumnDimension('B')->setWidth(5);
$hojaActiva->getColumnDimension('C')->setWidth(8);
$hojaActiva->getColumnDimension('D')->setWidth(9);
$hojaActiva->getColumnDimension('E')->setWidth(20);
$hojaActiva->getColumnDimension('F')->setWidth(10);
$hojaActiva->getColumnDimension('G')->setWidth(10);
$hojaActiva->getColumnDimension('H')->setWidth(10);
$hojaActiva->getColumnDimension('I')->setWidth(10);
$hojaActiva->getColumnDimension('J')->setWidth(10);
$hojaActiva->getColumnDimension('K')->setWidth(10);
$hojaActiva->getColumnDimension('L')->setWidth(10);
$hojaActiva->getColumnDimension('M')->setWidth(10);
$hojaActiva->getColumnDimension('N')->setWidth(10);
$hojaActiva->getColumnDimension('O')->setWidth(10);
$hojaActiva->getColumnDimension('P')->setWidth(10);
$hojaActiva->getColumnDimension('Q')->setWidth(10);
$hojaActiva->getColumnDimension('R')->setWidth(10);
$hojaActiva->getColumnDimension('S')->setWidth(10);
$hojaActiva->getColumnDimension('T')->setWidth(10);
$hojaActiva->getColumnDimension('U')->setWidth(10);
$hojaActiva->getColumnDimension('V')->setWidth(8);
$hojaActiva->getColumnDimension('W')->setWidth(8);
$hojaActiva->getColumnDimension('X')->setWidth(8);

$hojaActiva->mergeCells('B' . $fila . ':E' . $fila);
$hojaActiva->setCellValue('B' . $fila, 'FECHA: ' . $del . ' AL ' . $hasta);
$fila++;
$hojaActiva->mergeCells('B' . $fila . ':E' . $fila);
$hojaActiva->setCellValue('B' . $fila, 'FECHA DE EMICION: ' . date('d-m-Y H:i:s'));

$fila++;
$hojaActiva->mergeCells('N' . $fila . ':U' . $fila);
$hojaActiva->getStyle('N' . $fila . ':U' . $fila)->applyFromArray(styleArray: $h3);
$hojaActiva->setCellValue('N' . $fila, value: 'VENTAS INTERNAS O EXPORTACIONES GRAVADAS');

$hojaActiva->mergeCells('V' . $fila . ':X' . ($fila + 1));
$hojaActiva->getStyle('V' . $fila . ':X' . $fila)->applyFromArray(styleArray: $h3);
$hojaActiva->setCellValue('V' . $fila, 'IVA RETENIDO POR EL COMPRADOR');
$fila++;
$hojaActiva->mergeCells('N' . $fila . ':Q' . $fila);
$hojaActiva->getStyle('N' . $fila . ':U' . $fila)->applyFromArray(styleArray: $h3);
$hojaActiva->setCellValue('N' . $fila, 'VENTAS A CONTRIBUYENTES');

$hojaActiva->mergeCells('R' . $fila . ':U' . $fila);
$hojaActiva->setCellValue('R' . $fila, 'VENTAS NO CONTRIBUYENTES');
$fila++;
$hojaActiva->getStyle('B' . $fila . ':X' . $fila)->applyFromArray(styleArray: $h3);
$hojaActiva->getStyle('B' . $fila . ':X' . $fila)->getAlignment()->setWrapText(true);
$hojaActiva->setCellValue('B' . $fila, 'OPER. NRO.');
$hojaActiva->setCellValue('C' . $fila, 'FECHA DE LA FACTURA');
$hojaActiva->setCellValue('D' . $fila, 'RIF / CEDULA');
$hojaActiva->setCellValue('E' . $fila, 'RAZON SOCIAL');
$hojaActiva->setCellValue('F' . $fila, 'Nº PLANILLA EXPORTANCION');
$hojaActiva->setCellValue('G' . $fila, 'Nº DE FACTURA');
$hojaActiva->setCellValue('H' . $fila, 'Nº DE CONTROL');
$hojaActiva->setCellValue('I' . $fila, 'Nº DE NOTA DE DEBITO');
$hojaActiva->setCellValue('J' . $fila, 'Nº DE NOTA DE CREDITO');
$hojaActiva->setCellValue('K' . $fila, 'TIPO DE TRANSAC');
$hojaActiva->setCellValue('L' . $fila, 'Nº FACTURA AFECTADA');
$hojaActiva->setCellValue('M' . $fila, 'TOTAL  DE VENTAS INCLUYENDO EL IVA');
$hojaActiva->setCellValue('N' . $fila, 'VENTAS INTERNAS NO GRAVADAS');
$hojaActiva->setCellValue('O' . $fila, 'BASE IMPONIBLE');
$hojaActiva->setCellValue('P' . $fila, 'ALICUOTA GENERAL O REDUCIDA');
$hojaActiva->setCellValue('Q' . $fila, 'IMPUESTO IVA');
$hojaActiva->setCellValue('R' . $fila, 'VENTAS INTERNAS NO GRAVADA');
$hojaActiva->setCellValue('S' . $fila, 'BASE IMPONIBLE');
$hojaActiva->setCellValue('T' . $fila, 'ALICUOTA GENERAL  REDUCIDA');
$hojaActiva->setCellValue('U' . $fila, 'IMPUESTO IVA');
$hojaActiva->setCellValue('V' . $fila, 'FECHA DEL COMPROBANTE');
$hojaActiva->setCellValue('W' . $fila, 'NUMERO COMPROBANTE RETENCION');
$hojaActiva->setCellValue('X' . $fila, 'IVA RETENIDO');

$cant = 1;
$totalFactura = 0;
$totalExento = 0;
$totalBaseImponible = 0;
$totalIva = 0;

$totalFacturaNot = 0;
$totalExentoNot = 0;
$totalBaseImponibleNot = 0;
$totalIvaNot = 0;
foreach ($consulta as $i => $row) {
  $totalFactura += round($row['Estatus'] != 1 ? $row['total'] : 0, 2);
  $fila++;
  $hojaActiva->getStyle('B' . $fila . ':X' . $fila)->applyFromArray(styleArray: $text);
  $hojaActiva->setCellValue('B' . $fila, $cant);
  $hojaActiva->setCellValue('C' . $fila, $row['Fecha']);
  $hojaActiva->setCellValue('D' . $fila, value: $row['Estatus'] != 1 ? $row['RifCliente'] : 'NO APLICA');
  $hojaActiva->setCellValue('E' . $fila, $row['Estatus'] != 1 ? $row['NombreCliente'] : 'ANULADA');
  $hojaActiva->setCellValue('F' . $fila, '');
  $hojaActiva->setCellValue('G' . $fila, $row['NFacturaFiscal']);
  $hojaActiva->setCellValue('H' . $fila, $row['SerialMaquinaFiscal']);
  $hojaActiva->setCellValue('I' . $fila, '');
  $hojaActiva->setCellValue('J' . $fila, $row['Estatus'] == 1 ? $row['NNotaCredito'] : '');
  $hojaActiva->setCellValue('K' . $fila, '');
  $hojaActiva->setCellValue('L' . $fila, $row['Estatus'] == 1 ? $row['NFacturaFiscal'] : '');
  $hojaActiva->setCellValue('M' . $fila, $row['total']);

  if (validarRifCedula($row['RifCliente'])) {
    $totalExento += round($row['Estatus'] != 1 ? $row['Exento'] : 0, 2);
    $totalBaseImponible += round($row['Estatus'] != 1 ? $row['Gravado'] : 0, 2);
    $totalIva += round($row['Estatus'] != 1 ? $row['Iva'] : 0, 2);

    $hojaActiva->setCellValue('N' . $fila, $row['Estatus'] != 1 ? $row['Exento'] : '');
    $hojaActiva->setCellValue('O' . $fila, value: $row['Estatus'] != 1 ? $row['Gravado'] : '');
    $hojaActiva->setCellValue('P' . $fila, '16%');
    $hojaActiva->setCellValue('Q' . $fila, value: $row['Estatus'] != 1 ? $row['Iva'] : '');
  } else {
    $hojaActiva->setCellValue('N' . $fila, $row['Estatus'] != 1 ? 0 : '');
    $hojaActiva->setCellValue('O' . $fila, value: $row['Estatus'] != 1 ? 0 : '');
    $hojaActiva->setCellValue('P' . $fila, $row['Estatus'] != 1 ? '16%' : '');
    $hojaActiva->setCellValue('Q' . $fila, value: $row['Estatus'] != 1 ? 0 : '');
  }


  if (!validarRifCedula($row['RifCliente'])) {
    $totalExentoNot += round($row['Estatus'] != 1 ? $row['Exento'] : 0, 2);
    $totalBaseImponibleNot += round($row['Estatus'] != 1 ? $row['Gravado'] : 0, 2);
    $totalIvaNot += round($row['Estatus'] != 1 ? $row['Iva'] : 0, 2);

    $hojaActiva->setCellValue('R' . $fila, $row['Estatus'] != 1 ? $row['Exento'] : '');
    $hojaActiva->setCellValue('S' . $fila, $row['Estatus'] != 1 ? $row['Gravado'] : '');
    $hojaActiva->setCellValue('T' . $fila, '16%');
    $hojaActiva->setCellValue('U' . $fila, $row['Estatus'] != 1 ? $row['Iva'] : '');
  } else {
    $hojaActiva->setCellValue('R' . $fila, $row['Estatus'] != 1 ? 0 : '');
    $hojaActiva->setCellValue('S' . $fila, $row['Estatus'] != 1 ? 0 : '');
    $hojaActiva->setCellValue('T' . $fila, $row['Estatus'] != 1 ? '16%' : '');
    $hojaActiva->setCellValue('U' . $fila, $row['Estatus'] != 1 ? 0 : '');
  }

  $hojaActiva->setCellValue('V' . $fila, '');
  $hojaActiva->setCellValue('W' . $fila, '');
  $hojaActiva->setCellValue('X' . $fila, '');
  $cant++;
}

$fila += 2;
$hojaActiva->getStyle('L' . $fila . ':O' . $fila)->applyFromArray(styleArray: $text);
$hojaActiva->setCellValue('L' . $fila, 'TOTALES');
$hojaActiva->setCellValue('M' . $fila, $totalFactura);
$hojaActiva->setCellValue('N' . $fila, $totalExento);
$hojaActiva->setCellValue('O' . $fila, $totalBaseImponible);

$hojaActiva->getStyle('Q' . $fila . ':U' . $fila)->applyFromArray(styleArray: $text);
$hojaActiva->setCellValue('Q' . $fila, $totalIva);
$hojaActiva->setCellValue('R' . $fila, $totalExentoNot);
$hojaActiva->setCellValue('S' . $fila, $totalBaseImponibleNot);
$hojaActiva->setCellValue('U' . $fila, $totalIvaNot);
$hojaActiva->getStyle('X' . $fila . ':X' . $fila)->applyFromArray(styleArray: $text);
$hojaActiva->setCellValue('X' . $fila, 0);

$fila += 2;
$hojaActiva->getStyle('M' . $fila . ':Q' . $fila)->applyFromArray(styleArray: $text);
$hojaActiva->mergeCells('M' . $fila . ':N' . $fila);
$hojaActiva->setCellValue('M' . $fila, 'BASE IMPONIBLE');
$hojaActiva->mergeCells('O' . $fila . ':P' . $fila);
$hojaActiva->setCellValue('O' . $fila, 'DEBITO FISCAL');
$hojaActiva->setCellValue('Q' . $fila, 'IVA RETENIDO');

$fila++;
$hojaActiva->getStyle('F' . $fila . ':Q' . ($fila + 5))->applyFromArray(styleArray: $texto);
$hojaActiva->getStyle('M' . $fila . ':Q' . ($fila + 5))->applyFromArray(styleArray: $text);
$hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
$hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS INTERNAS NO GRAVADAS');
$hojaActiva->setCellValue('M' . $fila, '40');
$hojaActiva->setCellValue('N' . $fila, $totalExento + $totalExentoNot);
$fila++;
$hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
$hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS DE EXPORTACION');
$hojaActiva->setCellValue('M' . $fila, '41');
$fila++;
$hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
$hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS INTERNAS AFECTADAS SOLO ALICUOTA GENERAL');
$hojaActiva->setCellValue('M' . $fila, '42');
$hojaActiva->setCellValue('N' . $fila, ($totalBaseImponible + $totalBaseImponibleNot));
$hojaActiva->setCellValue('O' . $fila, '43');
$hojaActiva->setCellValue('P' . $fila, ($totalIva + $totalIvaNot));
$fila++;
$hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
$hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS INTERNAS AFECTADAS SOLO ALICUOTA GENERAL + ADICIONAL');
$hojaActiva->setCellValue('M' . $fila, '442');
$hojaActiva->setCellValue('O' . $fila, '452');
$fila++;
$hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
$hojaActiva->setCellValue('F' . $fila, 'TOTAL VENTAS INTERNAS AFECTADAS EN ALICUOTA REDUCIDA');
$hojaActiva->setCellValue('M' . $fila, '443');
$hojaActiva->setCellValue('O' . $fila, '453');
$fila++;
$hojaActiva->mergeCells('F' . $fila . ':J' . $fila);
$hojaActiva->setCellValue('F' . $fila, 'TOTAL');
$hojaActiva->setCellValue('M' . $fila, '46');
$hojaActiva->setCellValue('O' . $fila, '47');
$hojaActiva->setCellValue('N' . $fila, ($totalBaseImponible + $totalBaseImponibleNot) + ($totalExento + $totalExentoNot));
$hojaActiva->setCellValue('P' . $fila, ($totalIva + $totalIvaNot));


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="LIBRO DE VENTA ' . $sucursal . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
