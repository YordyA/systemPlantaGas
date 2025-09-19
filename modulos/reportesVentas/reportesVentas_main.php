<?php


//! FACTURACION POR ARTICULO
function FacturacionPorArticulo($datos)
{
  $reporte = conexion()->prepare("SELECT 
  facturasdetalle.IDProducto,
  facturasresumen.IDSucursal,
  articulosdeinventario.DescripcionArticulo,
  articulosdeinventario.CodigoArticulo,
  articulosdeinventario.IDAlicuota,
  contabilidadpartidas.CodigoPartida AS CodigoPartida,
  SUM(facturasdetalle.Cantidad) AS TotalCantidad,
  SUM(facturasdetalle.SubTotal) AS TotalBs,
  ROUND(SUM(CASE WHEN facturasresumen.Fecha = TasaUsd.FechaTasa THEN (facturasdetalle.SubTotal / TasaUsd.TasaRefUSD) ELSE 0 END), 2) AS TotalUSD
FROM  
  facturasresumen
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN articulosdeinventario ON facturasdetalle.IDProducto = articulosdeinventario.IDArticulo
  INNER JOIN existenciaporsucursal ON articulosdeinventario.IDArticulo = existenciaporsucursal.IDArticulo
  LEFT JOIN contabilidadpartidas ON existenciaporsucursal.PartidaContable = contabilidadpartidas.IDPartida
  INNER JOIN agroflor_administracion_empresas.historial_tasa_bcv AS TasaUsd ON facturasresumen.Fecha = TasaUsd.FechaTasa
WHERE
  facturasresumen.Fecha BETWEEN ? AND ?
  AND facturasresumen.IDSucursal = ?
  AND existenciaporsucursal.IDSucursal = ?
  AND facturasresumen.Estatus NOT IN (2, 3)
GROUP BY 
  facturasdetalle.IDProducto,
  facturasresumen.IDSucursal,
  articulosdeinventario.DescripcionArticulo,
  articulosdeinventario.CodigoArticulo,
  articulosdeinventario.IDAlicuota,
  contabilidadpartidas.CodigoPartida
ORDER BY 
  articulosdeinventario.CodigoArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}


//! REPORTE DE CUADRE DE CAJA POR TIPO DE COBRO

function CadreDeCajaResumido($datos)
{
  $reporte = conexion()->prepare("SELECT
  facturasresumen.IDCaja,
  caja.DescripcionCaja,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.Efectivo ELSE 0 END) AS sum_efectivo_estatus_0,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.CrucesFacturas ELSE 0 END) AS total_cruce,
  SUM(CASE WHEN facturasresumen.ESTATUS = 1 THEN facturasresumen.total ELSE 0 END) AS sum_efectivo_estatus_1,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.Tarjeta ELSE 0 END) AS sum_tarjeta,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.BioPago ELSE 0 END) AS sum_biopago,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.Transferencia ELSE 0 END) AS sum_transferencia,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.VueltoEfectivo ELSE 0 END) AS sum_vuelto,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.VueltoPagoMovil ELSE 0 END) AS sum_vuelto_movil,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.Transferencia + facturasmediopago.BioPago + facturasmediopago.Tarjeta + facturasmediopago.Efectivo + facturasmediopago.CrucesFacturas ELSE 0 END) AS total_caja,
  SUM(CASE WHEN facturasresumen.ESTATUS = 0 THEN facturasmediopago.Transferencia + facturasmediopago.BioPago + facturasmediopago.Tarjeta + facturasmediopago.Efectivo ELSE 0 END) AS total_caja_precivido,
  SUM(CASE WHEN facturasresumen.ESTATUS = 2 THEN facturasresumen.total ELSE 0 END) AS total_anulado
FROM 
  facturasresumen
  INNER JOIN facturasmediopago ON facturasresumen.IDResumenVenta = facturasmediopago.NVenta
  INNER JOIN caja ON facturasresumen.IDCaja = caja.IDCaja
WHERE 
  facturasresumen.Fecha BETWEEN ? AND ?
  AND facturasresumen.IDSucursal = ? 
GROUP BY  
  facturasresumen.IDCaja, caja.DescripcionCaja, facturasresumen.IDSucursal");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

