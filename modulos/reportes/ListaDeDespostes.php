<?php

require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';

$del = LimpiarCadena($_GET['d']);
$hasta = LimpiarCadena($_GET['h']);


$tabla = '';


foreach (ReporteDeResesDespostadas([$del, $hasta,$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['Fecha'] . '</td>';
  $tabla .= '<td>' . $row['CodigoRes'] . '</td>';
  $tabla .= '<td>' . $row['Peso'] . '</td>';
  $tabla .= '<td>' . $row['PulpaNegra'] . '</td>';
  $tabla .= '<td>' . $row['Chocozuela'] . '</td>';
  $tabla .= '<td>' . $row['PolloDeRes'] . '</td>';
  $tabla .= '<td>' . $row['PulpaHerradero'] . '</td>';
  $tabla .= '<td>' . $row['Lomo'] . '</td>';
  $tabla .= '<td>' . $row['Lomito'] . '</td>';
  $tabla .= '<td>' . $row['Punta'] . '</td>';
  $tabla .= '<td>' . $row['Cogote'] . '</td>';
  $tabla .= '<td>' . $row['Paleta'] . '</td>';
  $tabla .= '<td>' . $row['Pecho'] . '</td>';
  $tabla .= '<td>' . $row['Codillo'] . '</td>';
  $tabla .= '<td>' . $row['Lagarto'] . '</td>';
  $tabla .= '<td>' . $row['Falda'] . '</td>';
  $tabla .= '<td>' . $row['Descarne'] . '</td>';
  $tabla .= '<td>' . $row['Chuleta'] . '</td>';
  $tabla .= '<td>' . $row['Costilla'] . '</td>';
  $tabla .= '<td>' . $row['HuesoRedondo'] . '</td>';
  $tabla .= '<td>' . $row['HuesoRojo'] . '</td>';
  $tabla .= '<td>' . $row['HuesoSopero'] . '</td>';
  $tabla .= '<td>' . $row['Pellejos'] . '</td>';
  $tabla .= '<td>' . $row['Grasa'] . '</td>';
  $tabla .= '<td>' . $row['HuesoBlanco'] . '</td>';
  $tabla .= '</tr>';
}
echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
