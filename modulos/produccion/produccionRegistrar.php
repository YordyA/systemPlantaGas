<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../formulas/formulasMain.php';
require_once '../articulos/articulosMain.php';
require_once '../inventarioPlanta/inventarioPlantaMain.php';
require_once '../inventarioProduccion/inventarioProduccionMain.php';
require_once 'produccionMain.php';

$fechaActual = date('Y-m-d');
$fechaCaducidad = date('Y-m-d', strtotime($fechaActual . ' +180 days'));
$responsable = $_SESSION['PlantaGas']['nombreUsuario'];

$IDArticulo = desencriptar($_POST['IDArticulo']);
$IDEmpaque = desencriptar($_POST['IDEmpaque']);
$KG = limpiarCadena($_POST['cantEmpacada']);
$Empaque = desencriptar($_POST['Empaque']);

if ($IDArticulo == '' || $IDEmpaque == '' || $KG == '' || $Empaque == '' ) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$consultaArticulo = articulosVerificarXID([$IDArticulo]);
if ($consultaArticulo->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
    "texto"   => "EL ARTICULO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaArticulo = $consultaArticulo->fetch(PDO::FETCH_ASSOC);

$consultaMateriaPrima = inventarioProduccionVerificarXID([$IDEmpaque]);
if ($consultaMateriaPrima->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
    "texto"   => "LA MATERIA PRIMA NO SE ENCUENTRA REGISTRADA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);


if ($consultaMateriaPrima['Existencia'] < $KG) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA MATERIA PRIMA POSEE EXISTENCIA INSUFICIENTE",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$consultaFormula = formulasConsultarXIDARTICULO([$IDArticulo]);
if ($consultaFormula->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL ARTICULO NO POSEE FORMULA REGISTRADA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultaFormula = $consultaFormula->fetchAll(PDO::FETCH_ASSOC);

foreach ($consultaFormula as $row) {
  $consultaProducto = inventarioProduccionVerificarXID([$row['IDInvProduccion']]);

  if ($consultaProducto->rowCount() != 1) {
    $alerta = [
      "alerta"  => "simple",
      "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
      "texto"   => "EL PRODUCTO DE LA FORMULA NO SE ENCUENTRA REGISTRADO",
      "tipo"    => "error"
    ];
    echo json_encode($alerta);
    exit();
  }
  $consultaProducto = $consultaProducto->fetch(PDO::FETCH_ASSOC);
}

$nroLote = produccionGenerarNroLote([$IDArticulo]);
$IDProduccionResumen = produccionRegistrarResumen(
  [
    $fechaActual,
    $fechaCaducidad,
    $IDArticulo,
    $nroLote,
    $Empaque,
    $responsable
  ]
);

$costoXEmpaque = 0;
$conceptoRetiro = 'PRODUCCION LOTE NRO ' . generarCeros($nroLote, 5);

produccionRegistrarDetalle(
  [
    $IDProduccionResumen,
    $IDEmpaque,
    $consultaMateriaPrima['CostoUnitario'],
    $KG
  ]
);
inventarioProduccionRetirarExistencia([$KG, $IDEmpaque]);
inventarioProduccionRegistrarMovimiento(
  [
    $fechaActual,
    2,
    $IDEmpaque,
    $consultaMateriaPrima['Existencia'],
    $KG,
    $IDEmpaque,
    $conceptoRetiro,
    $responsable
  ]
);

$alerta = [
  "alerta"  => "limpiar",
  "titulo"  => "¡PRODUCCIÓN REGISTRADA!",
  "texto"   => "PRODUCCION EN PROCESO",
  "tipo"    => "success"
];
echo json_encode($alerta);
