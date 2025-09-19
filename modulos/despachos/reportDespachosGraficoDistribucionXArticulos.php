<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'despachosMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$IDTipoDesp = desencriptar($_GET['IDTipoDesp']);
$IDUnidad = desencriptar($_GET['IDMedida']);

$arrayProductos = [];
$arrayCantProducida = [];
$arrayColores = [];
foreach (reportDespachoDistribucionXArticulos([$del, $hasta, $IDTipoDesp], $IDUnidad) as $row) {
  $arrayProductos[] = $row['DescripcionArticulo'] . ($IDUnidad == 'und' ? ' - ' . $row['DescripcionProducto'] : '');
  $arrayCantProducida[] = round(($IDUnidad == 'kg' ? $row['CantKg'] : $row['CantUnd']), 2);
  $arrayColores[] = generarColores(0.5);
}

$data = [
  'labels'    => $arrayProductos,
  'datasets'  => [
    'label'           => $IDUnidad == 'kg' ? 'Despachos KG' : 'Despachos UND',
    'data'            => $arrayCantProducida,
    'backgroundColor' => $arrayColores,
    'borderColor'     => $arrayColores,
    'borderWidth'     => 1
  ]
];
echo json_encode($data);
