<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../facturacion/FacturacionMain.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;

$excel = new SpreadSheet;

$fecha = $_GET['f'];
$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];

$hoja_activa = $excel->getActiveSheet();
$hoja_activa->setTitle('ControlDivisas');

$styleArrayH1 = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'font' => [
    'bold' => true,
    'size' => 16,
  ],
];

$styleArrayH2 = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'font' => [
    'bold' => true,
    'size' => 12,
  ],
];

$styleArrayH3 = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'font' => [
    'bold' => true,
    'size' => 10,
  ],
];

$hoja_activa->mergeCells('A1:H1');
$hoja_activa->getStyle('A1')->applyFromArray($styleArrayH1);
$hoja_activa->setCellValue('A1', $_SESSION['PlantaGas']['Planta']);

$hoja_activa->getColumnDimension('A')->setWidth(20);
$hoja_activa->getColumnDimension('B')->setWidth(20);
$hoja_activa->getColumnDimension('C')->setWidth(20);
$hoja_activa->getColumnDimension('D')->setWidth(20);
$hoja_activa->getColumnDimension('E')->setWidth(20);
$hoja_activa->getColumnDimension('F')->setWidth(20);
$hoja_activa->getColumnDimension('G')->setWidth(20);
$hoja_activa->getColumnDimension('H')->setWidth(20);

$hoja_activa->mergeCells('A2:C2');
$hoja_activa->setCellValue('A2', 'FECHA: ' . $fecha);
$hoja_activa->mergeCells('A3:B3');
$hoja_activa->setCellValue('A3', 'FECHA DE EMISION: ' . date('d-m-Y H:i:s'));

$hoja_activa->mergeCells('A5:H5');
$hoja_activa->getStyle('A5')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('A5', 'CONTROL DIVISAS (USD)');

$hoja_activa->getStyle('A7:B16')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('A7:B16')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('A7:B16')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('A7:B16')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('A7:B16')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$hoja_activa->getStyle('A7:H7')->applyFromArray($styleArrayH2);
$hoja_activa->getStyle('A8:H8')->applyFromArray($styleArrayH3);
$hoja_activa->mergeCells('A7:B7');
$hoja_activa->setCellValue('A7', 'CAJA 1');
$hoja_activa->setCellValue('A8', 'DENOMINACION');
$hoja_activa->setCellValue('B8', 'CANTIDAD');

//* BILLETES
$hoja_activa->setCellValue('A9', '1$');
$hoja_activa->setCellValue('A10', '2$');
$hoja_activa->setCellValue('A11', '5$');
$hoja_activa->setCellValue('A12', '10$');
$hoja_activa->setCellValue('A13', '20$');
$hoja_activa->setCellValue('A14', '50$');
$hoja_activa->setCellValue('A15', '100$');
$hoja_activa->getStyle('A16')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('A16', 'TOTAL');

//* BILLETES CANTIDAD
$BilletesCaja1 = facturacionConsultarInventarioBilletesUSD([$fecha, $IDSucursal, 1]);
$hoja_activa->setCellValue('B9', $BilletesCaja1['Billete1']);
$hoja_activa->setCellValue('B10', $BilletesCaja1['Billete2']);
$hoja_activa->setCellValue('B11', $BilletesCaja1['Billete5']);
$hoja_activa->setCellValue('B12', $BilletesCaja1['Billete10']);
$hoja_activa->setCellValue('B13', $BilletesCaja1['Billete20']);
$hoja_activa->setCellValue('B14', $BilletesCaja1['Billete50']);
$hoja_activa->setCellValue('B15', $BilletesCaja1['Billete100']);
$hoja_activa->setCellValue('B15', $BilletesCaja1['Billete100']);

$totalUSDCaja1 = $BilletesCaja1['Billete1'] * 1;
$totalUSDCaja1 += $BilletesCaja1['Billete2'] * 2;
$totalUSDCaja1 += $BilletesCaja1['Billete5'] * 5;
$totalUSDCaja1 += $BilletesCaja1['Billete10'] * 10;
$totalUSDCaja1 += $BilletesCaja1['Billete20'] * 20;
$totalUSDCaja1 += $BilletesCaja1['Billete50'] * 50;
$totalUSDCaja1 += $BilletesCaja1['Billete100'] * 100;

$hoja_activa->getStyle('B16')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('B16', $totalUSDCaja1);


