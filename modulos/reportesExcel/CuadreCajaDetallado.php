<?php
//// todo LIBRERIA DE EXCEL
require_once __DIR__ . "/vendor/autoload.php";
//// TODO OTROS MODULOS
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../reportesVentas/reportesVentas_main.php';


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


$hoja_activa->getStyle('A5:J5')->applyFromArray($styleArrayy);
$hoja_activa->setCellValue('A5', 'FECHA');
$hoja_activa->setCellValue('B5', 'CAJA');
$hoja_activa->setCellValue('C5', 'NRO VENTA');
$hoja_activa->setCellValue('D5', 'NRO FISCAL');
$hoja_activa->setCellValue('E5', 'CLIENTE');
$hoja_activa->setCellValue('F5', 'TOTAL FACTURA');
$hoja_activa->setCellValue('G5', 'EFECTIVO');
$hoja_activa->setCellValue('H5', 'BIOPAGO');
$hoja_activa->setCellValue('I5', 'TARJETA');
$hoja_activa->setCellValue('J5', 'PAGO MOVIL / TRASNFERENCIAS');
$hoja_activa->setCellValue('K5', 'VUELTO');
$hoja_activa->setCellValue('L5', 'CXC');
$hoja_activa->setCellValue('M5', 'DEV');

$fila = 6;

$totalGeneral = 0;
$totalEfectivo = 0;
$totalBiopago = 0;
$totaltarjeta = 0;
$totaltransferencia = 0;
$totalpercivido = 0;
$totalCxC = 0;
$totaldevoculciones = 0;
$totalvueltos=0;

foreach (CuadreDeCajaDetallado([$del,$hasta,$_SESSION['PlantaGas']['IDPlanta']]) as $row) {


  if($row['Estatus'] == 1){

    $cxc=$row['Efectivo'];

  }else{

    $cxc=0.00;
  }
  if ($row['Estatus'] == 0) {

   $Efectivo=$row['Efectivo'];

  }else{

    $Efectivo=0.00;
  }
  if ($row['Estatus'] == 2) {

    $dev=$row['BioPago']+$row['Tarjeta']+$row['Efectivo']+$row['Transferencia'];

  }else{

    $dev=0.00;
  }

  $totalGeneral += $row['total_caja'] + $row['sum_efectivo_estatus_1'];
  $totalEfectivo += $row['sum_efectivo_estatus_0'];
  $totalBiopago += $row['sum_biopago'];
  $totaltarjeta += $row['sum_tarjeta'];
  $totaltransferencia += $row['sum_transferencia'];
  $totalpercivido += $row['total_caja_precivido'];
  $totalCxC += $row['sum_efectivo_estatus_1'];
  $totaldevoculciones += $row['total_anulado'];
  $totalvueltos+=$row['sum_vuelto'];


  $hoja_activa->setCellValue('A' . $fila, $row['Fecha']);
  $hoja_activa->setCellValue('B' . $fila, $row['IDCaja']);
  $hoja_activa->setCellValue('C' . $fila, $row['NVenta']);
  $hoja_activa->setCellValue('D' . $fila, $row['NFacturaFiscal']);
  $hoja_activa->setCellValue('E' . $fila, $row['RifCliente']. ' - ' .$row['NombreCliente']);
  $hoja_activa->setCellValue('F' . $fila, round($row['TotalFactura'], 2));
  $hoja_activa->setCellValue('G' . $fila, round($Efectivo));
  $hoja_activa->setCellValue('H' . $fila, round($row['BioPago'], 2));
  $hoja_activa->setCellValue('I' . $fila, round($row['Tarjeta'], 2));
  $hoja_activa->setCellValue('J' . $fila, round($row['Transferencia'], 2));
  $hoja_activa->setCellValue('K' . $fila, round($row['Vuelto'], 2));
  $hoja_activa->setCellValue('L' . $fila, round($cxc, 2));
  $hoja_activa->setCellValue('M' . $fila, round($dev, 2));



  $fila++;
}



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Cuadre De Caja Detallado.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
