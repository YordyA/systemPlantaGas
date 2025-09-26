<?php
if (!isset($_GET['serialMaquinaFiscal']) || $_GET['serialMaquinaFiscal'] == '' || !isset($_GET['nroReporteZ']) || $_GET['nroReporteZ'] == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'maquinasFiscalesMain.php';
require_once '../dependencias.php';
$serialMaquinaFiscal = desencriptar($_GET['serialMaquinaFiscal']);
$nroReporteZ = desencriptar($_GET['nroReporteZ']);
$responsable = $_SESSION['PlantaGas']['nombreUsuario'];

$nroFacturaDesde = LimpiarCadena($_POST['nroFacturaDesde']);
$nroFacturaHasta = LimpiarCadena($_POST['nroFacturaHasta']);
$montoTotalExento = LimpiarCadena($_POST['montoTotalExento']);
$montoTotalBaseImponible = LimpiarCadena($_POST['montoTotalBaseImponible']);
$montoTotalExentoNotaCredito = LimpiarCadena($_POST['montoTotalExentoNotaCredito']);
$montoTotalBaseImponibleCredito = LimpiarCadena($_POST['montoTotalBaseImponibleCredito']);
if ($nroFacturaDesde == '' || $nroFacturaHasta == '' || $montoTotalExento == '' || $montoTotalBaseImponible == '' || $montoTotalExentoNotaCredito == '' || $montoTotalBaseImponibleCredito == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$consultarMaquinaFiscal = maquinasFiscalesVerificarXSERIAL([$serialMaquinaFiscal]);
if ($consultarMaquinaFiscal->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA MAQUINA FISCAL NO SE ENCUENTRA REGISTRAD ",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultarMaquinaFiscal = $consultarMaquinaFiscal->fetch(PDO::FETCH_ASSOC);

maquinasFiscalesRegistrarReportZ(
  [
    date('Y-m-d'),
    $consultarMaquinaFiscal['IDMaquinaFiscal'],
    $nroReporteZ,
    $nroFacturaDesde,
    $nroFacturaHasta,
    $montoTotalExento,
    $montoTotalBaseImponible,
    $montoTotalExentoNotaCredito,
    $montoTotalBaseImponibleCredito,
    $responsable
  ]
);

$alerta = [
  "alerta"  => "redireccionar",
  "url"     => "Vender",
  "titulo"  => "¡REGISTRADO!",
  "texto"   => "EL REPORTE Z HA SIDO REGISTRADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
exit();