$hoja_activa->getStyle('D7:E16')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('D7:E16')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('D7:E16')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('D7:E16')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('D7:E16')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$hoja_activa->mergeCells('D7:E7');
$hoja_activa->setCellValue('D7', 'CAJA 2');
$hoja_activa->setCellValue('D8', 'DENOMINACION');
$hoja_activa->setCellValue('E8', 'CANTIDAD');

//* BILLETES
$hoja_activa->setCellValue('D9', '1$');
$hoja_activa->setCellValue('D10', '2$');
$hoja_activa->setCellValue('D11', '5$');
$hoja_activa->setCellValue('D12', '10$');
$hoja_activa->setCellValue('D13', '20$');
$hoja_activa->setCellValue('D14', '50$');
$hoja_activa->setCellValue('D15', '100$');
$hoja_activa->getStyle('D16')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('D16', 'TOTAL');

//* CANTIDA DE BILLETES
$BilletesCaja2 = facturacionConsultarInventarioBilletesUSD([$fecha, $IDSucursal, 2]);
$hoja_activa->setCellValue('E9', $BilletesCaja2['Billete1']);
$hoja_activa->setCellValue('E10', $BilletesCaja2['Billete2']);
$hoja_activa->setCellValue('E11', $BilletesCaja2['Billete5']);
$hoja_activa->setCellValue('E12', $BilletesCaja2['Billete10']);
$hoja_activa->setCellValue('E13', $BilletesCaja2['Billete20']);
$hoja_activa->setCellValue('E14', $BilletesCaja2['Billete50']);
$hoja_activa->setCellValue('E15', $BilletesCaja2['Billete100']);

$totalUSDCaja2 = $BilletesCaja2['Billete1'] * 1;
$totalUSDCaja2 += $BilletesCaja2['Billete2'] * 2;
$totalUSDCaja2 += $BilletesCaja2['Billete5'] * 5;
$totalUSDCaja2 += $BilletesCaja2['Billete10'] * 10;
$totalUSDCaja2 += $BilletesCaja2['Billete20'] * 20;
$totalUSDCaja2 += $BilletesCaja2['Billete50'] * 50;
$totalUSDCaja2 += $BilletesCaja2['Billete100'] * 100;
$hoja_activa->getStyle('E16')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('E16', $totalUSDCaja2);

$hoja_activa->getStyle('G7:H16')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('G7:H16')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('G7:H16')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('G7:H16')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('G7:H16')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$hoja_activa->mergeCells('G7:H7');
$hoja_activa->setCellValue('G7', 'CAJA 3');
$hoja_activa->setCellValue('G8', 'DENOMINACION');
$hoja_activa->setCellValue('H8', 'CANTIDAD');

$hoja_activa->setCellValue('G9', '1$');
$hoja_activa->setCellValue('G10', '2$');
$hoja_activa->setCellValue('G11', '5$');
$hoja_activa->setCellValue('G12', '10$');
$hoja_activa->setCellValue('G13', '20$');
$hoja_activa->setCellValue('G14', '50$');
$hoja_activa->setCellValue('G15', '100$');
$hoja_activa->getStyle('G16')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('G16', 'TOTAL');

//* CANTIDAD DE BILLETES
$BilletesCaja3 = facturacionConsultarInventarioBilletesUSD([$fecha, $IDSucursal, 3]);
$hoja_activa->setCellValue('H9', $BilletesCaja3['Billete1']);
$hoja_activa->setCellValue('H10', $BilletesCaja3['Billete2']);
$hoja_activa->setCellValue('H11', $BilletesCaja3['Billete5']);
$hoja_activa->setCellValue('H12', $BilletesCaja3['Billete10']);
$hoja_activa->setCellValue('H13', $BilletesCaja3['Billete20']);
$hoja_activa->setCellValue('H14', $BilletesCaja3['Billete50']);
$hoja_activa->setCellValue('H15', $BilletesCaja3['Billete100']);

$totalUSDCaja3 = $BilletesCaja3['Billete1'] * 1;
$totalUSDCaja3 += $BilletesCaja3['Billete2'] * 2;
$totalUSDCaja3 += $BilletesCaja3['Billete5'] * 5;
$totalUSDCaja3 += $BilletesCaja3['Billete10'] * 10;
$totalUSDCaja3 += $BilletesCaja3['Billete20'] * 20;
$totalUSDCaja3 += $BilletesCaja3['Billete50'] * 50;
$totalUSDCaja3 += $BilletesCaja3['Billete100'] * 100;

$hoja_activa->getStyle('H16')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('H16', $totalUSDCaja3);


$hoja_activa->mergeCells('A17:H17');
$hoja_activa->getStyle('A17')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('A17', 'CONTROL DIVISAS (COP)');

