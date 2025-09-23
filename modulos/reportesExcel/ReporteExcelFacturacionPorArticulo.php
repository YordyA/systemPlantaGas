<?php
//// todo LIBRERIA DE EXCEL
require_once __DIR__ . "/vendor/autoload.php";
//// TODO OTROS MODULOS
require_once '../main.php';
require_once '../reportesVentas/reportesVentas_main.php';
require_once '../sessionStart.php';






$del = $_GET['i'];
$hasta = $_GET['f'];

$fecha1 = date("d-m-Y", strtotime($del));
$fecha2 = date("d-m-Y", strtotime($hasta));

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$excel = new SpreadSheet;

$hoja_activa = $excel->getActiveSheet();
$hoja_activa->setTitle('CuadreDeCaja');


$styleArray = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'font' => [
    'bold' => true,
    'size' => 16,
  ],
];

// Unir celdas para combinar filas
$hoja_activa->mergeCells('A1:E1');

$hoja_activa->getStyle('A1')->applyFromArray($styleArray);
$hoja_activa->setCellValue('A1', $_SESSION['PlantaGas']['Planta']);


// Establecer el ancho de las columnas
$hoja_activa->getColumnDimension('A')->setWidth(20);
$hoja_activa->getColumnDimension('B')->setWidth(20);
$hoja_activa->getColumnDimension('C')->setWidth(20);
$hoja_activa->getColumnDimension('D')->setWidth(20);
$hoja_activa->getColumnDimension('E')->setWidth(20);

// Otras celdas y configuraciones
$hoja_activa->setCellValue('A2', 'Fecha: ' . $fecha1 . ' al ' . $fecha2);
$hoja_activa->setCellValue('A3', 'Fecha de Emision: ' . date('d-m-Y H:i:s'));
$hoja_activa->setCellValue('A3', 'NOTA: Los Precios presentados No Incluyen el IVA ');


$styleArrayy = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'font' => [
    'bold' => true,
    'size' => 10,
  ],
];


$hoja_activa->getStyle('A5:J5')->applyFromArray($styleArrayy);
$hoja_activa->setCellValue('A5', 'CODIGO');
$hoja_activa->setCellValue('B5', 'PARTIDA CONTABLE');
$hoja_activa->setCellValue('C5', 'ARTICULO');
$hoja_activa->setCellValue('D5', 'CANTIDAD');
$hoja_activa->setCellValue('E5', 'MONTO BS');
$hoja_activa->setCellValue('F5', 'MONTO $');


$totalventas = 0;
$totaldolares = 0;
$totalbs = 0;
$totaliva = 0;
$exento = 0;
$base = 0;
$fila = 6;

foreach (FacturacionPorArticulo([$del, $hasta, $_SESSION['PlantaGas']['IDPlanta']]) as $row) {

  if ($row['IDAlicuota'] == 0.00) {

    $totalventasiniva = $row['TotalBs'];
    $exento += $row['TotalBs'];
    $totaldolares +=  $totalventasinivaUSD = $row['TotalUSD'];
  } else if ($row['IDAlicuota'] == 0.16) {

    $totalventasiniva = $row['TotalBs'] / 1.16;
    $totaliva += ($row['TotalBs'] / 1.16) * 0.16;
    $base += $row['TotalBs'] / 1.16;
    $totaldolares += $totalventasinivaUSD = $row['TotalUSD'] / 1.16;
  }

  $totalventas += $row['TotalCantidad'];

  $totalbs += $totalventasiniva;
  $hoja_activa->setCellValue('A' . $fila, $row['CodigoArticulo']);
  $hoja_activa->setCellValue('B' . $fila, '');
  $hoja_activa->setCellValue('C' . $fila, $row['DescripcionTipo'] . ' '. $row['DescripcionProducto'] );
  $hoja_activa->setCellValue('D' . $fila, round($row['TotalCantidad'], 3));
  $hoja_activa->setCellValue('E' . $fila, round($totalventasiniva, 2));
  $hoja_activa->setCellValue('F' . $fila, round($totalventasinivaUSD, 2));
  $fila++;
}





$hoja_activa->setCellValue('D' . $fila, round($totalventas, 2));
$hoja_activa->setCellValue('E' . $fila, round($totalbs, 2));
$hoja_activa->setCellValue('F' . $fila, round($totaldolares, 2));

$fila++;
$hoja_activa->setCellValue('D' . $fila, 'EXENTO');
$hoja_activa->setCellValue('E' . $fila, round($exento, 2));

$fila++;
$hoja_activa->setCellValue('D' . $fila, 'BASE IMPONIBLE');
$hoja_activa->setCellValue('E' . $fila, round($base, 2));

$fila++;
$hoja_activa->setCellValue('D' . $fila, 'IVA (16%)');
$hoja_activa->setCellValue('E' . $fila, round($totaliva, 2));

$fila++;
$hoja_activa->setCellValue('D' . $fila, 'TOTAL');
$hoja_activa->setCellValue('E' . $fila, round($totaliva + $totalbs, 2));



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Facturacion Por Articulo.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
