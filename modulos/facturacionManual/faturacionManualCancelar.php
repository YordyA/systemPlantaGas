<?php
require_once '../sessionStart.php';

unset($_SESSION['facturaManual']['detalle']);
$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡FACTURACION CANCELADA!",
  "texto"   => "LA FACTURACION HA SIDO CANCELDAD CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
