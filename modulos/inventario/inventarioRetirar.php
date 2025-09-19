<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "¡OCURRIO UN ERROR INESPERADO!",
    "texto" => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo" => "error"
  );
  echo json_encode($alerta);
  exit();
}

require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once 'inventarioMain.php';

$reponsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDPlanta = $_SESSION['PlantaGas']['IDPlanta'];
$IDInvPlanta = desencriptar($_GET['id']);
$consulta = almacenVerificarXID([$IDInvPlanta]);
if ($consulta->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL PRODUCTO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

$cant = limpiarCadena($_POST['cant']);
$observacion = limpiarCadena($_POST['observacion']);
if ($cant == '' || $observacion == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

if ($consulta['Cantidad'] < $cant) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => " EXISTENCIA INSUFICIENTE",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

almacenCantidadRestar([$cant, $IDInvPlanta]);
MovimientosDeAlmacenRegistrar(
  [
    $IDPlanta,
    date('Y-m-d'),
    2,
    $IDInvPlanta,
    $consulta['Cantidad'],
    $cant,
    $IDInvPlanta,
    $observacion,
    $reponsable
  ]
);

$alerta = [
  "alerta"  => "actualizacion",
  "titulo"  => "¡EXISTENCIA RETIRADA!",
  "texto"   => "LA EXISTENCIA HA SIDO RETIRADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);