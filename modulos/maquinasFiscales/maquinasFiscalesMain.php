<?php

//* VERIFICAR MAQUINA FISCAL POR NRO DE SERIAL
function maquinasFiscalesVerificarXSERIAL($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  facturas_maquinas_fiscales
WHERE
  SerialMaquinaFiscal = ?');
  $sql->execute($datos);
  return $sql;
}

//* REGISTRAR REPORTE Z
function maquinasFiscalesRegistrarReportZ($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  facturas_reportes_z (
    FechaCierreReporteZ,
    IDMaquinaFiscal,
    NroReporteZ,
    NroFacturaDesde,
    NroFacturaHasta,
    MontoTotalExento,
    MontoTotalBaseImponible,
    MontoTotalExentoNotaCredito,
    MontoTotalBaseImponibleNotaCredito,
    ReponsableRegFacturaReporteZ,
    EstadoFacturaReporteZ
  )
VALUES
  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)');
  $sql->execute($datos);
  return $sql;
}

//* LISTA DE MAQUINA FISCALES
function maquinasFiscalesLista($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  facturas_reportes_z
  INNER JOIN facturas_maquinas_fiscales ON facturas_reportes_z.IDMaquinaFiscal = facturas_maquinas_fiscales.IDMaquinaFiscal
  INNER JOIN sucursales ON facturas_maquinas_fiscales.IDSucursal = sucursales.IDSucursal
WHERE
  facturas_maquinas_fiscales.IDSucursal = ?
  AND FechaCierreReporteZ BETWEEN ? AND ?
GROUP BY facturas_reportes_z.IDMaquinaFiscal');
  $sql->execute($datos);
  return $sql;
}

//* CONSULTAR NRO DE MAQUINA FISCAL
function maquinasFiscalesConsultarXID($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  facturas_reportes_z
  INNER JOIN facturas_maquinas_fiscales ON facturas_reportes_z.IDMaquinaFiscal = facturas_maquinas_fiscales.IDMaquinaFiscal
WHERE
  facturas_reportes_z.IDMaquinaFiscal = ?
  AND FechaCierreReporteZ BETWEEN ? AND ?');
  $sql->execute($datos);
  return $sql;
}

//* CONSULTAR NRO DE MAQUINA FISCAL
function maquinasFiscalesConsultarXFecha($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  facturas_reportes_z
  INNER JOIN facturas_maquinas_fiscales ON facturas_reportes_z.IDMaquinaFiscal = facturas_maquinas_fiscales.IDMaquinaFiscal
  INNER JOIN sucursales ON facturas_maquinas_fiscales.IDSucursal = sucursales.IDSucursal
WHERE
  sucursales.IDSucursal = ?
  AND FechaCierreReporteZ BETWEEN ? AND ?');
  $sql->execute($datos);
  return $sql;
}
