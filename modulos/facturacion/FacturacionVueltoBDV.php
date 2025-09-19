<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'FacturacionMain.php';

if ($_SESSION['PlantaGas']['IDPlanta'] == 2) {
  echo json_encode([false, '¡NO PERMITIDO!']);
  exit();
}

$infoCliente = extraerPrimerCaracter($_SESSION['carritoVenta']['cliente']['rif']);
$tipoIdentificacion = $infoCliente[0];
$cedulaDestino =  explode($tipoIdentificacion, $_SESSION['carritoVenta']['cliente']['rif'])[1];
$tlfDestino = LimpiarCadena($_POST['tlfDestino']);
$bancoDestino = LimpiarCadena($_POST['banco']);

$tasaRef = $_SESSION['PlantaGas']['Dolar'];
$tasCopRef = floatval(($_SESSION['PlantaGas']['IDPlanta'] == 2) ? 4300 : 5000);
$fecha = date('Y-m-d');
$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];
$IDCaja = $_SESSION['PlantaGas']['NroCaja'];
$responsable = $_SESSION['PlantaGas']['NombreUsuario'];

$conceptoSucursales = array(
  1 => 'FRIGORICO MANTECAL',
  2 => 'FRIGORICO ELORZA',
  3 => 'FRIGORICO SF1',
  4 => 'FRIGORICO SF2',
  5 => 'FRIGORICO BIRUACA',
);

$referencia = facturaGeneraNroReferencia();

if ($tlfDestino == '' || $bancoDestino == '') {
  echo json_encode([false, '¡TODO LOS CAMPOS SON REQUERIDOS!']);
  exit();
}

$vuletoFactura = floatval(round($_SESSION['carritoVenta']['cliente']['montoAbonadoBs'] - $_SESSION['carritoVenta']['cliente']['totalVenta'], 2));

$montoVueltoUSD = floatval(round($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoUsd'] * $tasaRef, 2));
$montoVueltoCOP = floatval(round(($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoCop'] / $tasCopRef) * $tasaRef, 2));
$montoVueltoBS = floatval($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoBs']);
$vuletoEntregado = floatval($montoVueltoBS + $montoVueltoCOP + $montoVueltoUSD);

$montoVuelto = floatval(round($vuletoFactura - $vuletoEntregado, 2));

if ($montoVuelto > 600) {
  echo json_encode([false, '¡MONTO NO VALIDO!']);
  exit();
}

$referenciaBDV = generarCerosIzquierda($referencia, 12);

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://bdvconciliacion.banvenez.com/api/vuelto',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => "{ \n \"numeroReferencia\":\"$referenciaBDV\", \n \"montoOperacion\":\"$montoVuelto\", \n \"nacionalidadDestino\":\"$tipoIdentificacion\", \n \"cedulaDestino\":\"$cedulaDestino\", \n \"telefonoDestino\":\"$tlfDestino\", \n \"bancoDestino\":\"$bancoDestino\", \n \"moneda\":\"VES\", \n\"conceptoPago\":\"$conceptoSucursales[$IDSucursal]\" \n} ",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: 975125547930B1157194E4BA495D048F",
    "content-type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
  echo json_encode([false, '!OCURRIO UN ERROR INESPERADO¡']);
  exit();
}

$response = json_decode($response, true);

if ($response['code'] != 1000) {
  echo json_encode([false, $response]);
  exit();
}

$IDPagoMovil = facturacionRegistrarPagoMovil(
  [
    $fecha,
    $IDSucursal,
    $IDCaja,
    0,
    $_SESSION['carritoVenta']['cliente']['rif'],
    $tlfDestino,
    $bancoDestino,
    $montoVuelto,
    $response['referencia'],
    $conceptoSucursales[$IDSucursal],
    $responsable
  ]
);

$_SESSION['carritoVenta']['cliente']['IDPagoMovil'] = $IDPagoMovil;
$_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoPagoMovil'] = floatval($montoVuelto);
echo json_encode([true, $response]);
