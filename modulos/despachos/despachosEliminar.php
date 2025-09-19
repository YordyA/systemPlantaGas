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

require_once '../sessionStart.php';
require_once '../dependencias.php';

$indice = desencriptar($_GET['id']);
if (count($_SESSION['despacho']['detalle']) == 1) {
  unset($_SESSION['despacho']['detalle'][$indice]);
  $titulo = '!PRODUCTO ELIMINADO¡';
  $sms = 'EL LOTE HA SIDO ELIMINADO CON EXITO';
} else {
  unset($_SESSION['despacho']);
  $titulo = '!DESPACHO CANCELADO¡';
  $sms = 'EL DESPACHO HA SIDO CANCELADO CON EXITO';
}

$alerta = [
  "alerta"  => "simple",
  "titulo"  => $titulo,
  "texto"   => $sms,
  "tipo"    => "success"
];
echo json_encode($alerta);
