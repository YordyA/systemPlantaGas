<?php
//// todo LIBRERIA DE EXCEL
require_once __DIR__ . "/vendor/autoload.php";
//// TODO OTROS MODULOS
require_once '../main.php';
require_once '../reportesVentas/reportesVentas_main.php';
require_once '../sessionStart.php';



$del = $_GET['i'];
$hasta = $_GET['f'];
$id = $_SESSION['PlantaGas']['IDPlanta'];
$fecha1 = date("d-m-Y", strtotime($del));
$fecha2 = date("d-m-Y", strtotime($hasta));

// Define the function to consolidate the results
function consolidar_datos($del, $hasta, $id)
{
  // Execute the queries
  $pesajeInicialResults = GeneralConteoFisicoInicial([$del, $id]);
  $inventarioInicialResults = GeneralExistenciaInicial([$del, $id]);
  $inicialDiferenciasResults = GeneralConteoFisicoInicialDiferencias([$del, $id]);
  $pesajeFinalResults = GeneralConteoFisicoFinal([$hasta, $id]);
  $mermasManipulacionResults = GeneralConteoFisicoFinalMermas([$del, $hasta, $id]);
  $salidasResults = GeneralSalidasDeInventario([$del, $hasta, $id]);
  $entradasResults = GeneralEntradasDeInventario([$del, $hasta, $id]);
  $ventasPorArticulosResults = GeneralTotalVentas([$del, $hasta, $id]);
  $VentasSubtotalUSD = GeneralTotalVentasUSD([$del, $hasta, $id]);
  $ventasPorArticulosResults1 = GeneralTotalVentasCantidad([$del, $hasta, $id]);
  $donaciones = GeneralDonaciones([$del, $hasta, $id]);
  $consumos = GeneralConsumos([$del, $hasta, $id]);
  $SalidasOtras = GeneralSalidasDeInventarioOtrasSalidas([$del, $hasta, $id]);

  // Initialize an array to consolidate results
  $consolidado = [];

  // Function to aggregate data into the consolidated array
  function agregar_datos(&$consolidado, $resultados, $tipo)
  {
    foreach ($resultados as $item) {
      $idArticulo = $item['IDArticulo'];
      if (!isset($consolidado[$idArticulo])) {
        $consolidado[$idArticulo] = [
          'IDArticulo' => $item['IDArticulo'],
          'CodigoArticulo' => $item['CodigoArticulo'],
          'DescripcionArticulo' => $item['DescripcionArticulo'],
          'PesajeInicial' => 0,
          'InventarioInicial' => 0,
          'Diferencias' => 0,
          'PesajeFinal' => 0,
          'Mermas' => 0,
          'Salidas' => 0,
          'Entradas' => 0,
          'VentasSubtotal' => 0,
          'VentasCantidad' => 0,
          'Donaciones' => 0,
          'Consumos' => 0,
          'OtrasSalidas' => 0,
          'VentasSubtotalUSD' => 0
        ];
      }

      switch ($tipo) {
        case 'pesaje_inicial':
          $consolidado[$idArticulo]['PesajeInicial'] += $item['pesaje_inicial'];
          break;
        case 'inventario_inicial':
          $consolidado[$idArticulo]['InventarioInicial'] += $item['inventario_inicial'];
          break;
        case 'diferencias':
          $consolidado[$idArticulo]['Diferencias'] += $item['inical_diferencias'];
          break;
        case 'pesaje_final':
          $consolidado[$idArticulo]['PesajeFinal'] += $item['pesaje_final'];
          break;
        case 'mermas':
          $consolidado[$idArticulo]['Mermas'] += $item['mermas_manipulacion'];
          break;
        case 'salidas':
          $consolidado[$idArticulo]['Salidas'] += $item['salidas'];
          break;
        case 'entradas':
          $consolidado[$idArticulo]['Entradas'] += $item['entradas'];
          break;
        case 'ventas_subtotal':
          $consolidado[$idArticulo]['VentasSubtotal'] += $item['sum_total'];
          break;
        case 'ventas_cantidad':
          $consolidado[$idArticulo]['VentasCantidad'] += $item['sum_cantidad'];
          break;
        case 'donaciones':
          $consolidado[$idArticulo]['Donaciones'] += $item['donaciones'];
          break;
        case 'consumos':
          $consolidado[$idArticulo]['Consumos'] += $item['consumos'];
          break;
        case 'otras_salidas':
          $consolidado[$idArticulo]['OtrasSalidas'] += $item['otras_salidas'];
          break;
        case 'TotalUSD':
          $consolidado[$idArticulo]['VentasSubtotalUSD'] += $item['TotalUSD'];
          break;
      }
    }
  }

  // Add data from each query to the consolidated array
  agregar_datos($consolidado, $pesajeInicialResults, 'pesaje_inicial');
  agregar_datos($consolidado, $inventarioInicialResults, 'inventario_inicial');
  agregar_datos($consolidado, $inicialDiferenciasResults, 'diferencias');
  agregar_datos($consolidado, $pesajeFinalResults, 'pesaje_final');
  agregar_datos($consolidado, $mermasManipulacionResults, 'mermas');
  agregar_datos($consolidado, $salidasResults, 'salidas');
  agregar_datos($consolidado, $entradasResults, 'entradas');
  agregar_datos($consolidado, $ventasPorArticulosResults, 'ventas_subtotal');
  agregar_datos($consolidado, $ventasPorArticulosResults1, 'ventas_cantidad');
  agregar_datos($consolidado, $donaciones, 'donaciones');
  agregar_datos($consolidado, $consumos, 'consumos');
  agregar_datos($consolidado, $SalidasOtras, 'otras_salidas');
  agregar_datos($consolidado, $VentasSubtotalUSD, 'TotalUSD');
  // Convert consolidated array to a numerical array for better readability
  return array_values($consolidado);
}

