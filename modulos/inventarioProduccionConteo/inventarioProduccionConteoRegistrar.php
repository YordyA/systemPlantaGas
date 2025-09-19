<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../inventarioProduccion/inventarioProduccionMain.php';
require_once 'inventarioProduccionConteoMain.php';

if (!isset($_SESSION['conteoFisico']['datelle'])) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

inventarioProduccionConteoActualizarEstadoXFecha([date('Y'), date('m')]);
$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$nroConteo = inventarioProduccionConteoGenerarNro();
$conteoFisico = $_SESSION['conteoFisico']['datelle'];
foreach ($conteoFisico as $IDInvProduccion => $row) {
  inventarioProduccionConteoRegistrar(
    [
      date('Y-m-d'),
      $nroConteo,
      $IDInvProduccion,
      $row['cantSistema'],
      $row['cantFisica'],
      $row['diferencia'],
      $responsable
    ]
  );

  inventarioProduccionActualizarExistencia([$row['cantFisica'], $IDInvProduccion]);
  inventarioProduccionRegistrarMovimiento(
    [
      date('Y-m-d'),
      ($row['diferencia'] > 0 ? 1 : 2),
      $IDInvProduccion,
      $row['cantSistema'],
      abs($row['diferencia']),
      $IDInvProduccion,
      'CONTEO FISICO NRO ' . $nroConteo . ' - RELIZADO POR ' . $responsable,
      $responsable
    ]
  );
}

unset($_SESSION['conteoFisico']['datelle']);
$alerta = [
  "alerta"  => "simple",
  "url"     => "modulos/excel/EXCELConteo.php?id=" . encriptar($nroConteo),
  "titulo"  => "¡CONTEO FISICO REGISTRADO!",
  "texto"   => "EL CONTEO FISICO HA SIDO REGISTRADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
