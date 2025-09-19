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
require_once '../reportes/reportes_main.php';
require_once 'CobrarMain.php';

$IDSucursal =$_SESSION['PlantaGas']['IDSucursal'];
$responsable =$_SESSION['PlantaGas']['NombreUsuario'];

$IDResumenVenta = Desencriptar($_GET['id']);
$consulta = consultarVentaPorNventa([$IDResumenVenta]);
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

foreach ($consulta as $row) {
  ActualizarExistenciaSumar([$row['Cantidad'], $row['IDArticulo'], $IDSucursal]);

  EntradaProductosInventario(
    [
      $IDSucursal,
      date('Y-m-d'),
      '(ENTREGA DE FACTURA) ANULACION DE VENTA Nro ' . $row['NVentaResumen'],
      $row['IDArticulo'],
      $row['Cantidad'],
      $responsable
    ]
  );
}

anularResumenVenta([$IDResumenVenta, $IDSucursal]);
$alerta = [
  "alerta"  => "simple",
  "titulo"  => "¡ENTREGA PRODUCTOS ANULADO!",
  "texto"   => "LA ENTREGA DE PRODUCTOS HA SIDO ANULADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
