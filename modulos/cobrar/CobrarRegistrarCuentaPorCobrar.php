<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../inventario/inventario_main.php';
require_once '../reportes/reportes_main.php';
require_once '../cobrar/CobrarMain.php';

$NroCaja = 1;
$ultimaFacturaFiscal = 0;
$fecha = date('Y-m-d');
$IDSucursal =$_SESSION['PlantaGas']['IDSucursal'];
$NroFacturaEnEspera = Desencriptar($_GET['id']);
$responsable =$_SESSION['PlantaGas']['NombreUsuario'];
$usd =$_SESSION['PlantaGas']['Dolar'];

$NroNotaVenta = NVenta();
$consulta = consultarFacturasEnEspera([$IDSucursal, $NroFacturaEnEspera])->fetchAll(PDO::FETCH_ASSOC);

$productoSinExistencia = '';
foreach ($consulta as $row) {
  $producto = VerificarArticulosPorIDYNombre([$row['IDArticulo'], $IDSucursal])->fetch(PDO::FETCH_ASSOC);
  if ($producto['ExistenciaArticulo'] < round(floatval($row['Cantidad']), 3)) {
    $productoSinExistencia .= $producto['DescripcionArticulo'] . "\n";
  }
}

if ($productoSinExistencia != '') {
  echo '
        <script>
            alert("SIN EXISTENCIA")
            window.location.href = "https://agrofloracorpogaba.org.ve/systemFrigorificos/Venders"
        </script>';
  exit();
}

$IDResumenVenta = registrarResumenVenta(
  [
    $IDSucursal,
    $NroCaja,
    $consulta[0]['IDCliente'],
    $NroNotaVenta,
    $fecha,
    date('Y-m-d H:i:s'),
    $responsable,
    1,
    null
  ]
);

$totalVenta = 0;
$exento = 0;
$gravado = 0;
foreach ($consulta as $row) {
  $subTotal = round($row['Precio'] * $row['Cantidad'], 2);
  $totalVenta += round($subTotal, 2);

  ActualizarExistenciaRestar(
    [
      $row['Cantidad'],
      $row['IDArticulo'],
      $IDSucursal,
    ]
  );

  SalidaProductosInventario(
    [
      $IDSucursal,
      $fecha,
      'VENTA NRO ' . $NroNotaVenta,
      $row['IDArticulo'],
      $row['Cantidad'],
      $responsable
    ]
  );

  RegistrarDetalleVenta(
    [
      $IDResumenVenta,
      $row['IDArticulo'],
      $row['Precio'],
      $row['Cantidad'],
      $subTotal,
    ]
  );

  if ($row['IDAlicuota'] == 1) {
    $exento += round(floatval($subTotal), 2);
  } else if ($row['IDAlicuota'] == 2) {
    $gravado += round(floatval($subTotal), 2);
  }
}

registrarMediosPagos([$IDResumenVenta, 0, 0, 0, '', 0, 0, 0, 0]);
ActualizarResumenVenta(
  [
    $totalVenta,
    $exento,
    $gravado - round(floatval($gravado - ($gravado / 1.16)), 2),
    round(floatval($gravado - ($gravado / 1.16)), 2),
    $IDResumenVenta
  ]
);

EliminarFacturaPendiente([$consulta[0]['IDCaja'], $IDSucursal, $consulta[0]['IDCliente'], $NroFacturaEnEspera]);
echo '<script>window.location.href = "https://www.agrofloracorpogaba.org.ve/systemFrigorificos/modulos/cobrar/CobrarEmitirFacturacFiscal.php?n=' . Encriptar($IDResumenVenta) . '"</script>';
