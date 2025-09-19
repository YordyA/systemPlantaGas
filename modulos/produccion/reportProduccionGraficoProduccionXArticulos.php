<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'produccionMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$IDUnidad = desencriptar($_GET['IDMedida']);

$arrayProductos = [];
$arrayCantProducida = [];
$arrayColores = [];
foreach (reportProduccionXArticulos([$del, $hasta], $IDUnidad) as $row) {
  $arrayProductos[] = $row['DescripcionArticulo'] . ($IDUnidad == 'und' ? ' - ' . $row['DescripcionProducto'] : '');
  $arrayCantProducida[] = ($IDUnidad == 'kg' ? $row['TotalProducidoKG'] : $row['TotalProducidoUND']);
  $arrayColores[] = generarColores(0.5);
}

$data = [
  'labels'    => $arrayProductos,
  'datasets'  => [
    'label'           => $IDUnidad == 'kg' ? 'Cantidad Producida KG' : 'Cantidad Producida UND',
    'data'            => $arrayCantProducida,
    'backgroundColor' => $arrayColores,
    'borderColor'     => $arrayColores,
    'borderWidth'     => 1
  ]
];
echo json_encode($data);
