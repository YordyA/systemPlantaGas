<?php
require_once '../sessionStart.php';

if (!isset($_SESSION['carritoVenta']['cliente'])) {
  echo json_encode([false, '!NO HAY VENTA¡']);
  exit();
}

$_SESSION['carritoVenta']['cliente']['montoEfectivoBs'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['montoBiopagoBs'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['montoPuntoVentaBs'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['montoTotalPagoMovil'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['montoTotalUsd'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['montoTotalCop'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['montoMedioPagosElectronicos'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['referenciasMontosPagoMovil'] = '';
$_SESSION['carritoVenta']['cliente']['montoAbonadoBs'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['billetesUsd'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['billetesUsd'] = [
  1 => 0,
  2 => 0,
  5 => 0,
  10 => 0,
  20 => 0,
  50 => 0,
  100 => 0
];
$_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'] = [
  1 => 0,
  2 => 0,
  5 => 0,
  10 => 0,
  20 => 0,
  50 => 0,
  100 => 0
];
$_SESSION['carritoVenta']['cliente']['billetesCop'] = [
  50 => 0,
  100 => 0,
  200 => 0,
  500 => 0,
  1000 => 0,
  2000 => 0,
  5000 => 0,
  10000 => 0,
  20000 => 0,
  50000 => 0,
  100000 => 0
];
$_SESSION['carritoVenta']['cliente']['billetesCopVuelto'] = [
  50 => 0,
  100 => 0,
  200 => 0,
  500 => 0,
  1000 => 0,
  2000 => 0,
  5000 => 0,
  10000 => 0,
  20000 => 0,
  50000 => 0,
  100000 => 0
];
$_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoUsd'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoCop'] = floatval(0);
$_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoBs'] = floatval(0);

echo json_encode([true,'¡MEDIOS DE PAGO ELIMINADO!']);