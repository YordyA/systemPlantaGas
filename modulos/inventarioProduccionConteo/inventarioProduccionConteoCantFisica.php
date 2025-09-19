<?php
if (!isset($_GET['id']) || $_GET['id'] == '' || !isset($_POST['cantFisica']) || $_POST['cantFisica'] == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
require_once '../dependencias.php';
require_once '../sessionStart.php';

$indice = desencriptar($_GET['id']);
$cantFisica = limpiarCadena($_POST['cantFisica']);

$_SESSION['conteoFisico']['datelle'][$indice]['cantFisica'] = $cantFisica;
$_SESSION['conteoFisico']['datelle'][$indice]['diferencia'] = round($cantFisica - $_SESSION['conteoFisico']['datelle'][$indice]['cantSistema'], 6);
$alerta = [
  "alerta"  => "simple",
  "titulo"  => "¡CANTIDAD FISICA INGRESA!",
  "texto"   => "LA CANTIDAD FISICA HA SIDO INGRESADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
