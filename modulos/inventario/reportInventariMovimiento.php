<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'inventarioMain.php';
require_once '../sessionStart.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$tipoMov = desencriptar($_GET['id']);
$IDPlanta = $_SESSION['PlantaGas']['IDPlanta'];
$html = '';
foreach (reportInventarioMovimientos([$del, $hasta, $IDPlanta], $tipoMov) as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $tipoMovArray[$row['TipoMov']] . '</td>';
  $html .= '<td>' . $row['FechaMov'] . '</td>';
  $html .= '<td>' . $row['DescripcionAlmacen'] . '</td>';
  $html .= '<td>' . $row['ExistenciaAnterior'] . '</td>';
  $html .= '<td>' . $row['Movimiento'] . '</td>';
  $html .= '<td>' . $row['ExistenciaActual'] . '</td>';
  $html .= '<td>' . $row['ObservacionMov'] . '</td>';
  $html .= '<td>' . $row['ResponsableMov'] . '</td>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);