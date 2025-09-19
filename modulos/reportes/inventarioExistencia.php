<?php
require_once '../main.php';
require_once '../inventario/inventario_main.php';
require_once '../sessionStart.php';
$tabla = '';

foreach (ConsultarArticulosYExistencia([$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['CodigoArticulo'] . '</td>';
  $tabla .= '<td>' . $row['DescripcionArticulo'] . '</td>';
  $tabla .= '<td>' . number_format($row['ExistenciaArticulo'], 3, '.', ',') . '</td>';
  $tabla .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);