<?php
require_once '../main.php';
require_once 'reportes_main.php';
require_once '../sessionStart.php';

$inicio = $_GET['del'];
$final = $_GET['hasta'];

$tabla = '';

$array_gastos = array(
  '<td> NOMINA </td>',
  '<td> COMPRAS ADMIMISTRACION</td>',
  '<td> CAJA CHICA</td>',
  '<td> COMBUSTIBLE</td>',
  '<td> CONSUMOS</td>',
  '<td> TRANSFERENCIA ENTRE HATOS</td>'
);

foreach (reporte_gastos_general([$inicio, $final,$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  $tabla  .= '<tr class="text-center">';
  $tabla  .= '<td>' . $row["Fecha"] .    '</td>';
  $tabla  .= '<td>' . $row["ConceptodeCompra"] .    '</td>';
  $tabla  .= '<td>' . $row["NDespacho"] .    '</td>';
  $tabla .= $array_gastos[$row["TipodeGasto"]];
  $tabla  .= '<td>' . number_format($row["Monto"], 2) .    '</td>';
  $tabla  .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
