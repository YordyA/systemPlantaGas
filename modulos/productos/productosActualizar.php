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
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once 'productosMain.php';

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDCisterna = desencriptar($_GET['id']);
$consulta = ProductosListaID([$IDCisterna]);
if ($consulta->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL PRODUCTO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);


$capacidad = limpiarCadena($_POST['precio']);

if ($capacidad == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

preciosActualizar([$capacidad, $IDCisterna]);

$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡PRECIO ACTUALIZADO!",
  "texto"   => "EL PRECIO FUE ACTUALIZADO",
  "tipo"    => "success"
];

echo json_encode($alerta);
