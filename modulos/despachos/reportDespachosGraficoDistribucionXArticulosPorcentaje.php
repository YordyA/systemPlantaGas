<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'despachosMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$IDTipoDesp = desencriptar($_GET['IDTipoDesp']);

$consulta = reportDespachoDistribucionXArticulos([$del, $hasta, $IDTipoDesp], 'kg')->fetchAll();
$totalProduccion = 0;
foreach ($consulta as $rows) {
  $totalProduccion += round($rows['CantKg'], 2);
}

$arrayProductos = [];
$arrayPorcentaje = [];
$arrayColores = [];
foreach ($consulta as $row) {
  $porcentaje = round($row['CantKg'] / $totalProduccion * 100, 2);
  $arrayProductos[] = $row['DescripcionArticulo'] . ' (' . round($porcentaje, 2) . '%)';
  $arrayPorcentaje[] = $porcentaje;
  $arrayColores[] = generarColores(0.5);
}

$data = [
  'labels'   => $arrayProductos,
  'datasets' => [
    'label'             => 'Despacho X Porcentaje',
    'data'              => $arrayPorcentaje,
    'backgroundColor'   => $arrayColores,
    'borderColor'       => $arrayColores,
    'borderWidth'       => 1
  ]
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
