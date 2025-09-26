<?php
//! CONSULTAR EL ULTIMO NUMERO DE FACTURA POR SUCURSAL
function NVenta()
{
  $sql = conexion()->prepare('SELECT NVentaResumen FROM facturasresumen WHERE IDSucursal = ? GROUP BY NVentaResumen ORDER by NVentaResumen DESC LIMIT 1');
  $sql->execute([$_SESSION['PlantaGas']['IDPlanta']]);
  $l = $sql->fetch(PDO::FETCH_ASSOC);
  if ($l === FALSE) return 1;
  return $l['NVentaResumen'] + 1;
}

//! REGISTRAR DETALLE DE LA VENTA DE PRODUCTOS
function RegistrarDetalleVenta($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  facturasdetalle (
    NVenta,
    IDProducto,
    Precio,
    Cantidad,
    SubTotal
  )
VALUES
  (?, ?, ?, ?, ?)');
  $sql->execute($datos);
}

//! REGISTRAR RESUMEN DE VENTA
function registrarResumenVenta($datos)
{
  $Conexion = conexion();
  $sql = $Conexion->prepare('INSERT INTO facturasresumen (IDSucursal, IDCaja, IDCliente, NVentaResumen, Fecha, FechaHora, Responsable, Estatus, FechaCobranza) VALUES (?,?,?,?,?,?,?,?,?)');
  $sql->execute($datos);
  return $Conexion->lastInsertId();
}

//! ACTUALIZAR RESUMEN DE VENTA
function ActualizarResumenVenta($datos)
{
  $sql = conexion()->prepare('UPDATE facturasresumen SET total =  ?, Exento = ?, Gravado = ?, Iva = ? WHERE  IDResumenVenta = ?');
  $sql->execute($datos);
}


//! ANULAR RESUMEN DONACIONES
function ActualizarFechaDespacho($datos)
{
  $sql = conexion()->prepare('UPDATE facturasresumen SET FechaDespacho = ? WHERE IDResumenVenta = ?');
  $sql->execute($datos);
}

//! REGISTRAR MEDIOS DE PAGOS
function registrarMediosPagos($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  facturasmediopago (
    NVenta,
    Efectivo,
    Tarjeta,
    BioPago,
    Referencia,
    Transferencia,
    Vuelto,
    VueltoPagoMovil,
    VueltoEfectivo
  )
VALUES
  (?, ?, ?, ?, ?, ?, ?, ?, ?)');
  $sql->execute($datos);
}

# REGISTRAR TOTAL EN LA CAJA 
function actualizarTotalCaja($datos)
{
  $sql = conexion()->prepare('UPDATE caja SET total = total + ? WHERE  IDSucursal = ? AND IDCaja = ?');
  $sql->execute($datos);
}

//// ANULACION DE VENTA ////

//! ANULAR RESUMEN DE VENTA
function anularResumenVenta($datos)
{
  $sql = conexion()->prepare('UPDATE facturasresumen SET Estatus = 2 WHERE IDResumenVenta = ? AND IDSucursal = ?');
  $sql->execute($datos);
}

