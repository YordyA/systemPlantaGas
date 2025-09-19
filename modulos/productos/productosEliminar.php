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
require_once 'cisternasMain.php';

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDCisterna = desencriptar($_GET['id']);
$consulta = cisternasVerificarXID([$IDCisterna]);
if ($consulta->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL USUARIO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

cisternasActualizarEstado([0, ($fechaHoraModificacion . ' - ' . $responsable), $IDCisterna]);
$alerta = [
  "alerta"  => "actualizacion",
  "titulo"  => "!CISTERNA ELIMINADA!",
  "texto"   => "LA CISTERNA HA SIDO ELIMINADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
