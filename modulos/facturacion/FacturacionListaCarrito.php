<?php
require_once '../sessionStart.php';
require_once '../dependencias.php';
$tasaRef = floatval($_SESSION['PlantaGas']['Dolar']);
$tasCopRef = floatval(($_SESSION['PlantaGas']['IDPlanta'] == 2) ? 4300 : 5000);

$html = array();
$html['tabla'] = '';

if (isset($_SESSION['carritoVenta']['cliente'])) {
  $arrayCliente = $_SESSION['carritoVenta']['cliente'];

  $html['rif'] = $_SESSION['carritoVenta']['cliente']['rif'];
  $html['cliente'] = $_SESSION['carritoVenta']['cliente']['cliente'];
  $montoAbonadoBs = $_SESSION['carritoVenta']['cliente']['montoAbonadoBs'];
  $montoAbonadoUsd = floatval(round($montoAbonadoBs / $tasaRef, 2));
  $montoAbonadoCop = floatval(round($montoAbonadoUsd * $tasCopRef, 2));
} else {
  $html['rif'] = 'SIN CLIENTE';
  $html['cliente'] = 'SIN CLIENTE';
  $montoAbonadoBs = 0;
  $montoAbonadoUsd = 0;
  $montoAbonadoCop = 0;
}

$total = floatval(0);
$totalBs = floatval(0);
$totalUsd = floatval(0);
$totalCop = floatval(0);
if (isset($_SESSION['carritoVenta']['productos'])) {
  foreach ($_SESSION['carritoVenta']['productos'] as $i => $row) {
    $total += floatval($row['subtotal']);

    $html['tabla'] .= '<tr>';
    $html['tabla'] .= '<td><strong>' . $row['descripcion'] . '</strong></td>';
    $html['tabla'] .= '<td>
                        <button class="btn btn-outline-success editarPrecio" value="' . $i . '">
                          <strong>Bs.</strong>' . number_format($row['precio'], 2) . '
                        </button>
                      </td>';
    $html['tabla'] .= '<td>
                          <button type="button" class="btn btn-outline-danger editar" value="' . $i . '">
                            <strong>' . number_format($row['cantidad'], 3) . '</strong>
                          </button>
                      </td>';
    $html['tabla'] .= '<td><strong>Bs.</strong>' . number_format($row['subtotal'], 2) . '</td>';
    $html['tabla'] .= '<td>
                          <button class="btn btn-lg quitar" value="' . $i . '">
                            <i class="lni lni-trash-can"></i>
                          </button>
                      </td>';
    $html['tabla'] .= '</tr>';
  }

  $totalBs = round(floatval($total), 2);
  $totalUsd = round(floatval($totalBs / $tasaRef), 2);
  $totalCop = round(floatval($totalUsd * $tasCopRef), 2);
  $_SESSION['carritoVenta']['cliente']['totalVenta'] = floatval($totalBs);
} else {
  $html['tabla'] .= '<tr>';
  $html['tabla'] .= '<td colspan="5" class="text-center">No hay informacion</td>';
  $html['tabla'] .= '</tr>';
}

//^ MEDIOS DE PAGO
if (isset($_SESSION['carritoVenta']['cliente'])) {
  $html['medioPagos'] = array(
    'efectivo' => number_format($arrayCliente['montoEfectivoBs'], 2),
    'biopago' => number_format($arrayCliente['montoBiopagoBs'], 2),
    'tarjeta' => number_format($arrayCliente['montoPuntoVentaBs'], 2),
    'transferencia' => number_format($arrayCliente['montoTotalPagoMovil'], 2),
    'divisasUsd' => number_format($arrayCliente['montoTotalUsd'], 2),
    'divisasCop' => number_format($arrayCliente['montoTotalCop'], 2)
  );
} else {
  $html['medioPagos'] = array(
    'efectivo' => '0.00',
    'biopago' =>  '0.00',
    'tarjeta' =>  '0.00',
    'transferencia' => '0.00',
    'divisasUsd' => '0.00',
    'divisasCop' => '0.00'
  );
}

//^ BILLETES VUELTO USD
if (isset($_SESSION['carritoVenta']['cliente'])) {
  $html['billeteVuelto'] = $arrayCliente['billetesUsdVuelto'];
} else {
  $html['billeteVuelto'] = array(
    1 => 0,
    2 => 0,
    5 => 0,
    10 => 0,
    20 => 0,
    50 => 0,
    100 => 0
  );
}

