<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS COMPOS SON REQUERIDOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once 'inventarioMain.php';

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$fechaHoraModificacion = date('Y-m-d H:i:s');
$IDAlmacen = desencriptar($_GET['id']);
$consulta = almacenVerificarXID([$IDAlmacen]);
if ($consulta->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL ALMACEN NO EXISTE EN SISTEMA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

$descripcionAlmacen = strtoupper(limpiarCadena($_POST['descripcionAlmacen']));

if ($descripcionAlmacen == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

almacenActualizar([$descripcionAlmacen, ($fechaHoraModificacion . ' - ' . $responsable), $IDAlmacen]);
$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡ALMACEN ACTUALIZADO!",
  "texto"   => "EL ALMACEN FUE ACTUALIZADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);