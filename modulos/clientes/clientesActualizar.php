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
require_once 'clientesMain.php';

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDCliente = desencriptar($_GET['id']);
$consulta = clientesVerificarXID([$IDCliente]);
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

if ($consulta['RifCedula'] != $rifCedula) {
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
}

clientesActualizar([$rifCedula, $razonSocial, $domicilioFiscal, ($fechaHoraModificacion . ' - ' . $responsable), $IDCliente]);
$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡CLIENTE ACTUALIZADO!",
  "texto"   => "EL CLIENTE HA SIDO ACTUALIZADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