// Call the function and get the consolidated data
$datosConsolidados = consolidar_datos($del, $hasta, $id);

// Print the consolidated data
//print_r($datosConsolidados);






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
$hoja_activa->mergeCells('A1:O1');

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
$hoja_activa->getColumnDimension('L')->setWidth(20);
$hoja_activa->getColumnDimension('M')->setWidth(20);
$hoja_activa->getColumnDimension('N')->setWidth(20);
$hoja_activa->getColumnDimension('O')->setWidth(20);

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
    'bold' => false,
    'size' => 8,
  ],
];

$textIzquierda = [
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
  ],
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
      'color' => ['argb' => 'FF000000'],
    ],
  ],
  'font' => [
    'bold' => false,
    'size' => 8,
  ],
];
$hoja_activa->getStyle('A5:O5')->applyFromArray($h3);
$hoja_activa->setCellValue('A5', 'Codigo');
$hoja_activa->setCellValue('B5', 'Descripcion');
$hoja_activa->setCellValue('C5', 'Inventario Inicial');
$hoja_activa->setCellValue('D5', 'Pesaje Inicial');
$hoja_activa->setCellValue('E5', 'Entradas');
$hoja_activa->setCellValue('F5', 'Salidas');
$hoja_activa->setCellValue('G5', 'Inventario Final');
$hoja_activa->setCellValue('H5', 'Inventario Fisico');
$hoja_activa->setCellValue('I5', 'Mermas');
$hoja_activa->setCellValue('J5', 'Otras Salidas');
$hoja_activa->setCellValue('K5', 'Cant. Donaciones');
$hoja_activa->setCellValue('L5', 'Cant. Autoconsumo');
$hoja_activa->setCellValue('M5', 'Cant. Ventas');
$hoja_activa->setCellValue('N5', 'Ventas BS');
$hoja_activa->setCellValue('O5', 'Ventas $');

$totalventas = 0;
$totaldolares = 0;
$totalbs = 0;
$totaliva = 0;
$exento = 0;
$base = 0;


$fila = 6;

foreach ($datosConsolidados as $data) {
  $hoja_activa->getStyle('A' . $fila . ':O' . $fila)->applyFromArray(styleArray: $textIzquierda);
  $hoja_activa->setCellValue('A' . $fila, htmlspecialchars($data['CodigoArticulo']));
  $hoja_activa->setCellValue('B' . $fila, htmlspecialchars($data['DescripcionArticulo']));
  $hoja_activa->setCellValue('C' . $fila, htmlspecialchars(round($data['InventarioInicial'], 3)));
  $hoja_activa->setCellValue('D' . $fila, htmlspecialchars(round($data['PesajeInicial'], 3)));
  $hoja_activa->setCellValue('E' . $fila, htmlspecialchars(round($data['Entradas'], 3)));
  $hoja_activa->setCellValue('F' . $fila, htmlspecialchars(round($data['Salidas'], 3)));
  $hoja_activa->setCellValue('G' . $fila, htmlspecialchars(round($data['PesajeInicial'] + $data['Entradas'] - $data['Salidas'], 3)));
  $hoja_activa->setCellValue('H' . $fila, htmlspecialchars(round($data['PesajeFinal'], 3)));
  $hoja_activa->setCellValue('I' . $fila, htmlspecialchars(round($data['Mermas'], 3)));
  $hoja_activa->setCellValue('J' . $fila, htmlspecialchars(round($data['OtrasSalidas'], 3)));
  $hoja_activa->setCellValue('K' . $fila, htmlspecialchars(round($data['Donaciones'], 3)));
  $hoja_activa->setCellValue('L' . $fila, htmlspecialchars(round($data['Consumos'], 3)));
  $hoja_activa->setCellValue('M' . $fila, htmlspecialchars(round($data['VentasCantidad'], 3)));
  $hoja_activa->setCellValue('N' . $fila, htmlspecialchars(round($data['VentasSubtotal'], 2)));
  $hoja_activa->setCellValue('O' . $fila, htmlspecialchars(round($data['VentasSubtotalUSD'], 2)));

  $fila++;
}



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte General Frigorifico.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
