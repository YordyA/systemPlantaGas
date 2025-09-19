<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once 'inventarioMain.php';

$IDPlanta = $_SESSION['PlantaGas']['IDPlanta'];
$descripcionAlmacen = strtoupper(limpiarCadena($_POST['descripcionAlmacen']));

if ($descripcionAlmacen == '' ) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$IDAlmacen = almacenRegistrar([$IDPlanta, $descripcionAlmacen]);

$alerta = [
  "alerta"  => "limpiar",
  "titulo"  => "¡ALMACEN REGISTRADO!",
  "texto"   => "EL ALMACEN HA SIDO REGISTRADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);