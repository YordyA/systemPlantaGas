<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../dependencias.php';
$consulta = consultarVentaPorNventa([Desencriptar(LimpiarCadena($_GET['id']))]);
$consulta = $consulta->fetchAll();

$html = array();

$html['tabla'] = '';

$html['NroVenta'] = $consulta[0]['NVentaResumen'];
$html['NroFactura'] = $consulta[0]['NFacturaFiscal'];
$html['Rif'] = $consulta[0]['RifCliente'];
$html['Nombre'] = $consulta[0]['NombreCliente'];

$estatus = array(
  '<span class="status-btn success-btn">CANCELADA</span>',
  '<span class="status-btn warning-btn">POR CANCELAR</span>',
  '<span class="status-btn close-btn">ANULADA</span>'
);

$total = 0;
$html['Estatus'] = $estatus[$consulta[0]['Estatus']];

foreach ($consulta as $row) {
  $total += floatval($row['SubTotal']);
  $html['tabla'] .= '<tr>';
  $html['tabla'] .= '<td><strong>' . $row['Codigo'] . '</strong></td>';
  $html['tabla'] .= '<td><strong>' . $row['DescripcionTipo'] . ' '. $row['DescripcionProducto'] . '</strong></td>';
  $html['tabla'] .= '<td><strong></strong>' . number_format($row['Cantidad'], 3) . '</td>';
  $html['tabla'] .= '<td><strong>Bs </strong>' . number_format($row['Precio'], 2) . '</td>';
  $html['tabla'] .= '<td><strong>Bs </strong>' . number_format($row['SubTotal'], 2) . '</td>';
  $html['tabla'] .= '</tr>';
}
$html['tabla'] .= '<tr>';
$html['tabla'] .= '<td></td>';
$html['tabla'] .= '<td></td>';
$html['tabla'] .= '<td></td>';
$html['tabla'] .= '<td><strong>TOTAL FACTURA:</strong></td>';
$html['tabla'] .= '<td><strong>Bs ' . number_format($total, 2) . '</strong></td>';
$html['tabla'] .= '</tr>';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
