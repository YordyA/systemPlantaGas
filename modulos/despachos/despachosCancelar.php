<?php
require_once '../dependencias.php';
require_once '../sessionStart.php';

unset($_SESSION['despacho']);
$alerta = [
  "alerta"  => "simple",
  "titulo"  => "!DESPACHO CANCELADO¡",
  "texto"   => "EL DESPACHO HA SIDO CANCELADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