//! OJO
function consultarDonacionPorNroDonacion($datos)
{
  $consulta = conexion()->prepare('SELECT
  *
FROM
  donacionesresumen
  INNER JOIN donacionesdetalle ON donacionesresumen.IDDonaciones = donacionesdetalle.NDonacion
  INNER JOIN productos ON donacionesdetalle.IDProducto = productos.IDProducto
  INNER JOIN tipo_productos ON productos.IDTipoProducto = tipo_productos.IDTipo
  INNER JOIN clientes ON donacionesresumen.IDCliente = clientes.IDCliente
  INNER JOIN historial_tasa_bcv AS TasaUsd ON donacionesresumen.Fecha = TasaUsd.FechaTasa
WHERE
  donacionesresumen.IDDonaciones = ?');
  $consulta->execute($datos);
  return $consulta;
}


//! ANULAR RESUMEN DONACIONES
function anularResumenDonacion($datos)
{
  $sql = conexion()->prepare('UPDATE donacionesresumen SET Estatus = 1 WHERE IDDonaciones = ?');
  $sql->execute($datos);
}


//// FACTURAS PENDIENTE //// 

# ELIMINAR FACTURA PENDIENTE
function EliminarFacturaPendiente($datos)
{
  $sql = conexion()->prepare('DELETE FROM facturasespera WHERE facturasespera.IDCaja = ? AND facturasespera.IDSucursal = ? AND facturasespera.IDCliente = ? AND facturasespera.NFacturaEspera = ?');
  return $sql->execute($datos);
}

//// DONACIONES ////

# OBTENER EL NUMERO DE DONACION
function NDonacion()
{
  $sql = conexion()->prepare('SELECT NDonacion FROM donacionesresumen WHERE IDSucursal = ? ORDER by NDonacion DESC LIMIT 1');
  $sql->execute([$_SESSION['PlantaGas']['IDPlanta']]);
  $l = $sql->fetch(PDO::FETCH_ASSOC);
  if ($l === FALSE) return 1;
  return $l['NDonacion'] + 1;
}

# REGISTRAR DETALLE DONACION
function RegistrarDetalleDonacion($datos)
{
  $sql = conexion()->prepare('INSERT INTO donacionesdetalle (NDonacion, IDProducto, Precio, Cantidad, SubTotal) VALUES (?,?,?,?,?)');
  return $sql->execute($datos);
}

# REGISTRAR RESUMEN DE LA DONACION
function registrarResumenDonacion($datos)
{
  $Conexion = conexion();
  $sql = $Conexion->prepare('INSERT INTO donacionesresumen (IDSucursal, NDonacion, IDCliente, FechaHora, Fecha, Total, TipoConsumo, responsable) VALUES (?,?,?,?,?,?,?,?)');
  $sql->execute($datos);
  return $Conexion->lastInsertId();
}


//! OJO
function cancelarCxC($datos)
{
  $sql = conexion()->prepare('UPDATE facturasresumen SET Estatus = 0, FechaCobranza = ?, Responsable = ? WHERE IDResumenVenta = ?');
  return $sql->execute($datos);
}

//! ACTUALIZAR MEDIO DE PAGO
function abonarcxc($datos)
{
  $sql = conexion()->prepare('UPDATE facturasmediopago
SET Efectivo = Efectivo + ?,
Tarjeta = Tarjeta + ?,
BioPago = BioPago + ?,
Transferencia = Transferencia + ?,
CrucesFacturas = CrucesFacturas + ?
WHERE NVenta = ?');
  return $sql->execute($datos);
}

//! CONSULTAR MEDIO DE PAGO DE UNA FACTURA
function VentasPorNroVenta($datos)
{
  $reporte = conexion()->prepare("SELECT facturasmediopago.*, facturasresumen.total AS TotalFactura
from
  facturasmediopago
INNER JOIN facturasresumen ON facturasmediopago.NVenta = facturasresumen.IDResumenVenta
WHERE
  facturasmediopago.NVenta = ?");
  $reporte->execute($datos);
  return $reporte;
}

//! REGISTRAR BILLETES DOLARES
function cobrarRegistrarBilleteUSD($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  facturascontrolusd (
    NroVenta,
    Billete1,
    Billete2,
    Billete5,
    Billete10,
    Billete20,
    Billete50,
    Billete100,
    Responsable,
    EstatusBilleteUSD
  )
VALUES
  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
  $sql->execute($datos);
  return $sql;
}


//! REGISTRAR PESOS
function cobrarRegistrarBilleteCOP($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  facturascontrolcop (
    NroVenta,
    Billete50,
    Billete100,
    Billete200,
    Billete500,
    Billete1000,
    Billete2000,
    Billete5000,
    Billete10000,
    Billete20000,
    Billete50000,
    Billete100000,
    Responsable,
    EstatusBilleteCOP
  )
VALUES
  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
  $sql->execute($datos);
}

//! ACTUALIZAR VUELTO BDV
function cobrarActualizarVueltoBDV($datos)
{
  $sql = conexion()->prepare('UPDATE facturasvueltobdv SET NroVenta = ? WHERE IDVueltoBDV = ?');
  $sql->execute($datos);
}