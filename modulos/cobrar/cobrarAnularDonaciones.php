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
require_once '../inventario/inventario_main.php';
require_once 'CobrarMain.php';

$IDSucursal =$_SESSION['PlantaGas']['IDSucursal'];
$responsable =$_SESSION['PlantaGas']['NombreUsuario'];

$IDResumenVenta = Desencriptar($_GET['id']);
$consulta = consultarDonacionPorNroDonacion([$IDResumenVenta]);
if ($consulta->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA ENTREGA DE PRODUCTO NO SE ENCUENTRA REGISTRADA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
$nameRetiros = array(
  1 => 'CONTRIBUCION SOCIAL EMPRESARIAL',
  3 => 'AUTOCONSUMO',
  2 => 'CONTRIBUCION SOCIAL TRABAJADOR'
);

foreach ($consulta as $row) {
  ActualizarExistenciaSumar([$row['Cantidad'], $row['IDArticulo'], $IDSucursal]);
  EntradaProductosInventario(
    [
      $IDSucursal,
      date('Y-m-d'),
      $nameRetiros[$consulta[0]['TipoConsumo']] . ' ANULACION DE NOTA Nro ' . $row['NDonacion'],
      $row['IDArticulo'],
      $row['Cantidad'],
      $responsable
    ]
  );
}

anularResumenDonacion([$IDResumenVenta]);
$alerta = [
  "alerta"  => "recargar",
  "titulo"  => "NOTA ANUDA!",
  "texto"   => "LA NOTA HA SIDO ANULADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
