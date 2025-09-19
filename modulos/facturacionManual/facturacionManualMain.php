<?php

//* ACTUALIZAR DATOS DE LA FACTURA
function facturacionManualActualizarDatos($datos)
{
  $sql = conexion()->prepare('UPDATE facturasresumen
SET
  Fecha = ?,
  FechaCobranza = ?,
  total = ?,
  Exento = ?,
  Gravado = ?,
  Iva = ?,
  NFacturaFiscal = ?,
  SerialMaquinaFiscal = ?,
  Estatus = 0
WHERE
  IDResumenVenta = ?');
  $sql->execute($datos);
}


//* ACTUALIZAR PRECIO Y SUBTOTAL DE ITEM DE LA FACTURA
function facturacionManualActualizarDatosItem($datos)
{
  $sql = conexion()->prepare('UPDATE facturasdetalle SET Precio = ?, SubTotal = ? WHERE IDDetalleVenta = ?');
  $sql->execute($datos);
}

//* ACTUALIZAR DATOS DE LOS MEDIO DE PAGO DE LA FACTURA
function facturacionManualActualizarMedioPago($datos)
{
  $sql = conexion()->prepare('UPDATE facturasmediopago SET Efectivo = ?, Tarjeta = ?, BioPago = ?, Transferencia = ? WHERE NVenta = ?');
  $sql->execute($datos);
}
