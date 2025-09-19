<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'FacturacionMain.php';
require_once '../dependencias.php';

$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];
$NroCaja = $_SESSION['PlantaGas']['NroCaja'];
$fecha = date('Y-m-d');

$tasaRef = round($_SESSION['PlantaGas']['Dolar'], 3);
$tasCopRef = round(($_SESSION['PlantaGas']['IDPlanta'] == 2) ? 4500 : 5000, 3);

$tipoDeMoneda = LimpiarCadena($_GET['tpb']);
$IDBillete = Desencriptar(LimpiarCadena($_GET['id'])) ?? 0;

$vuletoAEntregar = round($_SESSION['carritoVenta']['cliente']['montoAbonadoBs'] - $_SESSION['carritoVenta']['cliente']['totalVenta'], 3);

$vuletoEntregado = round(
  round($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoUsd'] * $tasaRef, 3) +
    round(($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoCop'] / $tasCopRef) * $tasaRef, 3) +
    round($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoBs'], 3),
  3
);


if ($tipoDeMoneda == 1) {
  $consultarBillete = facturacionConsultarInventarioBilletesUSD([$fecha, $IDSucursal, $NroCaja]);
  $billetesUSD = array(
    1 => $consultarBillete['Billete1'],
    2 => $consultarBillete['Billete2'],
    5 => $consultarBillete['Billete5'],
    10 => $consultarBillete['Billete10'],
    20 => $consultarBillete['Billete20'],
    50 => $consultarBillete['Billete50'],
    100 => $consultarBillete['Billete100']
  );

  if ($billetesUSD[$IDBillete] < ($_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][$IDBillete] + 1)) {
    echo json_encode([false, '¡NO HAY SUFICIENTE BILLETE ' . $IDBillete . '$!']);
    exit();
  }

  if ($vuletoAEntregar < round(($vuletoEntregado + (floatval(round($IDBillete * $tasaRef, 3)))), 3)) {
    echo json_encode([false, '¡EL VULETO NO PUEDE SER MAYOR!']);
    exit();
  }

  $_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoUsd'] += $IDBillete;
  $_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][$IDBillete] += 1;

  echo json_encode([true, '¡BILLETE AGREGADO!']);
}

if ($tipoDeMoneda == 2) {
  $consultarBillete = facturacionConsultarInventarioBilletesCOP([$fecha, $IDSucursal, $NroCaja]);
  $billetesCOP = array(
    50 => $consultarBillete['Billete50'],
    100 => $consultarBillete['Billete100'],
    200 => $consultarBillete['Billete200'],
    500 => $consultarBillete['Billete500'],
    1000 => $consultarBillete['Billete1000'],
    2000 => $consultarBillete['Billete2000'],
    5000 => $consultarBillete['Billete5000'],
    10000 => $consultarBillete['Billete10000'],
    20000 => $consultarBillete['Billete20000'],
    50000 => $consultarBillete['Billete50000'],
    100000 => $consultarBillete['Billete100000']
  );

  if ($billetesCOP[$IDBillete] < ($_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][$IDBillete] + 1)) {
    echo json_encode([false, '¡NO HAY SUFICIENTE BILLETE ' . $IDBillete . 'COP!']);
    exit();
  }

  if ($vuletoAEntregar < ($vuletoEntregado + round(floatval($IDBillete / $tasCopRef) * $tasaRef, 3))) {
    echo json_encode([false, '¡EL VULETO NO PUEDE SER MAYOR!']);
    exit();
  }

  $_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoCop'] += $IDBillete;
  $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][$IDBillete] += 1;

  echo json_encode([true, '¡BILLETE AGREGADO!']);
}

if ($tipoDeMoneda == 3) {
  $montoEnBS = round(LimpiarCadena($_GET['id']), 3);
  if (round($vuletoEntregado + $montoEnBS, 2) > $vuletoAEntregar) {
    echo json_encode([false, '¡EL VULETO NO PUEDE SER MAYOR!']);
    exit();
  }

  $_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoBs'] += $montoEnBS;
  echo json_encode([true, '¡BILLETE AGREGADO!']);
}