<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
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
require_once 'inventarioPlantaMain.php';

$responsable = $_SESSION['systemHarina']['nombreUsuario'];
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

inventarioPlantaRetirarExistencia([$consulta['Existencia'], $IDInvPlanta]);
inventarioPlantaRegistrarMovimiento(
  [
    date('Y-m-d'),
    2,
    $IDInvPlanta,
    $consulta['Existencia'],
    $consulta['Existencia'],
    $IDInvPlanta,
    'DESINCORPORACION COMPLETA DEL LOTE',
    $responsable
  ]
);
inventarioPlantaActualizarEstado([0, ($fechaHoraModificacion . ' - ' . $responsable), $IDInvPlanta]);
$alerta = [
  "alerta"  => "actualizacion",
  "titulo"  => "!LOTE ELIMINADO!",
  "texto"   => "EL LOTE HA SIDO ELIMINADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);