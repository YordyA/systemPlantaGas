<?php
if (!isset($_GET['id']) || $_GET['id'] == '' || !isset($_POST['clave']) || $_POST['clave'] == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../usuarios/usuariosMain.php';
require_once '../inventarioPlanta/inventarioPlantaMain.php';
require_once '../inventarioProduccion/inventarioProduccionMain.php';
require_once 'produccionMain.php';

$IDProduccionResumen = desencriptar($_GET['id']);
$clave = limpiarCadena($_POST['clave']);
$consultaUsuario = usuariosVerificarXID([$_SESSION['PlantaGas']['IDUsuario']]);
if ($consultaUsuario->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
    "texto"   => "EL USUARIO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaUsuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

if (!password_verify($clave, $consultaUsuario['Clave'])) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA CLAVE INGRESADA NO COINCIDE",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$consultaProduccion = produccionConsultarXID([$IDProduccionResumen]);
if ($consultaProduccion->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA PRODUCCION NO SE ENCUENTRA REGISTRADA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaProduccion = $consultaProduccion->fetchAll(PDO::FETCH_ASSOC);

foreach ($consultaProduccion as $row) {
  $consultaProducto = inventarioProduccionVerificarXID([$row['IDInvProduccion']]);

  if ($consultaProducto->rowCount() != 1) {
    $alerta = [
      "alerta"  => "simple",
      "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
      "texto"   => "EL PRODUCTO NO SE ENCUENTRA REGISTRADO",
      "tipo"    => "error"
    ];
    echo json_encode($alerta);
    exit();
  }
  $consultaProducto = $consultaProducto->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRellenarExistencia([$row['CantidadUtilizada'], $row['IDInvProduccion']]);
  inventarioProduccionRegistrarMovimiento(
    [
      date('Y-m-d'),
      1,
      $row['IDInvProduccion'],
      $consultaProducto['Existencia'],
      $row['CantidadUtilizada'],
      $row['IDInvProduccion'],
      'ANULACION DE PRODUCCION NRO DE LOTE ' . generarCeros($row['NroLote'], 5),
      $consultaUsuario['NombreUsuario']
    ]
  );
}

produccionActualizarEstadoProduccion([0, $fechaHoraModificacion . ' - ' . $consultaUsuario['NombreUsuario'], $IDProduccionResumen]);
$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡PRODUCCION ANULADA!",
  "texto"   => "LA PRODUCCION HA SIDO ANULADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
