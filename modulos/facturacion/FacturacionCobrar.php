<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
if (!isset($_SESSION['carritoVenta']['cliente']) || !isset($_SESSION['carritoVenta']['productos'])) {
  echo json_encode([false, 'NO HAY VENTA']);
  exit();
}

$tasaRef = floatval($_SESSION['PlantaGas']['Dolar']);
$tasCopRef = floatval(($_SESSION['PlantaGas']['IDPlanta'] == 2) ? 4300 : 5000);

$arrayCliente = $_SESSION['carritoVenta']['cliente'];

$modal = limpiarCadena($_GET['mp']);
$montoAbonadoBs = (isset($_POST['cantidad'])) ? limpiarCadena(round(floatval($_POST['cantidad']), 2)) : 0;

if ($modal == 2) {
  $_SESSION['carritoVenta']['cliente']['montoEfectivoBs'] += round(floatval($montoAbonadoBs), 2);
} else if ($modal == 3) {
  if ($arrayCliente['totalVenta'] < ($montoAbonadoBs + $arrayCliente['montoBiopagoBs'] + $arrayCliente['montoPuntoVentaBs'] + $arrayCliente['montoTotalPagoMovil'])) {
    echo json_encode([false, '¡EL MONTO ABONADO NO PUEDE SER MAYOR A TOTAL DE LA VENTA!']);
    exit();
  }

  $_SESSION['carritoVenta']['cliente']['montoBiopagoBs'] += round(floatval($montoAbonadoBs), 2);
} else if ($modal == 4) {
  if ($arrayCliente['totalVenta'] < ($montoAbonadoBs + $arrayCliente['montoBiopagoBs'] + $arrayCliente['montoPuntoVentaBs'] + $arrayCliente['montoTotalPagoMovil'])) {
    echo json_encode([false, '¡EL MONTO ABONADO NO PUEDE SER MAYOR A TOTAL DE LA VENTA!']);
    exit();
  }

  $_SESSION['carritoVenta']['cliente']['montoPuntoVentaBs'] += round(floatval($montoAbonadoBs), 2);
  $_SESSION['carritoVenta']['cliente']['montoMedioPagosElectronicos'] += floatval($montoAbonadoBs);
} else if ($modal == 5) {
  $cedulaPagador = limpiarCadena($_POST['cedula']);
  $telefonoPagador = limpiarCadena($_POST['telefono']);
  $referencia = limpiarCadena($_POST['referencia']);
  $fechaPago = date('Y-m-d');
  $banco = limpiarCadena($_POST['banco']);

  if ($cedulaPagador == '' || $telefonoPagador == '' || $referencia == '' || $fechaPago == '' || $banco == '') {
    echo json_encode([false, 'TODO LOS CAMPOS SON REQUERIDOS']);
    exit();
  }

  if ($arrayCliente['totalVenta'] < ($montoAbonadoBs + $arrayCliente['montoBiopagoBs'] + $arrayCliente['montoPuntoVentaBs'] + $arrayCliente['montoTotalPagoMovil'])) {
    echo json_encode([false, '¡EL MONTO ABONADO NO PUEDE SER MAYOR A TOTAL DE LA VENTA!']);
    exit();
  }

  $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_PORT => "443",
    CURLOPT_URL => "https://bdvconciliacion.banvenez.com:443/getMovement",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n  \"cedulaPagador\": \"$cedulaPagador\",\n  \"telefonoPagador\": \"$telefonoPagador\",\n  \"telefonoDestino\": \"04145790251\",\n  \"referencia\": \"$referencia\",\n  \"fechaPago\": \"$fechaPago\",\n  \"importe\": \"$montoAbonadoBs\",\n  \"bancoOrigen\": \"$banco\"\n}",
    CURLOPT_HTTPHEADER => [
          "X-API-Key: C5AD11C3D276B8F746FA1CA21BBF5FDB",
      "content-type: application/json"
    ],
  ]);
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
    echo json_encode([false, 'BDV NO RESPONDE']);
    exit();
  } else {
    $response =  json_decode($response, true);
  }

  if ($response['code'] != 1000) {
    echo json_encode([false, 'PAGO MOVIL NO CONFIRMADO', $response['code']]);
    exit();
  }

  $_SESSION['carritoVenta']['cliente']['referenciasMontosPagoMovil'] .= 'R= ' . $referencia . 'M= ' . number_format($montoAbonadoBs, 2) . '//';
  $_SESSION['carritoVenta']['cliente']['montoTotalPagoMovil'] += round(floatval($montoAbonadoBs), 2);
} else if ($modal == 9) {
  $IDBilleteCOP = floatval(Desencriptar(limpiarCadena($_GET['id'])));
  $montoAbonadoBs += floatval(round(($IDBilleteCOP / $tasCopRef) * $tasaRef, 2));
  $_SESSION['carritoVenta']['cliente']['montoTotalCop'] += $IDBilleteCOP;
  $_SESSION['carritoVenta']['cliente']['billetesCop'][$IDBilleteCOP] += 1;
} else if ($modal == 7) {
  $referencia = limpiarCadena($_POST['referencia']);

  if ($referencia == '') {
    echo json_encode([false, 'TODO LOS CAMPOS SON REQUERIDOS']);
    exit();
  }

  if ($arrayCliente['totalVenta'] < ($montoAbonadoBs + $arrayCliente['montoBiopagoBs'] + $arrayCliente['montoPuntoVentaBs'] + $arrayCliente['montoTotalPagoMovil'])) {
    echo json_encode([false, '¡EL MONTO ABONADO NO PUEDE SER MAYOR A TOTAL DE LA VENTA!']);
    exit();
  }

  $_SESSION['carritoVenta']['cliente']['referenciasMontosPagoMovil'] .= 'R= ' . $referencia . 'M= ' . number_format($montoAbonadoBs, 2) . '//';
  $_SESSION['carritoVenta']['cliente']['montoTotalPagoMovil'] += round(floatval($montoAbonadoBs), 2);
} else if ($modal == 6) {
  $IDBilleteUSD = floatval(Desencriptar(limpiarCadena($_GET['id'])));
  $_SESSION['carritoVenta']['cliente']['montoTotalUsd'] += $IDBilleteUSD;
  $montoAbonadoBs = floatval(round($IDBilleteUSD * $tasaRef, 2));
  $_SESSION['carritoVenta']['cliente']['billetesUsd'][$IDBilleteUSD] += 1;
}

$_SESSION['carritoVenta']['cliente']['montoAbonadoBs'] += $montoAbonadoBs;
echo json_encode([true, 'MONTO ABONADO']);
