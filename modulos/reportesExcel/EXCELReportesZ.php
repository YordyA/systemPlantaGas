<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once './vendor/autoload.php';
require_once '../maquinasFiscales/maquinasFiscalesMain.php';

$del = LimpiarCadena($_GET['d']);
$hasta = LimpiarCadena($_GET['h']);
$tipoArchivo = desencriptar($_GET['tipoArchivo']);
if ($tipoArchivo == 'text') {
  header('Location: ../maquinasFiscales/maquinasFiscalesTxt.php?d=' . $del . '&h=' . $hasta);
}

$consultarMaquinaFiscales = maquinasFiscalesLista([$_SESSION['PlantaGas']['IDPlanta'], $del, $hasta])->fetchAll(PDO::FETCH_ASSOC);

$styleArray = [
  'textH1' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'font' => [
      'bold' => true,
      'size' => 12,
    ],
  ],
  'textH2' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'font' => [
      'bold' => true,
      'size' => 9,
    ],
  ],
  'textH3' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'font' => [
      'bold' => true,
      'size' => 8,
    ],
  ],
  'textEncabezado' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      'wrap' => true,
    ],
    'font' => [
      'bold' => true,
      'size' => 8
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => [
          'argb' => 'FF000000',
        ],
      ],
    ],
    'fill' => [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
      'startColor' => [
        'argb' => 'FF808080',
      ],
    ],
  ],
  'text' => [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
    'font' => [
      'bold' => true,
      'size' => 8,
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => [
          'argb' => '#FF000000',
        ],
      ],
    ],
  ],
];

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;

$excel = new Spreadsheet();
$sheet = $excel->getActiveSheet();
$sheet->setTitle('REPORTES Z');

$sheet->getColumnDimension('A')->setWidth(1);
$sheet->getColumnDimension('B')->setWidth(6.5);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(11);
$sheet->getColumnDimension('E')->setWidth(9);
$sheet->getColumnDimension('F')->setWidth(11);
$sheet->getColumnDimension('F')->setWidth(11);
$sheet->getColumnDimension('G')->setWidth(11);
$sheet->getColumnDimension('H')->setWidth(11);
$sheet->getColumnDimension('I')->setWidth(6);
$sheet->getColumnDimension('J')->setWidth(7);
$sheet->getColumnDimension('K')->setWidth(11);
$sheet->getColumnDimension('L')->setWidth(11);
$sheet->getColumnDimension('M')->setWidth(11);
$sheet->getColumnDimension('N')->setWidth(11);
$sheet->getColumnDimension('O')->setWidth(11);
$sheet->getColumnDimension('P')->setWidth(11);
$sheet->getColumnDimension('Q')->setWidth(11);
$sheet->getColumnDimension('R')->setWidth(11);
$sheet->getColumnDimension('S')->setWidth(11);

