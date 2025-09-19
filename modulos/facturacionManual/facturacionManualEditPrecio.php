<?php
if (!isset($_GET['id']) || $_GET['id'] == '' || !isset($_GET['precio']) || $_GET['precio'] == '') {
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
require_once '../sessionStart.php';

$indice = Desencriptar($_GET['id']);
$precio = LimpiarCadena($_GET['precio']);
$datosItem = $_SESSION['facturaManual']['detalle'][$indice];

if ($datosItem['IDAlicuota'] == 2) {
  $precio = round($precio + ($precio * 0.16), 2);
}

$_SESSION['facturaManual']['detalle'][$indice]['precio'] = round($precio, 2);
$alerta = [
  "alerta"  => "simple",
  "titulo"  => "¡PRECIO AGREGADO!",
  "texto"   => "EL PRECIO HA SIDO AGREGADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
