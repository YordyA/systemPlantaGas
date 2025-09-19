<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../despachos/despachosMain.php';
require_once 'vendor/autoload.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$IDTipoDespacho = desencriptar($_GET['IDTipoDesp']);
$consulta = reportDespachoDistribucionXCliente([$del, $hasta, $IDTipoDespacho]);
$arrayDesp = [];
foreach ($consulta as $rowDetalleDesp) {
  if (!isset($arrayDesp[$rowDetalleDesp['IDCliente']])) {
    $arrayDesp[$rowDetalleDesp['IDCliente']] = [
      'RifCedula'       => $rowDetalleDesp['RifCedula'],
      'RazonSocial'     => $rowDetalleDesp['RazonSocial'],
      'DomicilioFiscal' => $rowDetalleDesp['DomicilioFiscal'],
      'detalle'         => [],
    ];
  }

  $arrayDesp[$rowDetalleDesp['IDCliente']]['detalle'][] = [
    'DescripcionProducto' => $rowDetalleDesp['DescripcionProducto'],
    'CantDesp'            => $rowDetalleDesp['CantDesp'],
    'PrecioVentaDespUSD'  => $rowDetalleDesp['PrecioVentaDespUSD'],
    'PrecioVentaDespBS'   => $rowDetalleDesp['PrecioVentaDespBS'],
  ];
}

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;


$style = [
  'textTitulo' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
    'font' => [
      'bold' => true,
      'size' => 14,
    ],
  ],
  'textTituloFont' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
    'font' => [
      'bold' => true,
      'size' => 10,
    ],
  ],
  'textTituloBorder' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'font' => [
      'bold' => true,
      'size' => 10,
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => ['rgb' => '000000'],
      ],
    ],
  ],
  'textSencillo' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      'wrapText' => true,
    ],
    'font' => [
      'bold' => false,
      'size' => 9,
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => ['rgb' => '000000'],
      ],
    ],
  ],
  'textSencilloSinBorder' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'font' => [
      'bold' => false,
      'size' => 10,
    ]
  ],
];

$excel = new SpreadSheet;
$sheet = $excel->getActiveSheet();
$sheet->setTitle('DESP X CLIENTE');

$sheet->getColumnDimension('A')->setWidth(2);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(40);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);

$row = 1;
$sheet->mergeCells('B' . $row . ':G' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($style['textTitulo']);
$sheet->setCellValue('B' . $row++, RAZONSOCIAL);
$sheet->mergeCells('B' . $row . ':G' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($style['textTituloFont']);
$sheet->setCellValue('B' . $row++, DOMICILIOFISCAL);
$sheet->mergeCells('B' . $row . ':G' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($style['textTituloFont']);
$sheet->setCellValue('B' . $row++, 'R.I.F: ' . RIF);
$row++;

$sheet->getStyle('B' . $row . ':G' . $row)->applyFromArray($style['textTituloBorder']);
$sheet->setCellValue('A' . $row, '');
$sheet->setCellValue('B' . $row, 'RIF / CEDULA');
$sheet->setCellValue('C' . $row, 'RAZON SOCIAL');
$sheet->setCellValue('D' . $row, 'DESCRIPCION PRODUCTO');
$sheet->setCellValue('E' . $row, 'PRECIO UNITARIO');
$sheet->setCellValue('F' . $row, 'CANTIDAD DESPACHADA');
$sheet->setCellValue('G' . $row++, 'SUBTOTAL');

$rowInicio = $row;
foreach ($arrayDesp as $IDCliente => $rowDatos) {
  $sheet->setCellValue('B' . $row, $rowDatos['RifCedula']);
  $sheet->setCellValue('C' . $row, $rowDatos['RazonSocial']);

  $rowSub = $row;
  foreach ($rowDatos['detalle'] as $detalleDesp) {
    $sheet->setCellValue('D' . $rowSub, $detalleDesp['DescripcionProducto']);
    $sheet->setCellValue('E' . $rowSub, $detalleDesp['PrecioVentaDespUSD']);
    $sheet->getCell('E' . $rowSub)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('F' . $rowSub, $detalleDesp['CantDesp']);
    $sheet->getCell('F' . $rowSub)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('G' . $rowSub, '=E' . $rowSub . '*F' . $rowSub);
    $sheet->getCell('G' . $rowSub++)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  }
  $sheet->mergeCells('B' . $row . ':B' . ($rowSub - 1));
  $sheet->mergeCells('C' . $row . ':C' . ($rowSub - 1));
  $row = $rowSub;
}
$sheet->getStyle('B' . $rowInicio . ':G' . ($row - 1))->applyFromArray($style['textSencillo']);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Distribucion Despacho por Cliente ' . $del . ' - ' . $hasta . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;