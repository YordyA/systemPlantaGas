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
require_once 'inventarioPlantaMain.php';

$reponsable = $_SESSION['systemHarina']['nombreUsuario'];
$IDInvPlanta = desencriptar($_GET['id']);
$consulta = inventarioPlantaVerificarXID([$IDInvPlanta]);
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

if ($consulta['Existencia'] < $cant) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => " EXISTENCIA INSUFICIENTE",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

inventarioPlantaRetirarExistencia([$cant, $IDInvPlanta]);
inventarioPlantaRegistrarMovimiento(
  [
    date('Y-m-d'),
    2,
    $IDInvPlanta,
    $consulta['Existencia'],
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