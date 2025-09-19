<?php
if (!isset($_GET['id']) || $_GET['id'] == '' || !isset($_POST['clave']) || $_POST['clave'] == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
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
require_once '../inventarioProduccion/inventarioProduccionMain.php';
require_once 'inventarioProduccionConteoMain.php';

$nroConteo = desencriptar($_GET['id']);
$clave = limpiarCadena($_POST['clave']);
$consultaUsuario = usuariosVerificarXID([$_SESSION['PlantaGas']['IDUsuario']]);
if ($consultaUsuario->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
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

$consultaConteo = inventarioProduccionConteoConsultar([$nroConteo]);
if ($consultaConteo->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL CONTEO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaConteo = $consultaConteo->fetchAll(PDO::FETCH_ASSOC);

foreach ($consultaConteo as $row) {
  inventarioProduccionActualizarExistencia([$row['CantSistema'], $row['IDInvProduccion']]);
  inventarioProduccionRegistrarMovimiento(
    [
      date('Y-m-d'),
      ($row['Diferencia'] < 0 ? 1 : 2),
      $row['IDInvProduccion'],
      $row['CantSistema'],
      abs($row['Diferencia']),
      $row['IDInvProduccion'],
      'ANULACION DE CONTEO FISICO NRO ' . $row['NroConteo'] . ' - ANULADO POR ' . $consultaUsuario['NombreUsuario'],
      $consultaUsuario['NombreUsuario']
    ]
  );
}
inventarioProduccionConteoActualizarEstado([0, ($fechaHoraModificacion . ' - ' . $consultaUsuario['NombreUsuario']), $nroConteo]);

$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡CONTEO ANULADO!",
  "texto"   => "EL CONTEO HA SIDO ANULADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
