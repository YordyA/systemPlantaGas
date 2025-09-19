<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../inventarioProduccion/inventarioProduccionMain.php';

if (isset($_SESSION['conteoFisico']['datelle'])) {
  unset($_SESSION['conteoFisico']['datelle']);
}

$_SESSION['conteoFisico']['datelle'] = [];
foreach (inventarioProduccionLista() as $row) {
  if ($row['IDTipoProducto'] == 1 || $row['IDTipoProducto'] == 5) {
    $_SESSION['conteoFisico']['datelle'][$row['IDInvProduccion']] = [
      'codigo'        => $row['CodigoProducto'],
      'descripcion'   => $row['DescripcionProducto'],
      'cantSistema'   => $row['Existencia'],
      'cantFisica'    => 0,
      'diferencia'    => 0
    ];
  }
}

$alerta = [
  "alerta"  => "simple",
  "titulo"  => "¡CONTEO INICIADO!",
  "texto"   => "EL CONTEO HA SIDO INICIADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