$row = 1;
$sheet->mergeCells('B' . $row . ':V' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($styleArray['textH1']);
$sheet->setCellValue('B' . $row++, $consultarMaquinaFiscales[0]['Sucursal']);
$sheet->mergeCells('B' . $row . ':V' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($styleArray['textH2']);
$sheet->setCellValue('B' . $row++, $consultarMaquinaFiscales[0]['Rif']);

$sheet->mergeCells('B' . $row . ':H' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($styleArray['textH3']);
$sheet->setCellValue('B' . $row++, 'FECHA DE EMICION: ' . date('d/m/Y H:i:s'));
$sheet->mergeCells('B' . $row . ':H' . $row);
$sheet->getStyle('B' . $row)->applyFromArray($styleArray['textH3']);
$sheet->setCellValue('B' . $row, 'FECHA DEL REPORTE: ' . $del . ' HASTA ' . $hasta);
$row += 3;

foreach ($consultarMaquinaFiscales as $rowMaquina) {
  $sheet->mergeCells('B' . $row . ':V' . $row);
  $sheet->getStyle('B' . $row . ':V' . $row)->applyFromArray($styleArray['textEncabezado']);
  $sheet->setCellValue('B' . $row++, 'CAJA NRO - ' . $rowMaquina['NroCaja']);
  $row++;

  $sheet->mergeCells('L' . $row . ':S' . $row);
  $sheet->getStyle('L' . $row . ':S' . $row)->applyFromArray($styleArray['textEncabezado']);
  $sheet->setCellValue('L' . $row++, 'VENTAS INTERNAS O EXPORTACIONES GRAVADAS');

  $sheet->getStyle('L' . $row . ':O' . $row)->applyFromArray($styleArray['textEncabezado']);
  $sheet->mergeCells('L' . $row . ':O' . $row);
  $sheet->setCellValue('L' . $row, 'VENTAS A CONTRIBUYENTES');

  $sheet->mergeCells('P' . $row . ':S' . $row);
  $sheet->getStyle('P' . $row . ':s' . $row)->applyFromArray($styleArray['textEncabezado']);
  $sheet->setCellValue('P' . $row++, 'VENTAS NO CONTRIBUYENTES');

  $sheet->getStyle('B' . $row . ':V' . $row)->applyFromArray($styleArray['textEncabezado']);
  $sheet->getStyle('B' . $row . ':V' . $row)->getAlignment()->setWrapText(true);
  $sheet->setCellValue('B' . $row, 'Oper. Nro.');
  $sheet->setCellValue('C' . $row, 'Fecha de la Factura');
  $sheet->setCellValue('D' . $row, 'Registro Maquina');
  $sheet->setCellValue('E' . $row, 'N° Reporte Z');
  $sheet->setCellValue('F' . $row, 'Numero de Factura Inicial');
  $sheet->setCellValue('G' . $row, 'Numero de Factura Final');
  $sheet->setCellValue('H' . $row, 'Nº de Nota de Credito o Debito');
  $sheet->setCellValue('I' . $row, 'Tipo de Transac');
  $sheet->setCellValue('J' . $row, 'Nº Factura Afectada');
  $sheet->setCellValue('K' . $row, 'Total de ventas Incluyendo el IVA');
  $sheet->setCellValue('L' . $row, 'Ventas Internas no gravadas');
  $sheet->setCellValue('M' . $row, 'Base Imponible');
  $sheet->setCellValue('N' . $row, 'Alicuota General o Reducida');
  $sheet->setCellValue('O' . $row, 'Impuesto IVA');
  $sheet->setCellValue('P' . $row, 'Ventas Internas no gravadas');
  $sheet->setCellValue('Q' . $row, 'Base Imponible');
  $sheet->setCellValue('R' . $row, 'Alicuota General o Reducida');
  $sheet->setCellValue('S' . $row, 'Impuesto IVA');
  $sheet->setCellValue('T' . $row, 'Fecha del Comprob.');
  $sheet->setCellValue('U' . $row, 'Numero Comprobante Retencion');
  $sheet->setCellValue('V' . $row++, 'Iva Retenido');

  $nroOper = 1;
  $rowInicio = $row;
  foreach (maquinasFiscalesConsultarXID([$rowMaquina['IDMaquinaFiscal'], $del, $hasta]) as $rowInfoMaquina) {
    $sheet->getStyle('B' . $row . ':V' . $row)->applyFromArray($styleArray['text']);
    $sheet->setCellValue('B' . $row, $nroOper++);
    $sheet->setCellValue('C' . $row, $rowInfoMaquina['FechaCierreReporteZ']);
    $sheet->setCellValue('D' . $row, $rowInfoMaquina['SerialMaquinaFiscal']);
    $sheet->setCellValue('E' . $row, generarCerosIzquierda($rowInfoMaquina['NroReporteZ'], 4));
    $sheet->setCellValue('F' . $row, generarCerosIzquierda($rowInfoMaquina['NroFacturaDesde'], 8));
    $sheet->setCellValue('G' . $row, generarCerosIzquierda($rowInfoMaquina['NroFacturaHasta'], 8));
    $sheet->setCellValue('H' . $row, '');
    $sheet->setCellValue('I' . $row, '01-reg');
    $sheet->setCellValue('J' . $row, '');
    $sheet->setCellValue('K' . $row, '=L' . $row . '+M' . $row . '+O' . $row . '+P' . $row . '+Q' . $row . '+S' . $row);
    $sheet->getCell('K' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('L' . $row, 0);
    $sheet->getCell('L' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('M' . $row, 0);
    $sheet->getCell('M' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('N' . $row, '16%');
    $sheet->setCellValue('O' . $row, '=M' . $row . '*N' . $row);
    $sheet->getCell('O' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('P' . $row, $rowInfoMaquina['MontoTotalExento']);
    $sheet->getCell('P' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('Q' . $row, $rowInfoMaquina['MontoTotalBaseImponible']);
    $sheet->getCell('Q' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('R' . $row, '16%');
    $sheet->setCellValue('S' . $row, '=R' . $row . '*Q' . $row);
    $sheet->getCell('S' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
    $sheet->setCellValue('T' . $row, '');
    $sheet->setCellValue('U' . $row, '');
    $sheet->setCellValue('V' . $row++, '');

    if ($rowInfoMaquina['MontoTotalExentoNotaCredito'] > 0 || $rowInfoMaquina['MontoTotalBaseImponibleNotaCredito'] > 0) {
      $sheet->getStyle('B' . $row . ':V' . $row)->applyFromArray($styleArray['text']);
      $sheet->setCellValue('B' . $row, $nroOper++);
      $sheet->setCellValue('C' . $row, $rowInfoMaquina['FechaCierreReporteZ']);
      $sheet->setCellValue('D' . $row, $rowInfoMaquina['SerialMaquinaFiscal']);
      $sheet->setCellValue('E' . $row, '');
      $sheet->setCellValue('F' . $row, '');
      $sheet->setCellValue('G' . $row, '');
      $sheet->setCellValue('H' . $row, '');
      $sheet->setCellValue('I' . $row, '01-reg');
      $sheet->setCellValue('J' . $row, '');
      $sheet->setCellValue('K' . $row, '=L' . $row . '+M' . $row . '+O' . $row . '+P' . $row . '+Q' . $row . '+S' . $row);
      $sheet->getCell('K' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
      $sheet->setCellValue('L' . $row, 0);
      $sheet->getCell('L' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
      $sheet->setCellValue('M' . $row, 0);
      $sheet->getCell('M' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
      $sheet->setCellValue('N' . $row, '16%');
      $sheet->setCellValue('O' . $row, '=M' . $row . '*N' . $row);
      $sheet->getCell('O' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
      $sheet->setCellValue('P' . $row, (-1 * $rowInfoMaquina['MontoTotalExentoNotaCredito']));
      $sheet->getCell('P' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
      $sheet->setCellValue('Q' . $row, (-1 * $rowInfoMaquina['MontoTotalBaseImponibleNotaCredito']));
      $sheet->getCell('Q' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
      $sheet->setCellValue('R' . $row, '16%');
      $sheet->setCellValue('S' . $row, '=R' . $row . '*Q' . $row);
      $sheet->getCell('S' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
      $sheet->setCellValue('T' . $row, '');
      $sheet->setCellValue('U' . $row, '');
      $sheet->setCellValue('V' . $row++, '');
    }
  }
  $row++;
  $sheet->getStyle('J' . $row . ':M' . $row)->applyFromArray($styleArray['text']);
  $sheet->setCellValue('J' . $row, 'TOTALES');
  $sheet->setCellValue('K' . $row, '=SUM(K' . $rowInicio . ':K' . ($row - 2) . ')');
  $sheet->getCell('K' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->setCellValue('L' . $row, '=SUM(L' . $rowInicio . ':L' . ($row - 2) . ')');
  $sheet->getCell('L' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->setCellValue('M' . $row, '=SUM(M' . $rowInicio . ':M' . ($row - 2) . ')');
  $sheet->getCell('M' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->getStyle('O' . $row . ':Q' . $row)->applyFromArray($styleArray['text']);
  $sheet->setCellValue('O' . $row, '=SUM(O' . $rowInicio . ':O' . ($row - 2) . ')');
  $sheet->getCell('O' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->setCellValue('P' . $row, '=SUM(P' . $rowInicio . ':P' . ($row - 2) . ')');
  $sheet->getCell('P' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->setCellValue('Q' . $row, '=SUM(Q' . $rowInicio . ':Q' . ($row - 2) . ')');
  $sheet->getCell('Q' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->getStyle('S' . $row)->applyFromArray($styleArray['text']);
  $sheet->setCellValue('S' . $row, '=SUM(S' . $rowInicio . ':S' . ($row - 2) . ')');
  $sheet->getCell('S' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  $sheet->getStyle('V' . $row)->applyFromArray($styleArray['text']);
  $sheet->setCellValue('V' . $row, '=SUM(V' . $rowInicio . ':V' . ($row - 2) . ')');
  $sheet->getCell('S' . $row)->getStyle()->getNumberFormat()->setFormatCode((string) new Number(2, Number::WITH_THOUSANDS_SEPARATOR));
  $row += 3;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="REPORTES Z - ' . $consultarMaquinaFiscales[0]['Sucursal'] . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