//! CUADRE DE CAJA DETALLADO
function CuadreDeCajaDetallado($datos)
{
  $reporte = conexion()->prepare('SELECT
    clientes.RifCliente,
    clientes.NombreCliente,
    facturasresumen.NFacturaFiscal,
    facturasresumen.Estatus,
    facturasresumen.IDCaja,
    facturasresumen.total AS TotalFactura,
    facturasresumen.Fecha,
    facturasmediopago.*
FROM
    facturasresumen
INNER JOIN facturasmediopago ON facturasresumen.IDResumenVenta = facturasmediopago.NVenta
INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
WHERE
    facturasresumen.Fecha BETWEEN ? AND ?
    AND facturasresumen.IDSucursal = ?
ORDER BY
    facturasresumen.NVentaResumen ASC');
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralConteoFisicoInicial($id)
{
  $reportes = conexion()->prepare("SELECT
  articulosdeinventario.IDArticulo,
  articulosdeinventario.CodigoArticulo,
  articulosdeinventario.DescripcionArticulo,
  SUM(ExistenciaFisica) as pesaje_inicial
FROM
  inventarioconteo
  JOIN articulosdeinventario ON inventarioconteo.IDArticulo = articulosdeinventario.IDArticulo
WHERE
  TipoConteo = 0
  AND inventarioconteo.Fecha = ?
  AND inventarioconteo.IDSucursal = ?
GROUP BY
  articulosdeinventario.IDArticulo
ORDER BY
  articulosdeinventario.IDArticulo ASC");
  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralExistenciaInicial($id)
{
  $reportes = conexion()->prepare("SELECT
  articulosdeinventario.IDArticulo,
  articulosdeinventario.CodigoArticulo,
  articulosdeinventario.DescripcionArticulo,
  SUM(ExistenciaSistema) as inventario_inicial
FROM
  inventarioconteo
  INNER JOIN articulosdeinventario ON inventarioconteo.IDArticulo = articulosdeinventario.IDArticulo
WHERE
  TipoConteo = 0
  AND inventarioconteo.Fecha = ?
  AND inventarioconteo.IDSucursal = ?
GROUP BY
  articulosdeinventario.IDArticulo
ORDER BY
  articulosdeinventario.IDArticulo ASC");
  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralConteoFisicoInicialDiferencias($id)
{
  $reportes = conexion()->prepare("SELECT articulosdeinventario.IDArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo,
  SUM(Diferencia) as inical_diferencias
  FROM inventarioconteo 
  INNER JOIN articulosdeinventario ON inventarioconteo.IDArticulo=articulosdeinventario.IDArticulo
  WHERE Fecha = ? AND  inventarioconteo.IDSucursal= ?
  AND TipoConteo = 0 GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC ");

  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralConteoFisicoFinal($id)
{
  $reportes = conexion()->prepare("SELECT articulosdeinventario.IDArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo,
   SUM(ExistenciaFisica) as pesaje_final
  FROM inventarioconteo 
  JOIN articulosdeinventario ON inventarioconteo.IDArticulo=articulosdeinventario.IDArticulo
  WHERE inventarioconteo.Fecha = ? AND  inventarioconteo.IDSucursal= ?
  AND TipoConteo = 1 GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC");

  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralConteoFisicoFinalMermas($id)
{
  $reportes = conexion()->prepare("SELECT articulosdeinventario.IDArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo,
   SUM(Diferencia) as mermas_manipulacion
  FROM inventarioconteo 
  JOIN articulosdeinventario ON inventarioconteo.IDArticulo=articulosdeinventario.IDArticulo
  WHERE Fecha BETWEEN ? AND ? AND  inventarioconteo.IDSucursal= ? AND Diferencia < 0
  GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC");
  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralSalidasDeInventario($id)
{
  $reportes = conexion()->prepare("SELECT  articulosdeinventario.IDArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo, 
  SUM(Cantidad) as salidas
  FROM inventariosalidas 
  JOIN articulosdeinventario ON inventariosalidas.IDArticulo=articulosdeinventario.IDArticulo
  WHERE inventariosalidas.FechaMovimientoSalida BETWEEN ? AND ? AND inventariosalidas.IDSucursal= ?
  GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC");
  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}

function GeneralSalidasDeInventarioOtrasSalidas($id)
{
  $reportes = conexion()->prepare("SELECT  
    articulosdeinventario.IDArticulo, 
    articulosdeinventario.CodigoArticulo, 
    articulosdeinventario.DescripcionArticulo, 
    SUM(inventariosalidas.Cantidad) as otras_salidas
FROM 
    inventariosalidas 
JOIN 
    articulosdeinventario ON inventariosalidas.IDArticulo = articulosdeinventario.IDArticulo
WHERE 
    inventariosalidas.FechaMovimientoSalida BETWEEN ? AND ?
    AND inventariosalidas.IDSucursal = ?
    AND ConceptodeMovimiento NOT LIKE '%CONTEO FISICO: MERMA EN CAVA%'  
    AND ConceptodeMovimiento NOT LIKE '%CONTEO FISICO: MANIPULACION DEL DIA%'
    AND ConceptodeMovimiento NOT LIKE '%VENTA NRO%'
    AND ConceptodeMovimiento NOT LIKE '%DONACION NRO%'
    GROUP BY articulosdeinventario.IDArticulo 
    ORDER BY articulosdeinventario.IDArticulo ASC");

  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}

function GeneralEntradasDeInventario($id)
{
  $reportes = conexion()->prepare("SELECT articulosdeinventario.IDArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo,
  SUM(Cantidad) as entradas
  FROM inventarioentradas 
  JOIN articulosdeinventario ON inventarioentradas.IDArticulo=articulosdeinventario.IDArticulo 
  WHERE inventarioentradas.FechaMovimientoEntrada  BETWEEN ? AND ? AND inventarioentradas.IDSucursal= ?
  GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC");
  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}

function GeneralTotalVentas($datos)
{
  $reporte = conexion()->prepare("SELECT facturasresumen.*, articulosdeinventario.IDArticulo, articulosdeinventario.DescripcionArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.IDAlicuota,
  SUM(facturasdetalle.SubTotal) as sum_total
FROM facturasresumen
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN articulosdeinventario ON facturasdetalle.IDProducto = articulosdeinventario.IDArticulo
WHERE  facturasresumen.Fecha BETWEEN ? AND ? AND facturasresumen.IDSucursal = ? AND facturasresumen.Estatus < 2
GROUP BY articulosdeinventario.IDArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralTotalVentasUSD($datos)
{
  $reporte = conexion()->prepare("SELECT facturasresumen.*, articulosdeinventario.IDArticulo, articulosdeinventario.DescripcionArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.IDAlicuota,
  SUM(facturasdetalle.SubTotal) / NULLIF(TasaUsd.TasaRefUSD, 0) AS TotalUSD
  FROM facturasresumen
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN articulosdeinventario ON facturasdetalle.IDProducto = articulosdeinventario.IDArticulo
  INNER JOIN agroflor_administracion_empresas.historial_tasa_bcv AS TasaUsd ON facturasresumen.Fecha = TasaUsd.FechaTasa
  WHERE  facturasresumen.Fecha BETWEEN ? AND ? AND facturasresumen.IDSucursal = ? AND facturasresumen.Estatus < 2
  GROUP BY articulosdeinventario.IDArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralTotalVentasCantidad($datos)
{
  $reporte = conexion()->prepare("SELECT facturasresumen.*, articulosdeinventario.IDArticulo,  articulosdeinventario.DescripcionArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.IDAlicuota,
  SUM(facturasdetalle.Cantidad) as sum_cantidad
FROM facturasresumen
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN articulosdeinventario ON facturasdetalle.IDProducto = articulosdeinventario.IDArticulo
WHERE facturasresumen.Fecha BETWEEN ? AND ? AND facturasresumen.IDSucursal = ? AND facturasresumen.Estatus < 2
GROUP BY articulosdeinventario.IDArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}


function GeneralDonaciones($datos)
{
  $reporte = conexion()->prepare("SELECT donacionesresumen.*, articulosdeinventario.IDArticulo, articulosdeinventario.DescripcionArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.IDAlicuota,
  SUM(donacionesdetalle.Cantidad) as donaciones
FROM donacionesresumen
  INNER JOIN donacionesdetalle ON donacionesresumen.IDDonaciones = donacionesdetalle.NDonacion
  INNER JOIN articulosdeinventario ON donacionesdetalle.IDProducto = articulosdeinventario.IDArticulo
WHERE 
donacionesresumen.Fecha BETWEEN ? AND ? AND donacionesresumen.IDSucursal = ? AND donacionesresumen.Estatus = 0 and  donacionesresumen.TipoConsumo = 0
GROUP BY articulosdeinventario.IDArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

function GeneralConsumos($datos)
{
  $reporte = conexion()->prepare("SELECT donacionesresumen.*, articulosdeinventario.IDArticulo, articulosdeinventario.DescripcionArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.IDAlicuota,
  SUM(donacionesdetalle.Cantidad) as donaciones
FROM donacionesresumen
  INNER JOIN donacionesdetalle ON donacionesresumen.IDDonaciones = donacionesdetalle.NDonacion
  INNER JOIN articulosdeinventario ON donacionesdetalle.IDProducto = articulosdeinventario.IDArticulo
WHERE 
donacionesresumen.Fecha BETWEEN ? AND ? AND donacionesresumen.IDSucursal = ? AND donacionesresumen.Estatus = 0 and  donacionesresumen.TipoConsumo = 1
GROUP BY articulosdeinventario.IDArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

//* CONSUTALTA PARA GENERAL LIBRO DE VENTA AUXILIAR
function reporteLibroDeVentas($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  facturasresumen
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
WHERE
  Fecha BETWEEN ? AND ?
  AND IDSucursal = ?');
  $sql->execute($datos);
  return $sql->fetchAll(PDO::FETCH_ASSOC);
}
