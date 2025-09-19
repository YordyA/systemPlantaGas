<?php
# CONSULTAR EL ULTIMO NUMERO DE FACTURA POR SUCURSAL
function NFacturaEnEspera()
{
  $sql = conexion()->prepare('SELECT NFacturaEspera FROM facturasespera WHERE IDSucursal = ? ORDER by NFacturaEspera DESC LIMIT 1');
  $sql->execute([$_SESSION['PlantaGas']['IDPlanta']]);
  $l = $sql->fetch(PDO::FETCH_ASSOC);
  if ($l === FALSE) return 1;
  return $l['NFacturaEspera'] + 1;
}

# REGISTRAR FACTURA EN ESPERA
function RegistrarFacturaEnEspera($datos)
{
  $sql = conexion()->prepare('INSERT INTO facturasespera(NFacturaEspera, IDCliente, IDSucursal, IDCaja, IDArticulo, Cantidad, Precio) VALUES (?,?,?,?,?,?,?)');
  $sql->execute($datos);
}

# CONSULTAR CAJA POR SUCURSAL
function consultarCajaPorSucursal($datos)
{
  $sql = conexion()->prepare('SELECT * FROM caja WHERE IDSucursal = ? AND SerialImpresora = ?');
  $sql->execute($datos);
  return $sql;
}

# ACTUALIZAR DATOS CAJA
function actualizarDatosCaja($datos)
{
  $sql = conexion()->prepare('UPDATE caja SET FechaApertura = ?, Responsable = ?, total = ?, IPImpresora = ?, UltimoNFFiscal = ? WHERE IDSucursal = ? AND SerialImpresora = ?');
  $sql->execute($datos);
}


//// FACTURA EN ESPERA ////

# ELIMINAR FACTURA EN ESPERA
function eliminarFacturaEnEspera($datos)
{
  $sql = conexion()->prepare('DELETE FROM facturasespera WHERE NFacturaEspera = ? AND IDSucursal = ?');
  $sql->execute($datos);
  return $sql;
}

//! CONSULTAR INVENTARIO DE BILLETES USD
function facturacionConsultarInventarioBilletesUSD($datos)
{
  $sql = conexion()->prepare('SELECT
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 1 THEN Billete1 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 2 THEN Billete1 ELSE 0 END), 0) AS Billete1,
    
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 1 THEN Billete2 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 2 THEN Billete2 ELSE 0 END), 0) AS Billete2,

    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 1 THEN Billete5 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 2 THEN Billete5 ELSE 0 END), 0) AS Billete5,

    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 1 THEN Billete10 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 2 THEN Billete10 ELSE 0 END), 0) AS Billete10,

    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 1 THEN Billete20 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 2 THEN Billete20 ELSE 0 END), 0) AS Billete20,

    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 1 THEN Billete50 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 2 THEN Billete50 ELSE 0 END), 0) AS Billete50,

    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 1 THEN Billete100 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN facturascontrolusd.EstatusBilleteUSD = 2 THEN Billete100 ELSE 0 END), 0) AS Billete100
FROM
    facturascontrolusd
    INNER JOIN facturasresumen ON facturascontrolusd.NroVenta = facturasresumen.IDResumenVenta
WHERE
    facturasresumen.Fecha = ?
    AND facturasresumen.IDSucursal = ?
    AND facturasresumen.IDCaja = ?
    AND facturasresumen.Estatus != 2');
  $sql->execute($datos);
  return $sql->fetch(PDO::FETCH_ASSOC);
}

//! CONSULTAR INVENTARIO DE BILLETES COP
function facturacionConsultarInventarioBilletesCOP($datos)
{
  $sql = conexion()->prepare('SELECT
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete50 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete50 ELSE 0 END), 0) AS Billete50,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete100 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete100 ELSE 0 END), 0) AS Billete100,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete200 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete200 ELSE 0 END), 0) AS Billete200,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete500 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete500 ELSE 0 END), 0) AS Billete500,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete1000 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete1000 ELSE 0 END), 0) AS Billete1000,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete2000 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete2000 ELSE 0 END), 0) AS Billete2000,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete5000 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete5000 ELSE 0 END), 0) AS Billete5000,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete10000 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete10000 ELSE 0 END), 0) AS Billete10000,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete20000 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete20000 ELSE 0 END), 0) AS Billete20000,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete50000 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete50000 ELSE 0 END), 0) AS Billete50000,

    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 1 THEN Billete100000 ELSE 0 END), 0) - 
    COALESCE(SUM(CASE WHEN EstatusBilleteCOP = 2 THEN Billete100000 ELSE 0 END), 0) AS Billete100000
FROM
    facturascontrolcop 
INNER JOIN facturasresumen ON facturascontrolcop.NroVenta = facturasresumen.IDResumenVenta
WHERE
    Fecha = ? AND IDSucursal = ? AND IDCaja = ? AND Estatus != 2');
  $sql->execute($datos);
  return $sql->fetch(PDO::FETCH_ASSOC);
}

//! REGISTRAR RESUMEN PAGO MOVIL
function facturacionRegistrarPagoMovil($datos)
{
  $conexion = conexion();
  $sql = $conexion->prepare('INSERT INTO
  facturasvueltobdv (
    Fecha,
    IDSucursal,
    IDCaja,
    NroVenta,
    CedulaDestino,
    TLFDestino,
    BancoDestino,
    MontoVuleto,
    Referencia,
    Concepto,
    Responsable
  )
VALUES
  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
  $sql->execute($datos);
  return $conexion->lastInsertId();
}

//! GENERAR NUMERO DE REFERENCIA
function facturaGeneraNroReferencia()
{
  $sql = conexion()->query('SELECT Referencia FROM facturasvueltobdv GROUP BY Referencia ORDER BY Referencia DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
  if ($sql === FALSE) return 1;
  return $sql['Referencia'] + 1;
}