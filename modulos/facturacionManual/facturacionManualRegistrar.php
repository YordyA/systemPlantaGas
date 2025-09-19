<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../cobrar/CobrarMain.php';
require_once '../reportes/reportes_main.php';
require_once 'facturacionManualMain.php';

if (!isset($_GET['id']) || $_GET['id'] == '' || !isset($_SESSION['facturaManual']['detalle']) || count($_SESSION['facturaManual']['detalle']) == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$IDResumenVenta = Desencriptar($_GET['id']);
$consulta = consultarVentaPorNventa([$IDResumenVenta]);
if ($consulta->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA ENTREGA DE PRODUCTO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

$fecha = LimpiarCadena($_POST['fecha']);
$serie = LimpiarCadena($_POST['serie']);
$nroFactura = LimpiarCadena($_POST['nroFactura']);

$efectivo = LimpiarCadena($_POST['efectivo']);
$tarjeta = LimpiarCadena($_POST['tarjeta']);
$biopago = LimpiarCadena($_POST['biopago']);
$transferencia = LimpiarCadena($_POST['transferencia']);

if ($fecha == '' || $serie == '' || $efectivo == '' || $tarjeta == '' || $biopago == '' || $transferencia == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

if (date('Y-m-d') != $fecha) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA FECHA DE LA FACTURA NO PUEDE SER INFERIOR AL DIA ACTUAL",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$montTotalFactura = 0;
$montoExento = 0;
$montoGravado = 0;
foreach ($_SESSION['facturaManual']['detalle'] as $indice => $row) {
  $subTotal = round($row['cantidad'] * $row['precio'], 2);
  facturacionManualActualizarDatosItem([$row['precio'], $subTotal, $indice]);

  $montTotalFactura += $subTotal;
  if ($row['IDAlicuota'] == 1) {
    $montoExento += $subTotal;
  } else if ($row['IDAlicuota'] == 2) {
    $montoGravado += $subTotal;
  }
}

if ($montTotalFactura != round($efectivo + $tarjeta + $biopago + $transferencia, 2)) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA SUMA DE LOS MEDIO DE PAGO TIENE QUE SE IGUAL AL MONTO TOTAL DE LA FACTURA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

facturacionManualActualizarDatos(
  [
    $fecha,
    $fecha,
    $montTotalFactura,
    $montoExento,
    $montoGravado,
    round($montoGravado - round($montoGravado - ($montoGravado / 1.16), 2), 2),
    $nroFactura,
    $serie,
    $IDResumenVenta
  ]
);

facturacionManualActualizarMedioPago(
  [
    $efectivo,
    $tarjeta,
    $biopago,
    $transferencia,
    $IDResumenVenta
  ]
);

$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡FACTURA REGISTRADA!",
  "texto"   => "LA FACTURA HA SIDO REGISTRADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
