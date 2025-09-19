<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'clientesMain.php';

$rifCedula = strtoupper(limpiarCadena($_POST['rifCedula']));
$razonSocial = strtoupper(limpiarCadena($_POST['razonSocial']));
$domicilioFiscal = limpiarCadena($_POST['domicilioFiscal']);

if ($rifCedula == '' || $razonSocial == '' || $domicilioFiscal == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

if (validarCliente($rifCedula)) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL FORMATO DE R.I.F / CEDULA ES INCORRECTO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

if (clientesVerificarXRIFCEDULA([$rifCedula])->rowCount() > 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL CLIENTE YA SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

clientesRegistrar([$rifCedula, $razonSocial, $domicilioFiscal]);
$alerta = [
  "alerta"  => "limpiar",
  "titulo"  => "¡CLIENTE REGISTRADO!",
  "texto"   => "EL CLIENTE HA SIDO REGISTRADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
