<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../dependencias.php';

$consulta = consultarFacturasEnEspera([$_SESSION['PlantaGas']['IDPlanta'], Desencriptar(LimpiarCadena($_GET['id']))]);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

$usd = $_SESSION['PlantaGas']['Dolar'];

$html = array();
$html['tabla'] = '';

$html['NroVenta'] = $consulta[0]['NFacturaEspera'];
$html['Rif'] = $consulta[0]['RifCliente'];
$html['Nombre'] = $consulta[0]['NombreCliente'];

foreach ($consulta as $row) {
  $html['tabla'] .= '<tr>';
  $html['tabla'] .= '<td><strong>' . $row['Codigo'] . '</strong></td>';
  $html['tabla'] .= '<td><strong>' . $row['DescripcionTipo'] . ' ' .$row['DescripcionProducto'] . '</strong></td>';
  $html['tabla'] .= '<td><strong></strong>' . number_format($row['Cantidad'], 3) . '</td>';
  $html['tabla'] .= '<td><strong>Bs.</strong>' . number_format(floatval($row['Precio']), 2) . '</td>';
  $html['tabla'] .= '<td><strong>Bs.</strong>' . number_format(floatval($row['Precio'] * $row['Cantidad']), 2) . '</td>';
  $html['tabla'] .= '</tr>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);