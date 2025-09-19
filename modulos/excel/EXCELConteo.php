<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../inventarioProduccionConteo/inventarioProduccionConteoMain.php';
require_once 'vendor/autoload.php';

$nroConteo = desencriptar($_GET['id']);
$consulta = inventarioProduccionConteoConsultar([$nroConteo])->fetchAll(PDO::FETCH_ASSOC);

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
$sheet->setTitle('CONTEO DIA ' . $consulta[0]['FechaCierreConteo']);

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);

$row = 1;
$sheet->mergeCells('A' . $row . ':F' . $row);
$sheet->getStyle('A' . $row)->applyFromArray($style['textTitulo']);
$sheet->setCellValue('A' . $row++, RAZONSOCIAL);
$sheet->mergeCells('A' . $row . ':F' . $row);
$sheet->getStyle('A' . $row)->applyFromArray($style['textTituloFont']);
$sheet->setCellValue('A' . $row++, DOMICILIOFISCAL);
$sheet->mergeCells('A' . $row . ':F' . $row);
$sheet->getStyle('A' . $row)->applyFromArray($style['textTituloFont']);
$sheet->setCellValue('A' . $row++, 'R.I.F: ' . RIF);

$sheet->mergeCells('A' . $row . ':F' . $row);
$sheet->getStyle('A' . $row)->applyFromArray($style['textSencilloSinBorder']);
$sheet->setCellValue('A' . $row++, 'FECHA DE EMICION: ' . $fechaHoraModificacion);
$sheet->mergeCells('A' . $row . ':F' . $row);
$sheet->getStyle('A' . $row)->applyFromArray($style['textSencilloSinBorder']);
$sheet->setCellValue('A' . $row++, 'FECHA DEL CONTEO: ' .  $consulta[0]['FechaCierreConteo']);
$sheet->mergeCells('A' . $row . ':F' . $row);
$sheet->getStyle('A' . $row)->applyFromArray($style['textSencilloSinBorder']);
$sheet->setCellValue('A' . $row++, 'RESPONSABLE CONTEO: ' . $consulta[0]['ResponsableConteo']);
$row++;

$sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($style['textTituloBorder']);
$sheet->setCellValue('A' . $row, 'TIPO PRODUCTO');
$sheet->setCellValue('B' . $row, 'CÓDIGO DEL PRODUCTO');
$sheet->setCellValue('C' . $row, 'DESCRIPCIÓN PRODUCTO');
$sheet->setCellValue('D' . $row, 'EXISTENCIA SISTEMA');
$sheet->setCellValue('E' . $row, 'CANTIDAD FÍSICA');
$sheet->setCellValue('F' . $row++, 'DIFERENCIA');

$rowInicio = $row;
foreach ($consulta as $rowDetalle) {
  $sheet->setCellValue('A' . $row, $rowDetalle['DescripcionTipoProducto']);
  $sheet->setCellValue('B' . $row, $rowDetalle['CodigoProducto']);
  $sheet->setCellValue('C' . $row, $rowDetalle['DescripcionProducto']);
  $sheet->setCellValue('D' . $row, $rowDetalle['CantSistema']);
  $sheet->getCell('D' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(4, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->setCellValue('E' . $row, $rowDetalle['CantFisica']);
  $sheet->getCell('E' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(4, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->setCellValue('F' . $row, $rowDetalle['Diferencia']);
  $sheet->getCell('F' . $row++)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(4, Number::WITH_THOUSANDS_SEPARATOR));
}
$sheet->getStyle('A' . $rowInicio . ':F' . ($row - 1))->applyFromArray($style['textSencillo']);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . RAZONSOCIAL . ' - CONTEO FISICO ' . $consulta[0]['FechaCierreConteo'] . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
