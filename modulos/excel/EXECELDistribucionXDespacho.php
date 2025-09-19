<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../despachos/despachosMain.php';
require_once 'vendor/autoload.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$IDTipoDespacho = desencriptar($_GET['IDTipoDesp']);
$consulta = reportDespachoDistribucionXDespacho([$del, $hasta, $IDTipoDespacho]);
$arrayDesp = [];
foreach ($consulta as $rowDetalleDesp) {
  $idCliente = $rowDetalleDesp['IDCliente'];
  $idDespacho = $rowDetalleDesp['IDDespachoResumen'];

  if (!isset($arrayDesp[$idCliente])) {
    $arrayDesp[$idCliente] = [
      'RifCedula'       => $rowDetalleDesp['RifCedula'],
      'RazonSocial'     => $rowDetalleDesp['RazonSocial'],
      'DomicilioFiscal' => $rowDetalleDesp['DomicilioFiscal'],
      'despacho'        => []
    ];
  }

  if (!isset($arrayDesp[$idCliente]['despacho'][$idDespacho])) {
    $arrayDesp[$idCliente]['despacho'][$idDespacho] = [
      'FechaDespacho' => $rowDetalleDesp['FechaDesp'],
      'NroNota'   => generarCeros($rowDetalleDesp['NroNota'], 5),
      'detalle'       => []
    ];
  }

  $arrayDesp[$idCliente]['despacho'][$idDespacho]['detalle'][] = [
    'DescripcionProducto' => $rowDetalleDesp['DescripcionProducto'],
    'CantDesp'            => $rowDetalleDesp['CantDesp'],
    'PrecioVentaDespUSD'  => $rowDetalleDesp['PrecioVentaDespUSD'],
    'PrecioVentaDespBS'   => $rowDetalleDesp['PrecioVentaDespBS']
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
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(40);
$sheet->getColumnDimension('G')->setWidth(15);

$row = 1;
$sheet->mergeCells('B' . $row . ':I' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($style['textTitulo']);
$sheet->setCellValue('B' . $row++, RAZONSOCIAL);
$sheet->mergeCells('B' . $row . ':I' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($style['textTituloFont']);
$sheet->setCellValue('B' . $row++, DOMICILIOFISCAL);
$sheet->mergeCells('B' . $row . ':I' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($style['textTituloFont']);
$sheet->setCellValue('B' . $row++, 'R.I.F: ' . RIF);
$row++;

$sheet->getStyle('B' . $row . ':I' . $row)->applyFromArray($style['textTituloBorder']);
$sheet->setCellValue('A' . $row, '');
$sheet->setCellValue('B' . $row, 'RIF / CEDULA');
$sheet->setCellValue('C' . $row, 'RAZON SOCIAL');
$sheet->setCellValue('D' . $row, 'FECHA DESPACHO');
$sheet->setCellValue('E' . $row, 'NRO DESPACHO');
$sheet->setCellValue('F' . $row, 'DESCRIPCION PRODUCTO');
$sheet->setCellValue('G' . $row, 'PRECIO UNITARIO');
$sheet->setCellValue('H' . $row, 'CANTIDAD DESPACHADA');
$sheet->setCellValue('I' . $row++, 'SUBTOTAL');

$rowInicio = $row;
foreach ($arrayDesp as $IDCliente => $rowDatos) {
  $sheet->setCellValue('B' . $row, $rowDatos['RifCedula']);
  $sheet->setCellValue('C' . $row, $rowDatos['RazonSocial']);

  $rowClienteInicio = $row;
  foreach ($rowDatos['despacho'] as $idDespacho => $despacho) {
    $rowDespachoInicio = $row;
    $sheet->setCellValue('D' . $row, $despacho['FechaDespacho']);
    $sheet->setCellValue('E' . $row, $despacho['NroNota']);

    foreach ($despacho['detalle'] as $detalleDesp) {
      $sheet->setCellValue('F' . $row, $detalleDesp['DescripcionProducto']);
      $sheet->setCellValue('G' . $row, $detalleDesp['PrecioVentaDespUSD']);
      $sheet->getCell('G' . $row)->getStyle()->getNumberFormat()->setFormatCode('#,##0.00');
      $sheet->setCellValue('H' . $row, $detalleDesp['CantDesp']);
      $sheet->getCell('H' . $row)->getStyle()->getNumberFormat()->setFormatCode('#,##0.00');
      $sheet->setCellValue('I' . $row, '=G' . $row . '*H' . $row);
      $sheet->getCell('I' . $row)->getStyle()->getNumberFormat()->setFormatCode('#,##0.00');
      $row++;
    }
    if ($row > $rowDespachoInicio) {
      $sheet->mergeCells('D' . $rowDespachoInicio . ':D' . ($row - 1));
      $sheet->mergeCells('E' . $rowDespachoInicio . ':E' . ($row - 1));
    }
  }

  if ($row > $rowClienteInicio) {
    $sheet->mergeCells('B' . $rowClienteInicio . ':B' . ($row - 1));
    $sheet->mergeCells('C' . $rowClienteInicio . ':C' . ($row - 1));
  }
}
$sheet->getStyle('B' . $rowInicio . ':I' . ($row - 1))->applyFromArray($style['textSencillo']);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Distribucion Despachos ' . $del . ' - ' . $hasta . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
