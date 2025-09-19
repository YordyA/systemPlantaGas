<?php
if (!isset($_GET['id']) || $_GET['id'] == '' || !isset($_POST['precioVenta']) || $_POST['precioVenta'] == '') {
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
$precioVenta = limpiarCadena($_POST['precioVenta']);
if (inventarioPlantaVerificarXID([$IDInvPlanta])->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

inventarioPlantaActualizarPrecio([$precioVenta, ($fechaHoraModificacion . ' - ' . $responsable), $IDInvPlanta]);
$alerta = [
  "alerta"  => "actualizacion",
  "titulo"  => "¡PRECIO ACTUALIZADO!",
  "texto"   => "EL PRECIO DE VENTA HA SIDO ACTUALIZADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
exit();