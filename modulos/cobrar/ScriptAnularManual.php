<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../cobrar/CobrarMain.php';
require_once '../reportes/reportes_main.php';
require_once '../inventario/inventario_main.php';

$IDSucursal = $_GET['IDSucursal'];
$NroVenta = LimpiarCadena($_GET['NroVenta']);

//! OJO
$consulta = conexion()->prepare('SELECT
  *
FROM
  facturasresumen
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN articulosdeinventario ON facturasdetalle.IDProducto = articulosdeinventario.IDArticulo
  INNER JOIN facturasmediopago ON facturasresumen.IDResumenVenta = facturasmediopago.NVenta
WHERE
  facturasresumen.IDResumenVenta = ?
  AND facturasresumen.IDSucursal = ?');
$consulta->execute([$NroVenta, $IDSucursal]);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

foreach ($consulta as $row) {
  ActualizarExistenciaSumar(
    [
      $row['Cantidad'],
      $row['IDArticulo'],
      $IDSucursal
    ]
  );
  EntradaProductosInventario(
    [
      $IDSucursal,
      date('Y-m-d'),
      'ANULACION DE VENTA Nro ' . $row['NVentaResumen'],
      $row['IDArticulo'],
      $row['Cantidad'],
     $_SESSION['PlantaGas']['NombreUsuario']
    ]
  );
}

anularResumenVenta([$NroVenta, $IDSucursal]);
