<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'cisternasMain.php';

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

cisternasRegistrar([$tipoCisterna, $empresaC, $modelo, $capacidad]);

$alerta = [
  "alerta"  => "limpiar",
  "titulo"  => "¡CISTERNA REGISTRADA!",
  "texto"   => "LA CISTERNA HA SIDO REGISTRADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