$hoja_activa->getStyle('A19:H19')->applyFromArray($styleArrayH2);
$hoja_activa->getStyle('A20:H20')->applyFromArray($styleArrayH3);

$hoja_activa->getStyle('A19:B32')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('A19:B32')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('A19:B32')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('A19:B32')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('A19:B32')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$hoja_activa->mergeCells('A19:B19');
$hoja_activa->setCellValue('A19', 'CAJA 1');
$hoja_activa->setCellValue('A20', 'DENOMINACION');
$hoja_activa->setCellValue('B20', 'CANTIDAD');

//* BILLETES
$hoja_activa->setCellValue('A21', '50COP');
$hoja_activa->setCellValue('A22', '100COP');
$hoja_activa->setCellValue('A23', '200COP');
$hoja_activa->setCellValue('A24', '500COP');
$hoja_activa->setCellValue('A25', '1.000COP');
$hoja_activa->setCellValue('A26', '2.000COP');
$hoja_activa->setCellValue('A27', '5.000COP');
$hoja_activa->setCellValue('A28', '10.000COP');
$hoja_activa->setCellValue('A29', '20.000COP');
$hoja_activa->setCellValue('A30', '50.000COP');
$hoja_activa->setCellValue('A31', '100.000COP');
$hoja_activa->getStyle('A32')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('A32', 'TOTAL');

//* BILLETES CANTIDAD
$BilletesCajaCOP1 = facturacionConsultarInventarioBilletesCOP([$fecha, $IDSucursal, 1]);
$hoja_activa->setCellValue('B21', $BilletesCajaCOP1['Billete50']);
$hoja_activa->setCellValue('B22', $BilletesCajaCOP1['Billete100']);
$hoja_activa->setCellValue('B23', $BilletesCajaCOP1['Billete200']);
$hoja_activa->setCellValue('B24', $BilletesCajaCOP1['Billete500']);
$hoja_activa->setCellValue('B25', $BilletesCajaCOP1['Billete1000']);
$hoja_activa->setCellValue('B26', $BilletesCajaCOP1['Billete2000']);
$hoja_activa->setCellValue('B27', $BilletesCajaCOP1['Billete5000']);
$hoja_activa->setCellValue('B28', $BilletesCajaCOP1['Billete10000']);
$hoja_activa->setCellValue('B29', $BilletesCajaCOP1['Billete20000']);
$hoja_activa->setCellValue('B30', $BilletesCajaCOP1['Billete50000']);
$hoja_activa->setCellValue('B31', $BilletesCajaCOP1['Billete100000']);

$totalCOPCaja1 = $BilletesCajaCOP1['Billete50'] * 50;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete100'] * 100;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete200'] * 200;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete500'] * 500;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete1000'] * 1000;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete2000'] * 2000;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete5000'] * 5000;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete10000'] * 10000;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete20000'] * 20000;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete50000'] * 50000;
$totalCOPCaja1 += $BilletesCajaCOP1['Billete100000'] * 100000;

$hoja_activa->getStyle('B32')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('B32', $totalCOPCaja1);

$hoja_activa->getStyle('D19:E32')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('D19:E32')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('D19:E32')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('D19:E32')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('D19:E32')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$hoja_activa->mergeCells('D19:E19');
$hoja_activa->setCellValue('D19', 'CAJA 2');
$hoja_activa->setCellValue('D20', 'DENOMINACION');
$hoja_activa->setCellValue('E20', 'CANTIDAD');

//* BILLETES
$hoja_activa->setCellValue('D21', '50COP');
$hoja_activa->setCellValue('D22', '100COP');
$hoja_activa->setCellValue('D23', '200COP');
$hoja_activa->setCellValue('D24', '500COP');
$hoja_activa->setCellValue('D25', '1.000COP');
$hoja_activa->setCellValue('D26', '2.000COP');
$hoja_activa->setCellValue('D27', '5.000COP');
$hoja_activa->setCellValue('D28', '10.000COP');
$hoja_activa->setCellValue('D29', '20.000COP');
$hoja_activa->setCellValue('D30', '50.000COP');
$hoja_activa->setCellValue('D31', '100.000COP');
$hoja_activa->getStyle('D32')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('D32', 'TOTAL');

