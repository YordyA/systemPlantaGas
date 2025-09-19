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
require_once 'articulosMain.php';

$responsable = $_SESSION['systemHarina']['nombreUsuario'];
$IDArticulo = desencriptar($_GET['id']);
$consulta = articulosVerificarXID([$IDArticulo]);
if ($consulta->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "Titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL ARTICULO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

articulosActualizarEstado([0, ($fechaHoraModificacion . ' - ' . $responsable), $IDArticulo]);
$alerta = [
  "alerta"  => "actualizacion",
  "titulo"  => "!ARTICULO ELIMINADO!",
  "texto"   => "EL ARTICULO HA SIDO ELIMINADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);