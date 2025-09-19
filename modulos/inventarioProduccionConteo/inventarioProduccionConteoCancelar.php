<?php
require_once '../dependencias.php';
require_once '../sessionStart.php';

unset($_SESSION['conteoFisico']['datelle']);
$alerta = [
  "alerta"  => "simple",
  "titulo"  => "¡CONTEO CANCELADO!",
  "texto"   => "EL CONTEO HA SIDO CANCELADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
