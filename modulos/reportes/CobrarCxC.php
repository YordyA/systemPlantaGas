<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../cobrar/CobrarMain.php';



$consulta = VentasPorNroVenta([Desencriptar(LimpiarCadena($_GET['id']))]);
$consulta = $consulta->fetchAll();

$html = array();

$html['tabla'] = '';

$html['Efectivo'] = number_format($consulta[0]['Efectivo'], 2);
$html['Tarjeta'] = number_format($consulta[0]['Tarjeta'], 2);
$html['Biopago'] = number_format($consulta[0]['BioPago'], 2);
$html['PagoM'] = number_format($consulta[0]['Transferencia'], 2);
$html['Cruces'] = number_format($consulta[0]['CrucesFacturas'], 2);

number_format($suma = ($consulta[0]['Transferencia'] + $consulta[0]['Efectivo'] + $consulta[0]['Tarjeta'] + $consulta[0]['BioPago'] + $consulta[0]['CrucesFacturas']), 2);
if ($suma == '') {
  $a = 0;
  $html['Abonado'] = number_format($a, 2);
} else {
  $html['Abonado'] = number_format($suma, 2);
}
$html['TotalFactura'] = number_format($consulta[0]['TotalFactura'], 2);
$html['Restante'] = number_format($consulta[0]['TotalFactura'] - ($consulta[0]['Transferencia'] + $consulta[0]['Efectivo'] + $consulta[0]['Tarjeta'] + $consulta[0]['BioPago'] + $consulta[0]['CrucesFacturas']), 2);

$total = 0;
echo json_encode($html, JSON_UNESCAPED_UNICODE);
