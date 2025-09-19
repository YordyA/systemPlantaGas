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
$fechaAnterior = date('Y-m-d', strtotime('-1 day'));

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];

$Harina = $_POST['harina'];
$HarinaDos = $_POST['harina2'];
$Afrecho = $_POST['afrecho'];
$Barrido = $_POST['barrido'];
$Pico = $_POST['pico'];
$Fecula = $_POST['fecula'];
$Descarte = 0;
$Impurezas = $_POST['impurezas'];
$IDProduccionResumen = desencriptar(limpiarCadena($_GET['id']));
$conceptoRetiro = 'LOTE NRO ' . $IDProduccionResumen . '';
if ($IDProduccionResumen == '' || $Harina == '' || $HarinaDos == '' || $Afrecho == '' || $Barrido == '' || $Pico == '' || $Fecula == '' || $Descarte == '' || $Impurezas == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$totalEntrate = $Harina + $HarinaDos + $Afrecho + $Barrido + $Pico + $Fecula + $Descarte + $Impurezas;
$total = 0;
foreach (produccionConsultarSubProductosXID([$IDProduccionResumen]) as $row) {
  $total += $row['Harina'];
  $total += $row['Afrecho'];
  $total += $row['Barrido'];
  $total += $row['Descarte'];
  $total += $row['Fecula'];
  $total += $row['Impurezas'];
  $total += $row['Pico'];
}
$totalProductosUtilizado = 0;
foreach (produccionConsultarXID([$IDProduccionResumen]) as $row) {
  $totalProductosUtilizado += $row['CantidadUtilizada'];
}


// if (($total + $totalEntrate) > $totalProductosUtilizado) {
//   $alerta = [
//     "alerta"  => "simple",
//     "titulo"  => "¡OCURRIÓ UN ERROR INESPERADO!",
//     "texto"   => "LA SUMA TOTAL DE LOS INGRESOS NO PUEDE SER MAYOR A LA CANTIDAD DE MATERIA PRIMA UTILIZADA",
//     "tipo"    => "error"
//   ];
//   echo json_encode($alerta);
//   exit();
// }



# HARINA
produccionRegistrarSuproductos(
  [
    $fechaAnterior,
    $IDProduccionResumen,
    1,
    round($Harina + $HarinaDos, 2)
  ]
);

# PICO 
produccionRegistrarSuproductos(
  [
    $fechaAnterior,
    $IDProduccionResumen,
    13,
    $Pico
  ]
);

# BARRIDO 
produccionRegistrarSuproductos(
  [
    $fechaAnterior,
    $IDProduccionResumen,
    14,
    $Barrido
  ]
);


# AFRECHO 
produccionRegistrarSuproductos(
  [
    $fechaAnterior,
    $IDProduccionResumen,
    15,
    $Afrecho
  ]
);

# FECULA DE MAIZ 
produccionRegistrarSuproductos(
  [
    $fechaAnterior,
    $IDProduccionResumen,
    16,
    $Fecula
  ]
);

# HARINA DE DESCARTE 
produccionRegistrarSuproductos(
  [
    $fechaAnterior,
    $IDProduccionResumen,
    17,
    $Descarte
  ]
);

# IMPUREZAS 
produccionRegistrarSuproductos(
  [
    $fechaAnterior,
    $IDProduccionResumen,
    18,
    $Impurezas
  ]
);

if ($Pico > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([13]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      1,
      13,
      $consultaMateriaPrima['Existencia'],
      $Pico,
      13,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRellenarExistenciaConCosto([0, $Pico, 13]);
}

if ($Barrido > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([14]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      1,
      14,
      $consultaMateriaPrima['Existencia'],
      $Barrido,
      14,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRellenarExistenciaConCosto([0, $Barrido, 14]);
}

if ($Afrecho > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([15]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      1,
      15,
      $consultaMateriaPrima['Existencia'],
      $Afrecho,
      15,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRellenarExistenciaConCosto([0, $Afrecho, 15]);
}

if ($Fecula > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([16]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      1,
      16,
      $consultaMateriaPrima['Existencia'],
      $Fecula,
      16,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRellenarExistenciaConCosto([0, $Fecula, 16]);
}

if ($Descarte > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([17]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      1,
      17,
      $consultaMateriaPrima['Existencia'],
      $Descarte,
      17,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRellenarExistenciaConCosto([0, $Descarte, 17]);
}

if ($Impurezas > 0) {
  $consultaMateriaPrima = inventarioProduccionVerificarXID([18]);
  $consultaMateriaPrima = $consultaMateriaPrima->fetch(PDO::FETCH_ASSOC);

  inventarioProduccionRegistrarMovimiento(
    [
      $fechaActual,
      1,
      18,
      $consultaMateriaPrima['Existencia'],
      $Impurezas,
      18,
      $conceptoRetiro,
      $responsable
    ]
  );
  inventarioProduccionRellenarExistenciaConCosto([0, $Impurezas, 18]);
}

if ($Harina > 0) {
  $consultaProduccion = inventarioPlantaPorProduccionIDyArticulo([$IDProduccionResumen, 1]);
  if ($consultaProduccion->rowCount() == 0) {

    produccionActualizar([$Harina, $responsable . date('d-m-Y H:i:s'), $IDProduccionResumen]);
    $IDInvPlanta = inventarioPlantaRegistrar([$IDProduccionResumen, 1, date('d-m-Y'), $Harina]);
    inventarioPlantaRegistrarMovimiento(
      [
        $fechaActual,
        1,
        $IDInvPlanta,
        0,
        $Harina,
        $IDInvPlanta,
        $conceptoRetiro,
        $responsable
      ]
    );
  } else {
    produccionActualizar([$Harina, $responsable . date('d-m-Y H:i:s'), $IDProduccionResumen]);
    $InventarioPlanta = $consultaProduccion->fetch(PDO::FETCH_ASSOC);
    inventarioPlantaRellenarExistencia([$Harina, $InventarioPlanta['IDInvPlanta']]);
    inventarioPlantaRegistrarMovimiento(
      [
        $fechaActual,
        1,
        $InventarioPlanta['IDInvPlanta'],
        $InventarioPlanta['Existencia'],
        $Harina,
        $InventarioPlanta['IDInvPlanta'],
        $conceptoRetiro,
        $responsable
      ]
    );
  }
}

if ($HarinaDos > 0) {
  $consultaProduccion = inventarioPlantaPorProduccionIDyArticulo([$IDProduccionResumen, 2]);
  if ($consultaProduccion->rowCount() == 0) {

    produccionActualizar([$HarinaDos, $responsable . date('d-m-Y H:i:s'), $IDProduccionResumen]);
    $IDInvPlanta = inventarioPlantaRegistrar([$IDProduccionResumen, 2, date('d-m-Y'), $HarinaDos]);
    inventarioPlantaRegistrarMovimiento(
      [
        $fechaActual,
        1,
        $IDInvPlanta,
        0,
        $HarinaDos,
        $IDInvPlanta,
        $conceptoRetiro,
        $responsable
      ]
    );
  } else {
    produccionActualizar([$HarinaDos, $responsable . date('d-m-Y H:i:s'), $IDProduccionResumen]);
    $InventarioPlanta = $consultaProduccion->fetch(PDO::FETCH_ASSOC);
    inventarioPlantaRellenarExistencia([$HarinaDos, $InventarioPlanta['IDInvPlanta']]);
    inventarioPlantaRegistrarMovimiento(
      [
        $fechaActual,
        1,
        $InventarioPlanta['IDInvPlanta'],
        $InventarioPlanta['Existencia'],
        $HarinaDos,
        $InventarioPlanta['IDInvPlanta'],
        $conceptoRetiro,
        $responsable
      ]
    );
  }
}
// if (abs(($total + $totalEntrate) - $totalProductosUtilizado) <> 0) {
  produccionActualizarEstadoProduccion(
    [
      3,
      $responsable . date('d-m-Y H:i:s'),
      $IDProduccionResumen
    ]
  );
//}
$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡PRODUCCIÓN REGISTRADA!",
  "texto"   => "PRODUCCION ENVIADA A STOCK",
  "tipo"    => "success"
];
echo json_encode($alerta);
