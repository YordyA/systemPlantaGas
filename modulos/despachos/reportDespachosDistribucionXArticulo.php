<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'despachosMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$IDTipoDespacho = desencriptar($_GET['IDTipoDesp']);

$datos = [];
$productos = [];

$consulta = reportDespachoDistribucionXClienteYArticulo([$del, $hasta, $IDTipoDespacho])->fetchAll(PDO::FETCH_ASSOC);
foreach ($consulta as $row) {
  $razonSocial = $row['RazonSocialCliente'];
  $descripcionArticulo =  $row['DescripcionPresentacion'] . ' ' . $row['DescripcionProducto'];

  $datos[$razonSocial][$descripcionArticulo] = ($row['CantUnd']);
  $productos[$descripcionArticulo] = $descripcionArticulo;
}

// Construcción de la tabla
$html = '<table class="table table-sm text-center" id="tablaMain"><thead><tr><th class="text-center"><h6>CLIENTE</h6></th>';

// Encabezados de productos
foreach ($productos as $descripcionArticulo) {
  $html .= '<th class="text-center"><h6>' . htmlspecialchars($descripcionArticulo, ENT_QUOTES, 'UTF-8') . '</h6></th>';
}
$html .= '</tr></thead><tbody>';

// Filas de clientes y productos
foreach ($datos as $razonSocialCliente => $articulos) {
  $html .= '<tr><td>' . htmlspecialchars($razonSocialCliente, ENT_QUOTES, 'UTF-8') . '</td>';

  foreach ($productos as $descripcionArticulo) {
    $html .= '<td>' . number_format($articulos[$descripcionArticulo] ?? 0, 2) . '</td>';
  }

  $html .= '</tr>';
}

$html .= '</tbody></table>';
echo json_encode($html, JSON_UNESCAPED_UNICODE);
