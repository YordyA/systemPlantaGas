<?php
require_once '../main.php';
require_once 'CobrarMain.php';

$consulta = conexion()->prepare('UPDATE facturasresumen
SET
  NFacturaFiscal = ?
WHERE
  facturasresumen.IDResumenVenta = ?
  AND facturasresumen.IDSucursal = ?
  AND facturasresumen.IDCaja = ?
  AND facturasresumen.IDCliente = ?
  AND facturasresumen.NFacturaFiscal = ?');
if($consulta->execute(
  [
    $_GET['nf'],
    $_GET['venta'],
    $_GET['sucursal'],
    $_GET['caja'],
    $_GET['cliente'],
    0
  ]
)){
    echo json_encode(true, JSON_UNESCAPED_UNICODE);
}else {
    echo json_encode(false, JSON_UNESCAPED_UNICODE);
}

