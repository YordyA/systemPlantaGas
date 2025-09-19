<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../cobrar/CobrarMain.php';
require_once '../reportes/reportes_main.php';
require_once '../inventario/inventario_main.php';
require_once '../dependencias.php';
$IDSucursal = $_GET['id'] ?? $_SESSION['PlantaGas']['IDPlanta'];
$NroVenta = Desencriptar(LimpiarCadena($_GET['n']));
//! OJO
$consulta = conexion()->prepare('SELECT
  *
FROM
  facturasresumen
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN productos ON facturasdetalle.IDProducto = productos.IDProducto
  INNER JOIN tipo_productos ON productos.IDTipoProducto = tipo_productos.IDTipo
  INNER JOIN facturasmediopago ON facturasresumen.IDResumenVenta = facturasmediopago.NVenta
WHERE
  facturasresumen.IDResumenVenta = ?');
$consulta->execute([$NroVenta]);

$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

anularResumenVenta([$NroVenta, $IDSucursal]);
$i = 0;
var_dump($consulta[0]['NFacturaFiscal']);
exit();
?>
