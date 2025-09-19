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
require_once 'clientesMain.php';

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDCliente = desencriptar($_GET['id']);
$consulta = clientesVerificarXID([$IDCliente]);
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

clientesActualizarEstado([0, ($fechaHoraModificacion . ' - ' . $responsable), $IDCliente]);
$alerta = [
  "alerta"  => "actualizacion",
  "titulo"  => "!CLIENTE ELIMINADO!",
  "texto"   => "EL CLIENTE HA SIDO ELIMINADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
