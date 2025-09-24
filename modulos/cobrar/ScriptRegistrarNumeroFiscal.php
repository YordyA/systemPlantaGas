<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';

function cobrarActualizarNroFacturaFiscal($datos)
{
  $sql = conexion()->prepare('UPDATE facturasresumen
SET
  SerialMaquinaFiscal = ?,
  NFacturaFiscal = ?
WHERE
  facturasresumen.IDResumenVenta = ?
  AND facturasresumen.NFacturaFiscal = 0');
  $sql->execute($datos);
}

$NroFacturaFiscal = limpiarCadena($_GET['NroFactFiscal']);
$serialMaquinaFiscal = $_GET['serial'];
$NroVenta = limpiarCadena($_GET['NroVenta']);
cobrarActualizarNroFacturaFiscal([$serialMaquinaFiscal, $NroFacturaFiscal, $NroVenta]);

echo '<script>window.location.href = "https://sistemasinternos.net/systemPlantaGas/Vender"</script>';
