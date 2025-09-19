<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../clientes/clientesMain.php';
require_once '../inventario/inventarioMain.php';
require_once 'despachosMain.php';

if (!isset($_SESSION['despacho']['detalle'])) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$IDCliente = desencriptar($_GET['id']);
$choferCedula = limpiarCadena($_POST['choferCedula']);
$chofer = limpiarCadena($_POST['chofer']);
$IDTipoDesp = desencriptar($_POST['IDTipoDesp']);
$observacion = limpiarCadena($_POST['observacion']);
$responsable = $_SESSION['PlantaPescado']['nombreUsuario'];
$concepto = 'DESPACHO NRO  ';

if ($IDCliente == '' || $choferCedula == '' || $chofer == '' || $IDTipoDesp == '' || $observacion == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS " . $_GET['id'],
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$consultaCliente = clientesVerificarXID([$IDCliente]);
if ($consultaCliente->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL CLIENTE NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$consultaTipoDesp = despachosTiposVerificarXID([$IDTipoDesp]);
if ($consultaTipoDesp->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL TIPO DE DESPACHO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaTipoDesp = $consultaTipoDesp->fetch(PDO::FETCH_ASSOC);

foreach ($_SESSION['despacho']['detalle'] as $row) {
  $consulta = inventarioVerificarXID([$row['id']]);
  if ($consulta->rowCount() != 1) {
    $alerta = [
      "alerta"  => "simple",
      "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
      "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
      "tipo"    => "error"
    ];
    echo json_encode($alerta);
    exit();
  }
  $consulta = $consulta->fetch(PDO::FETCH_ASSOC);
}


$nroNota = despachosGenerarNroNota([$IDTipoDesp]);
$IDDespachoResumen = despachosRegistrarResumen(
  [
    date('Y-m-d'),
    $IDCliente,
    $IDTipoDesp,
    $nroNota,
    $chofer,
    $choferCedula,
    $observacion,
    $responsable
  ]
);


foreach ($_SESSION['despacho']['detalle'] as $row) {
  $consulta = inventarioVerificarXID([$row['id']]);
  if ($consulta->rowCount() != 1) {
    $alerta = [
      "alerta"  => "simple",
      "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
      "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
      "tipo"    => "error"
    ];
    echo json_encode($alerta);
    exit();
  }
  $consulta = $consulta->fetch(PDO::FETCH_ASSOC);
  inventarioExistenciaRetirar([$row['cantidad'], $row['id']]);
  inventarioExistenciaRegistrarMovimiento(
    [
      date('Y-m-d'),
      2,
      $row['id'],
      $consulta['Existencia'],
      $row['cantidad'],
      $row['id'],
      $concepto . $nroNota,
      $responsable
    ]
  );

  despachosRegistrarDetalle(
    [
      $IDDespachoResumen,
      $row['id'],
      null,
      $row['cantidad'],
      $row['precioVenta'],
      $row['valorAlicuota']
    ]
  );
}

unset($_SESSION['despacho']);
$alerta = [
  "alerta"  => "volver",
  "url"     => "modulos/pdf/PDFNotaDespachoUSD.php?id=" . encriptar($IDDespachoResumen),
  "titulo"  => "¡DESPACHO REGISTRADO!",
  "texto"   => "EL DESPACHO HA SIDO REGISTRADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
