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

$NFacturaEspera = Desencriptar($_GET['id']);
$consultarFacturaEspera = consultarFacturasEnEsperaDonacion([$IDSucursal, $IDSucursal, $NFacturaEspera]);
if ($consultarFacturaEspera->rowCount() == 0) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LA FACTURA EN ESPERA NO SE ENCUENTRA REGISTRADA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consultarFacturaEspera = $consultarFacturaEspera->fetchAll(pdo::FETCH_ASSOC);

$NroNotaVenta = NVenta();
$IDFacturaResumen = registrarResumenVenta(
  [
    $IDSucursal,
    $consultarFacturaEspera[0]['IDCaja'],
    $consultarFacturaEspera[0]['IDCliente'],
    $NroNotaVenta,
    null,
    date('Y-m-d H:i:s'),
    $responsable,
    3,
    null
  ]
);

foreach ($consultarFacturaEspera as $row) {
  ActualizarExistenciaRestar([$row['Cantidad'], $row['IDArticulo'], $row['IDSucursal']]);

  SalidaProductosInventario(
    [
      $row['IDSucursal'],
      date('Y-m-d'),
      '(ENTREGA DE PRODUCTOS) VENTA NRO ' . $NroNotaVenta,
      $row['IDArticulo'],
      $row['Cantidad'],
      $responsable
    ]
  );

  RegistrarDetalleVenta(
    [
      $IDFacturaResumen,
      $row['IDArticulo'],
      0,
      floatval($row['Cantidad']),
      0,
    ]
  );
}

registrarMediosPagos(
  [
    $IDFacturaResumen,
    0,
    0,
    0,
    '',
    0,
    0,
    0,
    0
  ]
);

EliminarFacturaPendiente([$consultarFacturaEspera[0]['IDCaja'], $consultarFacturaEspera[0]['IDSucursal'], $consultarFacturaEspera[0]['IDCliente'], $consultarFacturaEspera[0]['NFacturaEspera']]);
$alerta = [
  "alerta"  => "simple",
  "titulo"  => "¡ENTREGA DE PRODUCTOS REGISTRADA!",
  "texto"   => "LA ENTREGA DE PRODUCTO HA SIDO REGISTRADA CON EXITO",
  "url"     =>  "modulos/pdf/PDFNotaEntrega.php?id=" . Encriptar($IDFacturaResumen),
  "tipo"    => "success"
];
echo json_encode($alerta);
