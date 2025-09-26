<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../reportes/reportes_main.php';
require_once 'CobrarMain.php';
require_once '../dependencias.php';

$IDSucursal =$_SESSION['PlantaGas']['IDPlanta'];
$NroFacturaPendiente = (limpiarCadena(desencriptar($_GET['id'])));
$responsable =$_SESSION['PlantaGas']['nombreUsuario'];
$tipoConsumo = limpiarCadena($_GET['d']);
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
    $NroFacturaPendiente
  ]
)->fetchAll(PDO::FETCH_ASSOC);

$IDCliente = $consulta[0]['IDCliente'];
$usd =$_SESSION['PlantaGas']['Dolar'];
$totalVenta = 0;

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
 $precioBS = $consulta['TipoMoneda'] == 1 ? round(floatval($consulta['PrecioVenta'] * $usd), 2) : round(floatval($consulta['PrecioVenta']), 2);
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
