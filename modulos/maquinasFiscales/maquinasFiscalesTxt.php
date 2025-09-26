<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../maquinasFiscales/maquinasFiscalesMain.php';
require_once '../dependencias.php';
// Limpiar las entradas
$del = LimpiarCadena($_GET['d']);
$hasta = LimpiarCadena($_GET['h']);

$nombreArchivo = 'REPORTES_Z.txt';
$contenido = '';
foreach (maquinasFiscalesConsultarXFecha([$_SESSION['PlantaGas']['IDPlanta'], $del, $hasta]) as $row) {
  $contenido .= $row['NroFacturaDesde'] . "\t";
  $contenido .= $row['NroFacturaHasta'] . "\t";
  $contenido .= $row['FechaCierreReporteZ'] . "\t";
  $contenido .= $row['MontoTotalExento'] . "\t";
  $contenido .= $row['MontoTotalBaseImponible'] . "\t";
  $contenido .= "\t";
  $contenido .= "\t";
  $contenido .= "\t";
  $contenido .= "\t";
  $contenido .= "\t";
  $contenido .= "\t";
  $contenido .= "\t";
  $contenido .= "\t";
  $contenido .= "S\t";
  $contenido .= $row['CodigoSaw'] . "\t";
  $contenido .= $row['NroFacturaDesde'] . "\t";
  $contenido .= $row['NroFacturaHasta'] . "\n";
}

file_put_contents($nombreArchivo, $contenido);
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
header('Content-Length: ' . filesize($nombreArchivo));
readfile($nombreArchivo);
