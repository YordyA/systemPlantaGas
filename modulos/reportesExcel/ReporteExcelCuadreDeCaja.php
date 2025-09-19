<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../reportesVentas/reportesVentas_main.php';
require_once 'vendor/autoload.php';
require_once '../dependencias.php';
$del = LimpiarCadena($_GET['i']);
$hasta = LimpiarCadena($_GET['f']);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$excel = new Spreadsheet();
$sheet = $excel->getActiveSheet();
$sheet->setTitle('CuadreDeCaja');

// Definir estilos
$styles = [
  'header' => [
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'font' => ['bold' => true, 'size' => 16],
  ],
  'subHeader' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      'wrapText' => true,
    ],
    'font' => ['bold' => true, 'size' => 10],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
  ],
  'data' => [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    'numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1],
  ],
  'totals' => [
    'font' => ['bold' => true],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_DOUBLE]],
    'numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1],
  ],
];

// Ajuste de columnas
$columnas = range('A', 'N');
$anchoColumnas = [1, 10, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15];
foreach ($columnas as $i => $col) {
  $sheet->getColumnDimension($col)->setWidth($anchoColumnas[$i]);
}

// Encabezado
$row = 1;
$sheet->mergeCells("A$row:N$row");
$sheet->getStyle("A$row")->applyFromArray($styles['header']);
$sheet->setCellValue("A$row", $_SESSION['PlantaGas']['Planta']);

$row++;
$sheet->mergeCells("B$row:D$row");
$sheet->setCellValue("B$row", "Fecha: $del al $hasta");

$row++;
$sheet->mergeCells("B$row:D$row");
$sheet->setCellValue("B$row", "Fecha de Emisión: " . date('d-m-Y H:i:s'));

$row++;
$sheet->getStyle("A$row:N$row")->applyFromArray($styles['subHeader']);
$headers = ['', 'NRO CAJA', 'DESCRIPCION', 'TOTAL GENERAL', 'EFECTIVO', 'BIOPAGO', 'TARJETA', 'PAGO M/TRANSF', 'TOTAL PERCIBIDO', 'VUELTOS EFECTIVO', 'VUELTOS PAGO MOVIL', 'CXC', 'DEVOLUCIONES', 'CRUCE DE FACTURAS'];
foreach ($headers as $col => $header) {
  $sheet->setCellValueByColumnAndRow($col + 1, $row, $header);
}

// Datos
$rowInicio = ++$row;
foreach (CadreDeCajaResumido([$del, $hasta, $_SESSION['PlantaGas']['IDPlanta']]) as $detalle) {
  $datos = [
    $detalle['IDCaja'],
    $detalle['DescripcionCaja'],
    round($detalle['total_caja'], 2),
    round($detalle['sum_efectivo_estatus_0']),
    round($detalle['sum_biopago'], 2),
    round($detalle['sum_tarjeta'], 2),
    round($detalle['sum_transferencia'], 2),
    round($detalle['total_caja_precivido'] - $detalle['sum_vuelto'] - $detalle['sum_vuelto_movil'], 2),
    round($detalle['sum_vuelto'], 2),
    round($detalle['sum_vuelto_movil'], 2),
    round($detalle['sum_efectivo_estatus_1'], 2),
    round($detalle['total_anulado'], 2),
    round($detalle['total_cruce'], 2)
  ];
  foreach ($datos as $col => $dato) {
    $cell = $sheet->getCellByColumnAndRow($col + 2, $row);
    $cell->setValue($dato);
    $sheet->getStyle($cell->getCoordinate())->applyFromArray($styles['data']);
  }
  $row++;
}

// Totales
$sheet->mergeCells("B$row:C$row");
$sheet->setCellValue("B$row", "T O T A L E S");
$sheet->getStyle("B$row:N$row")->applyFromArray($styles['totals']);
$letras = range('D', 'N');
foreach ($letras as $col) {
  if ($col === 'D') {
    $sheet->setCellValue("D$row", "=SUM(D$rowInicio:D" . ($row - 1) . ")+L" . ($row) . "-J" . ($row) . "-K" . ($row));
  } else {
    $sheet->setCellValue("$col$row", "=SUM($col$rowInicio:$col" . ($row - 1) . ")");
  }
}


// Salida
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Cuadre De Caja - ' . $_SESSION['PlantaGas']['Planta'] . ' DEL ' . $del . ' HASTA ' . $hasta . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;