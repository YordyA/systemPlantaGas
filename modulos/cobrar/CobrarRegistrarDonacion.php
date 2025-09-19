<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../inventario/inventario_main.php';
require_once '../reportes/reportes_main.php';
require_once 'CobrarMain.php';

$IDSucursal =$_SESSION['PlantaGas']['IDSucursal'];
$NroFacturaPendiente = (LimpiarCadena(Desencriptar($_GET['id'])));
$responsable =$_SESSION['PlantaGas']['NombreUsuario'];
$tipoConsumo = LimpiarCadena($_GET['d']);
$fecha = date('Y-m-d');
$NroDonacion = NDonacion(
  [
    $IDSucursal,
    $tipoConsumo
  ]
);

$consulta = consultarFacturasEnEsperaDonacion(
  [
    $IDSucursal,
    $IDSucursal,
    $NroFacturaPendiente
  ]
)->fetchAll(PDO::FETCH_ASSOC);

$IDCliente = $consulta[0]['IDCliente'];
$usd =$_SESSION['PlantaGas']['Dolar'];
$totalVenta = 0;

$productoSinExistencia = '';
foreach ($consulta as $row) {
  $producto = VerificarArticulosPorIDYNombre([$row['IDArticulo'], $IDSucursal])->fetch(PDO::FETCH_ASSOC);

  if ($producto['ExistenciaArticulo'] < round(floatval($row['Cantidad']), 3)) {
    $productoSinExistencia .= $producto['DescripcionArticulo'] . "\n";
  }
}

if ($productoSinExistencia != '') {
  echo json_encode([false, $productoSinExistencia]);
  exit();
}

$nameRetiros = array(
  '0' => 'DONACIÓN',
  '1' => 'AUTO CONSUMO',
  '2' => 'DONACIÓN TRABAJADOR'
);



$IDDonacionResumen = registrarResumenDonacion(
  [
    $IDSucursal,
    $NroDonacion,
    $IDCliente,
    date('Y-m-d H:i:s'),
    $fecha,
    $totalVenta,
    $tipoConsumo,
    $responsable
  ]
);

foreach ($consulta as $row) {
  $precioBS = round(($tipoConsumo == 2) ? $row['PrecioArticulo'] * $usd : $row['CostoArticulo'] * $usd, 2);
  ActualizarExistenciaRestar(
    [
      round(floatval($row['Cantidad']), 3),
      $row['IDArticulo'],
      $IDSucursal,
    ]
  );
  SalidaProductosInventario(
    [
      $IDSucursal,
      $fecha,
      $nameRetiros[$tipoConsumo] . ' NRO ' . $NroDonacion,
      $row['IDArticulo'],
      round(floatval($row['Cantidad']), 3),
      $responsable
    ]
  );

  RegistrarDetalleDonacion(
    [
      $IDDonacionResumen,
      $row['IDArticulo'],
      $precioBS,
      $row['Cantidad'],
      round($precioBS * $row['Cantidad'], 2)
    ]
  );
  $totalVenta += round($precioBS * $row['Cantidad'], 2);
}

EliminarFacturaPendiente(
  [
    $consulta[0]['IDCaja'],
    $IDSucursal,
    $IDCliente,
    $NroFacturaPendiente
  ]
);

echo json_encode([true, Encriptar($IDDonacionResumen), Encriptar($tipoConsumo)]);
