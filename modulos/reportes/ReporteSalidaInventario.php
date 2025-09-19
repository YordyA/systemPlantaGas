<?php

require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';

$del = LimpiarCadena($_GET['d']);
$hasta = LimpiarCadena($_GET['h']);


$tabla = '';


foreach (ListaSalidaInventario([$del, $hasta,$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['FechaMovimientoSalida'] . '</td>';
  $tabla .= '<td>' . $row['CodigoArticulo'] . '</td>';
  $tabla .= '<td>' . $row['DescripcionArticulo'] . '</td>';
  $tabla .= '<td>' . $row['ConceptodeMovimiento'] . '</td>';
  $tabla .= '<td>' . $row['Cantidad'] . '</td>';
  $tabla .= '<td>' . $row['Responsable'] . '</td>';
  $tabla .= '</tr>';
}
echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
