<?php
if (!isset($_GET['id']) || $_GET['id'] == '' || !isset($_POST['clave']) || $_POST['clave'] == '' || !isset($_GET['f']) || $_GET['f'] == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../usuarios/usuariosMain.php';
require_once '../inventarioPlanta/inventarioPlantaMain.php';
require_once '../inventarioProduccion/inventarioProduccionMain.php';
require_once 'produccionMain.php';

$IDProduccionResumen = limpiarCadena($_GET['id']);
$Dia = desencriptar(limpiarCadena($_GET['f']));
$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$fechaActual = date('Y-m-d');
$conceptoRetiro = 'ANULACION DE PRODUCCION DE  ' . $Dia;
$clave = limpiarCadena($_POST['clave']);
$consultaUsuario = usuariosVerificarXID([$_SESSION['PlantaGas']['IDUsuario']]);
if ($consultaUsuario->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
    "texto"   => "EL USUARIO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaUsuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

if (!password_verify($clave, $consultaUsuario['Clave'])) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA CLAVE INGRESADA NO COINCIDE",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$consultaInvPlanta = inventarioPlantaVerificarXIDPRODUCCIONRESUMEN([$IDProduccionResumen]);
if ($consultaInvPlanta->rowCount() < 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "PRODUCCION NO ENCONTRADA EN INVENTARIO DE PLANTA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaInvPlanta = $consultaInvPlanta->fetch(PDO::FETCH_ASSOC);


$totalHarina = 0;
$totalAfrecho = 0;
$totalBarrido = 0;
$totalDescarte = 0;
$totalFecula = 0;
$totalImpurezas = 0;
$totalPico = 0;


foreach (produccionConsultarSubProductosXID([$IDProduccionResumen]) as $row) {
  if ($row['Fecha'] == $Dia) {
    $totalHarina += $row['Harina'];
    $totalAfrecho += $row['Afrecho'];
    $totalBarrido += $row['Barrido'];
    $totalDescarte += $row['Descarte'];
    $totalFecula += $row['Fecula'];
    $totalImpurezas += $row['Impurezas'];
    $totalPico += $row['Pico'];
  }
}


if ($totalHarina > $consultaInvPlanta['Existencia']) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA PRODUCCION NO SE PUEDE ANULAR, INVETARIO DE PLANTA INSUFICIENTE",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

if ($totalPico > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([13]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      2,
      13,
      $consultaMateriaPrima['Existencia'],
      $totalPico,
      13,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRetirarExistencia([$totalPico, 13]);
}

if ($totalBarrido > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([14]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      2,
      14,
      $consultaMateriaPrima['Existencia'],
      $totalBarrido,
      14,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRetirarExistencia([$totalBarrido, 14]);
}

if ($totalAfrecho > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([15]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      2,
      15,
      $consultaMateriaPrima['Existencia'],
      $totalAfrecho,
      15,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRetirarExistencia([$totalAfrecho, 15]);
}

if ($totalFecula > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([16]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      2,
      16,
      $consultaMateriaPrima['Existencia'],
      $totalFecula,
      16,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRetirarExistencia([$totalFecula, 16]);
}

if ($totalDescarte > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([17]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      2,
      17,
      $consultaMateriaPrima['Existencia'],
      $totalDescarte,
      17,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRetirarExistencia([$totalDescarte, 17]);
}

if ($totalImpurezas > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([18]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      2,
      18,
      $consultaMateriaPrima['Existencia'],
      $totalImpurezas,
      18,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRetirarExistencia([$totalImpurezas, 18]);
}

EliminarSubproductos([$Dia, $IDProduccionResumen]);
inventarioPlantaRetirarExistencia([$totalHarina, $consultaInvPlanta['IDInvPlanta']]);
inventarioPlantaRegistrarMovimiento(
  [
    date('Y-m-d'),
    2,
    $consultaInvPlanta['IDInvPlanta'],
    $consultaInvPlanta['Existencia'],
    $totalHarina,
    $consultaInvPlanta['IDInvPlanta'],
    'ANULACION DE PRODUCCION DE  ' . $Dia,
    $consultaUsuario['NombreUsuario']
  ]
);

$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡INGRESO ANULADO!",
  "texto"   => "EL INGRESO HA SIDO ANULADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
