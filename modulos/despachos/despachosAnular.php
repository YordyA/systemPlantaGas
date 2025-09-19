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
require_once '../inventarioPlanta/inventarioPlantaMain.php';
require_once '../inventarioProduccion/inventarioProduccionMain.php';
require_once 'despachosMain.php';

$IDDespachoResumen = desencriptar($_GET['id']);
$clave = limpiarCadena($_POST['clave']);
$consultaUsuario = usuariosVerificarXID([$_SESSION['systemPlantaAba']['IDUsuario']]);
if ($consultaUsuario->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL USUARIO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaUsuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

$consultaDespacho = despachosConsultarDespachoDetalladoXID([$IDDespachoResumen]);
if ($consultaDespacho->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL DESPACHO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaDespacho = $consultaDespacho->fetchAll(PDO::FETCH_ASSOC);

foreach ($consultaDespacho as $row) {
  $consultaProducto = ($row['IDInvProduccion'] == null ? inventarioPlantaVerificarXID([$row['IDInvPlanta']]) : inventarioProduccionVerificarXID([$row['IDInvProduccion']]))->fetch(PDO::FETCH_ASSOC);
  ($row['IDInvProduccion'] == null) ? inventarioPlantaRellenarExistencia([$row['CantDesp'], $row['IDInvPlanta']]) : inventarioProduccionRellenarExistencia([$row['CantDesp'], $row['IDInvProduccion']]);
  $datosRegMov = [
    date('Y-m-d'),
    1,
    $row['IDInvProduccion'] == null ? $row['IDInvPlanta'] : $row['IDInvProduccion'],
    $consultaProducto['Existencia'],
    $row['CantDesp'],
    $row['IDInvProduccion'] == null ? $row['IDInvPlanta'] : $row['IDInvProduccion'],
    'ANULACION DESPACHO DE ' . $row['DescripcionTipoDesp'] . ' NRO ' . generarCeros($row['NroNota'], 5) . ' CLIENTE ' . $row['RazonSocial'],
    $consultaUsuario['NombreUsuario']
  ];
  ($row['IDInvProduccion'] == null) ? inventarioPlantaRegistrarMovimiento($datosRegMov) : inventarioProduccionRegistrarMovimiento($datosRegMov);
}

despachosActualizarEstado([0, ($fechaHoraModificacion . ' - ' . $consultaUsuario['NombreUsuario']), $IDDespachoResumen]);
$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡DESPACHO ANUALADO!",
  "texto"   => "EL DESPACHO HA SIDO ANUALADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);