<?php
require_once '../main.php';
require_once '../sessionStart.php';

$IDSucursal = $_SESSION['PlantaGas']['IDSucursal'];
$IDCaja =$_SESSION['PlantaGas']['NroCaja'];

function cobrarActualizarNroNotaCredito($datos)
{
  $sql = conexion()->prepare('UPDATE facturasresumen
SET
  NNotaCredito = ?
WHERE
  facturasresumen.IDResumenVenta = ?');
  $sql->execute($datos);
}
$NroNotaCredito = limpiarCadena($_GET['NroNotaCredito']);
$NroVenta = limpiarCadena($_GET['NroVenta']);
cobrarActualizarNroNotaCredito([$NroNotaCredito, $NroVenta]);

echo '<script>window.location.href = "https://agrofloracorpogaba.org.ve/systemFrigorificos/VenderFacturasEmitidas"</script>';
