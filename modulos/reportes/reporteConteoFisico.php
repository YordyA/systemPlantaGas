<?php
require_once '../main.php';
require_once 'reportes_main.php';
require_once '../sessionStart.php';


$Fecha = LimpiarCadena($_GET['del']);
$Tipo = LimpiarCadena(Desencriptar($_GET['tipo']));
$tabla = '';

foreach (ConteoFisicoPorFecha([$Fecha, $Tipo, $_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  if ($row['TipoConteo'] == 0) {
    $tipo =  '<span class="status-btn success-btn">PESAJE  INICIAL</span>';
  } elseif ($row['TipoConteo'] == 1) {
    $tipo = '<span class="status-btn close-btn">PESAJE FINAL</span>';
  }
  $tabla .= '<tr>';
  $tabla .= '<td>' .  $row['Fecha'] . '</td>';
  $tabla .= '<td>' .  $tipo . '</td>';
  $tabla .= '<td>' .  $row['CodigoArticulo'] . '</td>';
  $tabla .= '<td>' .  $row['DescripcionArticulo'] . '</td>';
  $tabla .= '<td>' .  $row['ExistenciaSistema'] . '</td>';
  $tabla .= '<td>' .  $row['ExistenciaFisica'] . '</td>';
  $tabla .= '<td>' .  $row['Diferencia'] . '</td>';
  $tabla .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