//^ BILLETES VUELTO COP
if (isset($_SESSION['carritoVenta']['cliente'])) {
  $html['billeteVueltoCop'] = $arrayCliente['billetesCopVuelto'];
} else {
  $html['billeteVueltoCop'] = array(
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
}

//^ TOTALES
if (isset($_SESSION['carritoVenta']['cliente'])) {
  $html['abonado'] = '
                    <div class="alert ' . ($montoAbonadoBs >= $totalBs ? "alert-success" : "alert-danger") . '">
                      <strong class="text-dark">Bs. ' . number_format($montoAbonadoBs, 2) . '</strong>
                        <br>
                      <strong class="text-dark">$. ' . number_format($montoAbonadoUsd, 2) . '</strong>
                        <br>
                      <strong class="text-dark">Cop. ' . number_format($montoAbonadoCop, 2) . '</strong>
                    </div>';
  if ($totalBs > $montoAbonadoBs) {
    $html['vueltoFalta'] = '<h2 class="d-flex justify-content-between text-bold">
                            FALTA
                            <div class="alert alert-danger">
                              <strong class="text-dark">Bs. ' . number_format($totalBs - $montoAbonadoBs, 2) . '</strong>
                                <br>
                              <strong class="text-dark">$. ' . number_format($totalUsd - $montoAbonadoUsd, 2) . '</strong>
                                <br>
                              <strong class="text-dark">Cop. ' . number_format($totalCop - $montoAbonadoCop, 2) . '</strong>
                            </div>
                          </h2>';
  } else {
    $html['vueltoFalta'] = '<h2 class="d-flex justify-content-between text-bold">
                            VUELTO
                            <div class="alert alert-warning">
                              <strong class="text-dark">Bs. ' . number_format($montoAbonadoBs - $totalBs, 2) . '</strong>
                                <br>
                              <strong class="text-dark">$. ' . number_format($montoAbonadoUsd - $totalUsd, 2) . '</strong>
                                <br>
                              <strong class="text-dark">Cop. ' . number_format($montoAbonadoCop - $totalCop, 2) . '</strong>
                            </div>
                          </h2>';
  }
} else {
  $html['abonado'] = '<div class="alert">
                        <strong class="text-dark">Bs. 0.00</strong>
                          <br>
                        <strong class="text-dark">$. 0.00</strong>
                          <br>
                        <strong class="text-dark">Cop. 0.00</strong>
                      </div>';
  $html['vueltoFalta'] = '<h2 class="d-flex justify-content-between text-bold">
                            VUELTO
                            <div class="alert ">
                              <strong class="text-dark">Bs. 0.00</strong>
                                <br>
                              <strong class="text-dark">$. 0.00</strong>
                                <br>
                              <strong class="text-dark">Cop. 0.00</strong>
                            </div>
                          </h2>';
}

if ($montoAbonadoBs > $totalBs) {
  $vuletoBS = floatval(round($montoAbonadoBs - $totalBs, 2));
  $vuletoUSD = floatval(round($montoAbonadoUsd - $totalUsd, 2));
  $vuletoCop = floatval(round($montoAbonadoCop - $totalCop, 2));
} else {
  $vuletoBS = floatval(0);
  $vuletoUSD = floatval(0);
  $vuletoCop = floatval(0);
}

if (isset($_SESSION['carritoVenta']['cliente'])) {
  $vueltoEntregadoUSDEnBS = floatval($arrayCliente['montoVueltoEntregadoUsd'] * $tasaRef);
  $vueltoEntregadoCOPEnBs = floatval(($arrayCliente['montoVueltoEntregadoCop'] / $tasCopRef) * $tasaRef);

  $vueltoEntregadoBSEnCop = floatval(round($arrayCliente['montoVueltoEntregadoBs'] / $tasaRef, 2) * $tasCopRef);
  $vueltoEntregadoUSDEnCop = floatval($arrayCliente['montoVueltoEntregadoUsd'] * $tasCopRef);

  $vueltoEntregadoCopEnUSd = floatval($arrayCliente['montoVueltoEntregadoCop'] / $tasCopRef);
  $vueltoEntregadoBSEnUSD = floatval($arrayCliente['montoVueltoEntregadoBs'] / $tasaRef);

  $html['SinFormatVueltoEntregadoBs'] = round($vuletoBS - ($vueltoEntregadoUSDEnBS + $vueltoEntregadoCOPEnBs + $arrayCliente['montoVueltoEntregadoBs']), 3);

  $html['vueltoEntregadoBs'] = number_format($arrayCliente['montoVueltoEntregadoBs'], 2);
  $html['vueltoEntregadoUsd'] = number_format($arrayCliente['montoVueltoEntregadoUsd'], 2);
  $html['vueltoEntregadoCop'] = number_format($arrayCliente['montoVueltoEntregadoCop'], 2);

  $html['vueltoAEntregarBs'] = number_format($vuletoBS - ($vueltoEntregadoUSDEnBS + $vueltoEntregadoCOPEnBs + $arrayCliente['montoVueltoEntregadoBs']), 3);
  $html['vueltoAEntregarUsd'] = number_format($vuletoUSD - ($vueltoEntregadoCopEnUSd + $vueltoEntregadoBSEnUSD + $arrayCliente['montoVueltoEntregadoUsd']), 3);
  $html['vueltoAEntregarCop'] = number_format(round($vuletoCop - ($arrayCliente['montoVueltoEntregadoCop'] + $vueltoEntregadoUSDEnCop + $vueltoEntregadoBSEnCop), 2), 3);
} else {
  $html['vueltoEntregadoUsd'] = 0;
  $html['vueltoEntregadoBs'] = 0;

  $html['vueltoAEntregadoUsd'] = 0;
  $html['vueltoAEntregadoBs'] = 0;
}

$html['totalUsd'] = number_format(floatval($totalUsd), 2);
$html['totalBs'] = number_format(floatval($totalBs), 2);
$html['totalCop'] = number_format(floatval($totalUsd * $tasCopRef), 2);

$html['totalFactura'] = round(floatval($totalBs), 2);

$html['totalAbonadoBs'] = round(floatval($montoAbonadoBs), 2);
$html['totalAbonadoUsd'] = round(floatval($montoAbonadoUsd), 2);
$html['totalAbonadoCop'] = round(floatval($montoAbonadoCop), 2);

$html['vueltoBs'] = number_format($vuletoBS, 2);
$html['vueltoUsd'] = number_format($vuletoUSD, 2);
$html['vueltoCop'] = number_format($vuletoCop, 2);

echo json_encode($html, JSON_UNESCAPED_UNICODE);
