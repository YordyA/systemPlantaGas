<?php
//// todo LIBRERIA DE EXCEL
require_once __DIR__ . "/vendor/autoload.php";
//// TODO OTROS MODULOS
require_once '../main.php';
require_once '../reportes/reportes_main.php';
require_once '../sessionStart.php';



function VueltosPagoMovil($datos)
{
  $reporte = conexion()->prepare("SELECT * FROM facturasvueltobdv WHERE Fecha BETWEEN ? AND ? AND IDSucursal = ?");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

$del = $_GET['i'];
$hasta = $_GET['f'];

$fecha1 = date("d-m-Y", strtotime($del));
$fecha2 = date("d-m-Y", strtotime($hasta));

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$excel = new SpreadSheet;

$hoja_activa = $excel->getActiveSheet();
$hoja_activa->setTitle('ConciliacionPagoMovilVueltos');


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
$hoja_activa->mergeCells('A1:K1');

$hoja_activa->getStyle('A1')->applyFromArray($styleArray);
$hoja_activa->setCellValue('A1', $_SESSION['PlantaGas']['Planta']);


// Establecer el ancho de las columnas
$hoja_activa->getColumnDimension('A')->setWidth(20);
$hoja_activa->getColumnDimension('B')->setWidth(20);
$hoja_activa->getColumnDimension('C')->setWidth(20);
$hoja_activa->getColumnDimension('D')->setWidth(20);
$hoja_activa->getColumnDimension('E')->setWidth(20);
$hoja_activa->getColumnDimension('F')->setWidth(20);
$hoja_activa->getColumnDimension('G')->setWidth(20);
$hoja_activa->getColumnDimension('H')->setWidth(20);
$hoja_activa->getColumnDimension('I')->setWidth(20);
$hoja_activa->getColumnDimension('J')->setWidth(20);
$hoja_activa->getColumnDimension('K')->setWidth(20);
// Otras celdas y configuraciones
$hoja_activa->setCellValue('A2', 'Fecha: ' . $fecha1 . ' al ' . $fecha2);
$hoja_activa->setCellValue('A3', 'Fecha de Emision: ' . date('d-m-Y H:i:s'));



$styleArrayy = [
  'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'font' => [
      'bold' => true,
      'size' => 10,
  ],
];


$hoja_activa->getStyle('A5:K5')->applyFromArray($styleArrayy);
$hoja_activa->setCellValue('A5', 'Fecha');
$hoja_activa->setCellValue('B5', 'Sucursal');
$hoja_activa->setCellValue('C5', 'Caja');
$hoja_activa->setCellValue('D5', 'Número de Venta');
$hoja_activa->setCellValue('E5', 'Cédula Destino');
$hoja_activa->setCellValue('F5', 'Teléfono Destino');
$hoja_activa->setCellValue('G5', 'Banco Destino');
$hoja_activa->setCellValue('H5', 'Monto Vuelo');
$hoja_activa->setCellValue('I5', 'Referencia');
$hoja_activa->setCellValue('J5', 'Concepto');
$hoja_activa->setCellValue('K5', 'Responsable');

$fila = 6;

foreach (VueltosPagoMovil([$del, $hasta,$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
        $hoja_activa->setCellValue('A'.$fila, $row["Fecha"]);
        $hoja_activa->setCellValue('B'.$fila, $row["IDSucursal"]);
        $hoja_activa->setCellValue('C'.$fila, $row["IDCaja"]);
        $hoja_activa->setCellValue('D'.$fila, $row["NroVenta"]);
        $hoja_activa->setCellValue('E'.$fila, $row["CedulaDestino"]);
        $hoja_activa->setCellValue('F'.$fila, $row["TLFDestino"]);
        $hoja_activa->setCellValue('G'.$fila, $row["BancoDestino"]);
        $hoja_activa->setCellValue('H'.$fila, $row["MontoVuleto"]);
        $hoja_activa->setCellValue('I'.$fila, $row["Referencia"]);
        $hoja_activa->setCellValue('J'.$fila, $row["Concepto"]);
        $hoja_activa->setCellValue('K'.$fila, $row["Responsable"]);


  $fila++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Vueltos Pago Movil.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