//* BILLETES CANTIDAD
$BilletesCajaCOP2 = facturacionConsultarInventarioBilletesCOP([$fecha, $IDSucursal, 2]);
$hoja_activa->setCellValue('E21', $BilletesCajaCOP2['Billete50']);
$hoja_activa->setCellValue('E22', $BilletesCajaCOP2['Billete100']);
$hoja_activa->setCellValue('E23', $BilletesCajaCOP2['Billete200']);
$hoja_activa->setCellValue('E24', $BilletesCajaCOP2['Billete500']);
$hoja_activa->setCellValue('E25', $BilletesCajaCOP2['Billete1000']);
$hoja_activa->setCellValue('E26', $BilletesCajaCOP2['Billete2000']);
$hoja_activa->setCellValue('E27', $BilletesCajaCOP2['Billete5000']);
$hoja_activa->setCellValue('E28', $BilletesCajaCOP2['Billete10000']);
$hoja_activa->setCellValue('E29', $BilletesCajaCOP2['Billete20000']);
$hoja_activa->setCellValue('E30', $BilletesCajaCOP2['Billete50000']);
$hoja_activa->setCellValue('E31', $BilletesCajaCOP2['Billete100000']);

$totalCOPCaja2 = $BilletesCajaCOP2['Billete50'] * 50;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete100'] * 100;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete200'] * 200;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete500'] * 500;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete1000'] * 1000;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete2000'] * 2000;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete5000'] * 5000;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete10000'] * 10000;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete20000'] * 20000;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete50000'] * 50000;
$totalCOPCaja2 += $BilletesCajaCOP2['Billete100000'] * 100000;

$hoja_activa->getStyle('E32')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('E32', $totalCOPCaja2);

$hoja_activa->getStyle('G19:H32')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('G19:H32')->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('G19:H32')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('G19:H32')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$hoja_activa->getStyle('G19:H32')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$hoja_activa->mergeCells('G19:H19');
$hoja_activa->setCellValue('G19', 'CAJA 3');
$hoja_activa->setCellValue('G20', 'DENOMINACION');
$hoja_activa->setCellValue('H20', 'CANTIDAD');

//* BILLETES
$hoja_activa->setCellValue('G21', '50COP');
$hoja_activa->setCellValue('G22', '100COP');
$hoja_activa->setCellValue('G23', '200COP');
$hoja_activa->setCellValue('G24', '500COP');
$hoja_activa->setCellValue('G25', '1.000COP');
$hoja_activa->setCellValue('G26', '2.000COP');
$hoja_activa->setCellValue('G27', '5.000COP');
$hoja_activa->setCellValue('G28', '10.000COP');
$hoja_activa->setCellValue('G29', '20.000COP');
$hoja_activa->setCellValue('G30', '50.000COP');
$hoja_activa->setCellValue('G31', '100.000COP');
$hoja_activa->getStyle('G32')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('G32', 'TOTAL');

//* BILLETES CANTIDAD
$BilletesCajaCOP3 = facturacionConsultarInventarioBilletesCOP([$fecha, $IDSucursal, 3]);
$hoja_activa->setCellValue('H21', $BilletesCajaCOP3['Billete50']);
$hoja_activa->setCellValue('H22', $BilletesCajaCOP3['Billete100']);
$hoja_activa->setCellValue('H23', $BilletesCajaCOP3['Billete200']);
$hoja_activa->setCellValue('H24', $BilletesCajaCOP3['Billete500']);
$hoja_activa->setCellValue('H25', $BilletesCajaCOP3['Billete1000']);
$hoja_activa->setCellValue('H26', $BilletesCajaCOP3['Billete2000']);
$hoja_activa->setCellValue('H27', $BilletesCajaCOP3['Billete5000']);
$hoja_activa->setCellValue('H28', $BilletesCajaCOP3['Billete10000']);
$hoja_activa->setCellValue('H29', $BilletesCajaCOP3['Billete20000']);
$hoja_activa->setCellValue('H30', $BilletesCajaCOP3['Billete50000']);
$hoja_activa->setCellValue('H31', $BilletesCajaCOP3['Billete100000']);

$totalCOPCaja3 = $BilletesCajaCOP3['Billete50'] * 50;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete100'] * 100;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete200'] * 200;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete500'] * 500;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete1000'] * 1000;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete2000'] * 2000;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete5000'] * 5000;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete10000'] * 10000;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete20000'] * 20000;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete50000'] * 50000;
$totalCOPCaja3 += $BilletesCajaCOP3['Billete100000'] * 100000;

$hoja_activa->getStyle('H32')->applyFromArray($styleArrayH2);
$hoja_activa->setCellValue('H32', $totalCOPCaja3);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Control Divisas.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;