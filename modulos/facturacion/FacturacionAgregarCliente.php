<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../clientes/ClienteMain.php';
require_once '../dependencias.php';
$rif = LimpiarCadena($_GET['id']);

if ($rif == '') {
  echo  json_encode(0);
  exit();
}

if (verificarFormatoCliente($rif) == false) {
  echo json_encode(1);
  exit();
}

$consulta = VerificarClienteDucumentoFiscal($rif);
if ($consulta->rowCount() == 0) {
  echo json_encode(2);
  exit();
} else {
  $consulta = $consulta->fetch(PDO::FETCH_ASSOC);
}

if (!isset($_SESSION['carritoVenta']['cliente'])) {
  $_SESSION['carritoVenta']['cliente'] = [
    'IDcliente' => $consulta['IDCliente'],
    'rif' => $consulta['RifCliente'],
    'cliente' => $consulta['NombreCliente'],
    //! TOTLA VENTA
    'totalVenta' => floatval(0),
    //! MONTO
    'montoEfectivoBs' => floatval(0),
    'montoBiopagoBs' => floatval(0),
    'montoPuntoVentaBs' => floatval(0),
    'montoTotalPagoMovil' => floatval(0),
    'montoTotalUsd' => floatval(0),
    'montoTotalCop' => floatval(0),
    'montoMedioPagosElectronicos' => 0,
    //! REFERECIA
    'referenciasMontosPagoMovil' => '',
    //! MONTO TOTAL ABONADO
    'montoAbonadoBs' => floatval(0),
    //! CONTROL DE DOLARES
    'billetesUsd' => array(
      1 => 0,
      2 => 0,
      5 => 0,
      10 => 0,
      20 => 0,
      50 => 0,
      100 => 0
    ),
    'billetesUsdVuelto' => array(
      1 => 0,
      2 => 0,
      5 => 0,
      10 => 0,
      20 => 0,
      50 => 0,
      100 => 0
    ),
    //! CONTROL DE PESOS COLOMBIANOS
    'billetesCop' => array(
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
    ),
    'billetesCopVuelto' => array(
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
    ),
    'montoVueltoEntregadoUsd' => floatval(0),
    'montoVueltoEntregadoCop' => floatval(0),
    'montoVueltoEntregadoBs' => floatval(0),
    'montoVueltoEntregadoPagoMovil' => floatval(0),
    'IDPagoMovil' => 0
  ];
}else {
  $_SESSION['carritoVenta']['cliente']['rif'] = $consulta['IDCliente'];
  $_SESSION['carritoVenta']['cliente']['rif'] = $consulta['RifCliente'];
  $_SESSION['carritoVenta']['cliente']['cliente'] = $consulta['NombreCliente'];
}

echo json_encode(3);
