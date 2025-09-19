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
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once 'despachosMain.php';

$responsable = $_SESSION['systemPlantaAba']['nombreUsuario'];
$IDDespachoResumen = desencriptar($_GET['id']);
$consultaDespacho = despachosConsultarDespachoDetalladoXID([$IDDespachoResumen]);
if ($consultaDespacho->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL DESPACHO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaDespacho = $consultaDespacho->fetchAll(PDO::FETCH_ASSOC);

$serie = limpiarCadena($_POST['serie']);
$nroFactura = limpiarCadena($_POST['nroFactura']);
$nroControl = limpiarCadena($_POST['nroControl']);
$precioBS = $_POST['precioBS'];
if ($serie == '' || $nroFactura == '' || $nroControl == '' || count($precioBS) == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

foreach ($consultaDespacho as $indice => $row) {
  despachosActualizarPrecioBS([$precioBS[$indice], $row['IDDespachoDetalle']]);
}

despachosActualizarDatosFactura([$serie, $nroFactura, $nroControl, ($fechaHoraModificacion . ' - ' . $responsable), $IDDespachoResumen]);
$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡DESPACHO FACTURADO!",
  "texto"   => "LOS DETALLE DE LA FACTURA HAN SIDO AGREGADOS CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
