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
require_once 'usuariosMain.php';

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDUsuario = desencriptar($_GET['id']);
$consulta = usuariosVerificarXID([$IDUsuario]);
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

usuariosActualizarEstado([0, ($fechaHoraModificacion . ' - ' . $responsable), $IDUsuario]);
$alerta = [
  "alerta"  => "actualizacion",
  "titulo"  => "!USUARIO ELIMINADO!",
  "texto"   => "EL USUARIO HA SIDO ELIMINADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);