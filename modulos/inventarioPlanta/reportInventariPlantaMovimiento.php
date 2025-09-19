<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'inventarioPlantaMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$tipoMov = desencriptar($_GET['id']);

$html = '';
foreach (reportInventarioPlantaMovimientos([$del, $hasta], $tipoMov) as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $tipoMovArray[$row['TipoMov']] . '</td>';
  $html .= '<td>' . $row['FechaMov'] . '</td>';
  $html .= '<td>' . $row['CodigoArticulo'] . '</td>';
  $html .= '<td>' . $row['DescripcionArticulo'] . '</td>';
  $html .= '<td>' . $row['DescripcionProducto'] . '</td>';
  $html .= '<td>' . $row['NroLote'] . '</td>';
  $html .= '<td>' . $row['ExistenciaAnterior'] . '</td>';
  $html .= '<td>' . $row['Movimiento'] . '</td>';
  $html .= '<td>' . $row['ExistenciaActual'] . '</td>';
  $html .= '<td>' . $row['ObservacionMov'] . '</td>';
  $html .= '<td>' . $row['ResponsableMov'] . '</td>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);