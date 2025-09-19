<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../reportes/reportes_main.php';
require_once '../inventario/inventario_main.php';
require_once 'FacturacionMain.php';
require_once '../dependencias.php';

if (!isset($_GET['id']) || $_GET['id'] == '') {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "¡OCURRIO UN ERROR INESPERADO!",
    "texto" => "TODOS LOS CAMPOS SON OBLIGATORIOS",
    "tipo" => "error"
  );
  echo json_encode($alerta);
  exit();
}

if (isset($_SESSION['carritoVenta']['cliente']) && isset($_SESSION['carritoVenta']['productos'])) {
  unset($_SESSION['carritoVenta']);
}

$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];

$consulta = consultarFacturasEnEspera([$IDSucursal, LimpiarCadena(Desencriptar($_GET['id']))]);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

$productoSinExistencia = '';
foreach ($consulta as $row) {
  $producto = VerificarArticulosPorIDYNombre([$row['IDArticulo'], $IDSucursal])->fetch(PDO::FETCH_ASSOC);

  if ($producto['ExistenciaArticulo'] < round(floatval($row['Cantidad']), 3)) {
    $productoSinExistencia .= $producto['DescripcionArticulo'] . "\n";
  }
}

if ($productoSinExistencia != '') {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "!EXISTENCIA INSUFICIENTE¡",
    "texto" => $productoSinExistencia,
    "tipo" => "error"
  );
  echo json_encode($alerta);
  exit();
}

$_SESSION['carritoVenta']['cliente'] = [
  'IDcliente' => $consulta[0]['IDCliente'],
  'rif' => $consulta[0]['RifCliente'],
  'cliente' => $consulta[0]['NombreCliente'],
  //! TOTLA VENTA
  'totalVenta' => floatval(0),
  //! MONTO
  'montoEfectivoBs' => floatval(0),
  'montoBiopagoBs' => floatval(0),
  'montoPuntoVentaBs' => floatval(0),
  'montoTotalPagoMovil' => floatval(0),
  'montoTotalUsd' => floatval(0),
  'montoTotalCop' => floatval(0),
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

foreach ($consulta as $row) {
  $_SESSION['carritoVenta']['productos'][] = [
    'IDproducto' => $row['IDArticulo'],
    'descripcion' => $row['DescripcionArticulo'],
    'precio' => round(floatval($row['Precio']), 2),
    'cantidad' => round(floatval($row['Cantidad']), 3),
    'subtotal' => round(floatval($row['Precio'] * $row['Cantidad']), 2),
    'alicuota' => $row['IDAlicuota']
  ];
  $_SESSION['carritoVenta']['productos'] = array_reverse($_SESSION['carritoVenta']['productos']);
}

if (eliminarFacturaEnEspera([$consulta[0]['NFacturaEspera'], $IDSucursal])) {
  $alerta = array(
    "alerta" => "redireccionar",
    "url" => "Vender",
    "titulo" => "!FACTURA MONTADA¡",
    "texto" => "LA FACTURA FUE MONTADA CON EXITO",
    "tipo" => "success"
  );
  echo json_encode($alerta);
}
