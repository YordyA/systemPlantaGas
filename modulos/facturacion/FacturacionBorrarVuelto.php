<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
$_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoUsd'] = 0;
$_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'] = array(
  1 => 0,
  2 => 0,
  5 => 0,
  10 => 0,
  20 => 0,
  50 => 0,
  100 => 0
);

$_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoCop'] = 0;
$_SESSION['carritoVenta']['cliente']['billetesCopVuelto'] = array(
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
);

$_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoBs'] = 0;

echo json_encode(true);
