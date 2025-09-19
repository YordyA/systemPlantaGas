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
require_once 'cisternasMain.php';

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDCisterna = desencriptar($_GET['id']);
$consulta = cisternasVerificarXID([$IDCisterna]);
if ($consulta->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL CLIENTE NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

$tipoCisterna = desencriptar(limpiarCadena($_POST['tipo']));
$empresaC = strtoupper(limpiarCadena($_POST['propietario']));
$modelo = strtoupper(limpiarCadena($_POST['modelo']));
$capacidad = limpiarCadena($_POST['capacidad']);

if ($tipoCisterna == '' ||  $modelo == '' || $capacidad == '' || $empresaC == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
if ($tipoCisterna != 1 && $tipoCisterna != 2) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL TIPO DE CISTERNA ES INCORRECTO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

if ($tipoCisterna == 1) {
  $empresaC = RAZONSOCIAL;
}

cisternasActualizar([$tipoCisterna, $empresaC, $modelo, $capacidad, date('Y-m-d H:i:s') . ' - ' . $responsable, $IDCisterna]);

$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡CISTERNA ACTUALIZADA!",
  "texto"   => "LA CISTERNA HA SIDO ACTUALIZADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
