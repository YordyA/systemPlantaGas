<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';

$fecha = LimpiarCadena($_GET['f']);
$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];

$tabla = '';
foreach (reporteExistenciaFinal([$IDSucursal, $fecha]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['CodigoArticulo'] . '</td>';
  $tabla .= '<td>' . $row['DescripcionArticulo'] . '</td>';
  $tabla .= '<td>' . number_format($row['Existencia'], 3) . '</td>';
  $tabla .= '<td>' . number_format($row['Costo'], 2) . '</td>';
  $tabla .= '<td>' . number_format($row['Total'], 2) . '</td>';
  $tabla .= '</tr>';
}
echo json_encode($tabla, JSON_UNESCAPED_UNICODE);