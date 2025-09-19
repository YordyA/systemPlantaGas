<?php

//! ojo
function SumaSalidasDeInventario($id)
{
  $reportes = conexion()->prepare("SELECT  articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo,  SUM(Cantidad) as salidas
                    FROM inventariosalidas 
                    JOIN articulosdeinventario ON inventariosalidas.IDArticulo=articulosdeinventario.IDArticulo
                    WHERE inventariosalidas.FechaMovimientoSalida BETWEEN ? AND ? AND inventariosalidas.IDSucursal= ?
                    GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC
                    ");

  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}

//! ojo
function SumaEntradasDeInventario($id)
{
  $reportes = conexion()->prepare("SELECT articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo, SUM(Cantidad) as entradas
  FROM inventarioentradas 
  JOIN articulosdeinventario ON inventarioentradas.IDArticulo=articulosdeinventario.IDArticulo 
  WHERE inventarioentradas.FechaMovimientoEntrada  BETWEEN ? AND ? AND inventarioentradas.IDSucursal= ?
  GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC");

  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}

# DATOS DE LOS CONTEOS FISICOS INICIAL
function sumas_conteo($id)
{
  $reportes = conexion()->prepare("SELECT articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo,
  SUM(ExistenciaFisica) as pesaje_inicial,
  SUM(ExistenciaSistema) as inicial,
  SUM(Diferencia) as mermas_cava
  FROM inventarioconteo 
  JOIN articulosdeinventario ON inventarioconteo.IDArticulo=articulosdeinventario.IDArticulo
  WHERE inventarioconteo.Fecha BETWEEN ? AND ? AND  inventarioconteo.IDSucursal= ?
  AND TipoConteo = 0 GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC ");

  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}

//? DATOS DE LOS CONTEOS FISICOS INICIAL
function ConteoFisicoInicial($id)
{
  $reportes = conexion()->prepare("SELECT articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo,
  SUM(ExistenciaFisica) as pesaje_inicial,
  SUM(ExistenciaSistema) as inicial,
  SUM(Diferencia) as mermas_cava
  FROM inventarioconteo 
  JOIN articulosdeinventario ON inventarioconteo.IDArticulo=articulosdeinventario.IDArticulo
  WHERE inventarioconteo.Fecha = ? AND  inventarioconteo.IDSucursal= ?
  AND TipoConteo = 0 GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC ");

  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}

# DATOS DE LOS CONTEOS FISICOS INICIAL
function sumas_conteo_final($id)
{
  $reportes = conexion()->prepare("SELECT articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo, SUM(ExistenciaFisica) as pesaje_final,
   SUM(Diferencia) as mermas_manipulacion
  FROM inventarioconteo 
  JOIN articulosdeinventario ON inventarioconteo.IDArticulo=articulosdeinventario.IDArticulo
  WHERE inventarioconteo.Fecha BETWEEN ? AND ? AND  inventarioconteo.IDSucursal= ?
  AND TipoConteo = 1 GROUP BY articulosdeinventario.IDArticulo ORDER BY articulosdeinventario.IDArticulo ASC
                    ");

  $reportes->execute($id);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}
# SUMA CANTIDAD DE VENTAS
function catidad_de_ventas_por_articulos($datos)
{
  $reporte = conexion()->prepare("SELECT facturasresumen.*, articulosdeinventario.DescripcionArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.IDAlicuota,
  facturasdetalle.Precio,
  SUM(facturasdetalle.Cantidad) as sum_cantidad,
  SUM(facturasdetalle.SubTotal) as sum_total
FROM facturasresumen
  INNER JOIN facturasdetalle ON facturasresumen.NVenta = facturasdetalle.NVenta
  INNER JOIN articulosdeinventario ON facturasdetalle.IDProducto = articulosdeinventario.IDArticulo
WHERE 
facturasresumen.Fecha BETWEEN ? AND ? AND facturasresumen.IDSucursal = ? AND facturasresumen.Estatus < 2
GROUP BY articulosdeinventario.IDArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

//! CONSULTAR VENTA POR NUMERO
function consultarVentaPorNventa($datos)
{
  $consulta = conexion()->prepare('SELECT
  *,
  facturasresumen.Estatus AS EstadoFactura
FROM
  facturasresumen
  INNER JOIN caja ON facturasresumen.IDCaja = caja.IDCaja
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN productos ON facturasdetalle.IDProducto = productos.IDProducto
  INNER JOIN tipo_productos on productos.IDTipoProducto = tipo_productos.IDTipo
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
WHERE
  facturasresumen.IDResumenVenta = ?');
  $consulta->execute($datos);
  return $consulta;
}


# CONSULTAR FACTURA EN ESPERA
function consultarFacturasEnEspera($datos)
{
  $sql = conexion()->prepare('SELECT * FROM facturasespera 
          INNER JOIN articulosdeinventario ON facturasespera.IDArticulo = articulosdeinventario.IDArticulo
          INNER JOIN clientes ON facturasespera.IDCliente = clientes.IDCliente
        WHERE facturasespera.IDSucursal = ? AND facturasespera.NFacturaEspera = ?');
  $sql->execute($datos);
  return $sql;
}

//^ CONSULTAR FACTURA EN ESPERA
function consultarFacturasEnEsperaDonacion($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  facturasespera
  INNER JOIN existenciaporsucursal ON facturasespera.IDArticulo = existenciaporsucursal.IDArticulo
  INNER JOIN clientes ON facturasespera.IDCliente = clientes.IDCliente
WHERE
  existenciaporsucursal.IDSucursal = ?
  AND facturasespera.IDSucursal = ?
  AND facturasespera.NFacturaEspera = ?');
  $sql->execute($datos);
  return $sql;
}


# LISTA DE RESES EN CANAL
function lista_de_conteo_fisicos($id)
{
  $reportes = conexion()->prepare('SELECT * FROM inventarioconteo WHERE Anulado = 0 AND IDSucursal = ? GROUP BY IDConteo ORDER BY FechaHora DESC');
  $reportes->execute([$id]);
  return  $reportes->fetchAll(PDO::FETCH_ASSOC);
}

# REPORTE DE ENTRADAS Y SALIDAS DE PRODUCTOS
function existencia_entre_fechas($datos)
{
  $reporte = conexion()->prepare("SELECT  
  articulosdeinventario.DescripcionArticulo,
  articulosdeinventario.CodigoArticulo,
  inventarioconteo.ExistenciaFisica,
  sum(inventarioentradas.Cantidad) as sum_entradas, 
  sum(inventariosalidas.Cantidad) as sum_salidas,
  (inventarioconteo.ExistenciaFisica + sum(inventarioentradas.Cantidad) - sum(inventariosalidas.Cantidad)) as Resultado
FROM 
  articulosdeinventario
  INNER JOIN  inventarioentradas ON articulosdeinventario.IDArticulo =  inventarioentradas.IDArticulo
  INNER JOIN  inventariosalidas ON articulosdeinventario.IDArticulo =  inventariosalidas.IDArticulo
  INNER JOIN inventarioconteo ON articulosdeinventario.IDArticulo = inventarioconteo.IDArticulo
WHERE 
  inventariosalidas.FechaMovimiento BETWEEN ? AND ?
  AND inventarioentradas.FechaMovimiento BETWEEN ? AND ?
  and inventarioconteo.Fecha= ? 
  AND inventarioconteo.IDSucursal= ?
GROUP BY  
  inventarioconteo.IDArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

# REPORTES DE GASTOS POR TIPO DE GASTO
function reportGastoAdministracion($datos)
{
  $sql = conexionAdministracion()->prepare('SELECT
  *
FROM
  gastos_administrativo
  INNER JOIN historial_tasa_bcv ON gastos_administrativo.FechaGasto = historial_tasa_bcv.FechaTasa
WHERE
  EstadoGasto = 2 AND FechaGasto BETWEEN ? AND ? AND gastos_administrativo.IDCentroCosto = ?');
  $sql->execute($datos);
  return $sql;
}


//? REPORTE DE RESES DESPOSTADAS
function ReporteDeResesDespostadas($datos)
{
  $reporte = conexion()->prepare('SELECT
    inventarioreses.CodigoRes,
    inventarioreses.Peso,
    inventariocontroldesposte.*
FROM
    inventariocontroldesposte
INNER JOIN inventarioreses ON inventariocontroldesposte.IDRes = inventarioreses.IDRes
WHERE
    inventariocontroldesposte.Fecha BETWEEN ? AND ? AND inventariocontroldesposte.IDSucursal = ? ');
  $reporte->execute($datos);
  return $reporte;
}

# REPORTES DE GASTOS POR TIPO DE GASTO
function reporte_de_resesAnterior($datos)
{
  $reporte = conexion()->prepare('SELECT inventarioreses.CodigoRes, inventarioreses.Peso, inventarioresesdespostadas.* from inventarioresesdespostadas inner join inventarioreses on inventarioresesdespostadas.IDRes = inventarioreses.IDRes
  WHERE inventarioresesdespostadas.Fecha BETWEEN ? AND ?  AND inventarioresesdespostadas.IDSucursal= ?  ');
  $reporte->execute($datos);
  return $reporte;
}


//? REPORTE DE FACTURAS EMITIDAS
function reporteFacturasEmitidas($datos)
{
  $sql = conexion()->prepare('SELECT  clientes.RifCliente, clientes.NombreCliente, caja.DescripcionCaja, facturasresumen.*
FROM
  facturasresumen
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
  INNER JOIN caja ON facturasresumen.IDCaja = caja.IDCaja
WHERE
  facturasresumen.IDSucursal = ?
  AND facturasresumen.Fecha BETWEEN ? AND ?
ORDER BY
  facturasresumen.IDResumenVenta DESC');
  $sql->execute($datos);
  return $sql;
}

//* HISTORIAL DE CxC 
function reporteHistorialCxC($datos)
{
  $sql = conexion()->prepare('SELECT
    clientes.RifCliente,
    clientes.NombreCliente,
    caja.DescripcionCaja,
    facturasresumen.*
FROM
    facturasresumen
INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
INNER JOIN caja ON facturasresumen.IDCaja = caja.IDCaja
WHERE
	facturasresumen.Fecha != facturasresumen.FechaCobranza 
  AND facturasresumen.Estatus = 0
  AND facturasresumen.IDSucursal = ?
  AND facturasresumen.Fecha BETWEEN ? AND ?
ORDER BY
    facturasresumen.IDResumenVenta
DESC');
  $sql->execute($datos);
  return $sql;
}


//! REPORTE DE FACTURAS
function reporteFacturasEmitidasCxC($datos)
{
  $sql = conexion()->prepare('SELECT
    *
FROM
    facturasresumen
INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
WHERE
    IDSucursal = ? AND facturasresumen.Fecha BETWEEN ? AND ? AND facturasresumen.Estatus = 1
ORDER BY
    facturasresumen.IDResumenVenta
DESC');
  $sql->execute($datos);
  return $sql;
}
# REPORTE DE FACTURAS
function reporteFacturasEnEspera($datos)
{
  $sql = conexion()->prepare('SELECT * FROM facturasespera 
            INNER JOIN clientes ON facturasespera.IDCliente = clientes.IDCliente 
          WHERE IDSucursal = ? AND IDCaja = ? GROUP by NFacturaEspera ORDER BY NFacturaEspera DESC');
  $sql->execute($datos);
  return $sql;
}

# REPORTE DE FACTURAS DE DONACIONES
function reporteFacturasDonaciones($datos)
{
  $sql = conexion()->prepare('SELECT
    clientes.NombreCliente,
    clientes.RifCliente,
    donacionesresumen.IDDonaciones,
    donacionesresumen.Fecha,
    donacionesresumen.TipoConsumo,
    donacionesresumen.Estatus,
    donacionesresumen.NDonacion,
    donacionesresumen.responsable,
    ROUND(SUM(donacionesdetalle.SubTotal), 2) AS MontoTotalBS,
    ROUND(SUM(donacionesdetalle.SubTotal / historial_tasa_bcv.TasaRefUSD), 2) AS MontoTotalUSD
FROM
    donacionesdetalle
INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.IDDonaciones
INNER JOIN agroflor_administracion_empresas.historial_tasa_bcv AS historial_tasa_bcv ON donacionesresumen.Fecha = historial_tasa_bcv.FechaTasa
INNER JOIN clientes ON donacionesresumen.IDCliente = clientes.IDCliente
WHERE
    IDSucursal = ?
    AND Fecha BETWEEN ? AND ?
GROUP BY donacionesresumen.IDDonaciones');
  $sql->execute($datos);
  return $sql;
}

# REPORTE DE RESES DESPOSTADAS
function reporteResesDespostadas($datos)
{
  $sql = conexion()->prepare('SELECT * 
  FROM inventariocontroldesposte
  INNER JOIN inventarioresesdespostadasprecios ON inventarioresesdespostadasprecios.IDRes = inventariocontroldesposte.IDRes
  INNER JOIN inventarioreses ON inventarioreses.IDRes = inventariocontroldesposte.IDRes
  WHERE inventariocontroldesposte.IDRes = ? AND inventariocontroldesposte.IDSucursal = ?');
  $sql->execute($datos);
  return  $sql->fetch(PDO::FETCH_ASSOC);
}


# REPORTE DE RESES DESPOSTADAS
function reporteResesporid($datos)
{
  $sql = conexion()->prepare('SELECT * 
  FROM inventarioreses
  WHERE inventarioreses.IDRes = ? AND inventarioreses.IDSucursal = ?');
  $sql->execute($datos);
  return $sql;
}

# REPORTE DE RESES DESPOSTADAS
function reporteResesporiddesposte($datos)
{
  $sql = conexion()->prepare('SELECT  inventarioreses.CodigoRes, inventariocontroldesposte.* 
  FROM inventariocontroldesposte INNER JOIN inventarioreses on inventariocontroldesposte.IDRes = inventarioreses.IDRes
  WHERE inventariocontroldesposte.ID = ?');
  $sql->execute($datos);
  return $sql;
}


//^ REPORTE DE RESES DESPOTADAS
function reporteListaResesDespostadas($datos)
{
  $sql = conexion()->prepare('SELECT
  inventariocontroldesposte.*,
  inventarioreses.CodigoRes,
  inventarioreses.Peso,
  inventarioreses.TotalCosto
FROM
  inventariocontroldesposte
  INNER JOIN inventarioreses ON inventarioreses.IDRes = inventariocontroldesposte.IDRes
WHERE
  inventariocontroldesposte.Fecha BETWEEN ? AND ?
  AND inventariocontroldesposte.IDSucursal = ?
  AND inventariocontroldesposte.Estado = 0
GROUP BY
  inventariocontroldesposte.IDRes
ORDER BY
  inventariocontroldesposte.Fecha DESC');
  $sql->execute($datos);
  return $sql;
}

# REPORTE DE INVENTARIOS FINALES
function inventario_final($datos)
{
  $reporte = conexion()->prepare("SELECT * FROM valorinventariofinal WHERE FechaCierre =  ?  AND IDSucursal= ?");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

# REPORTE DE INVENTARIOS FINALES
function inventario_final_General($datos)
{
  $reporte = conexion()->prepare("SELECT * FROM valorinventariofinal WHERE FechaCierre =  ?");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

//! REPORTE EXISTENCIA FINAL
function reporteExistenciaFinal($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  valorinventariofinal
  INNER JOIN articulosdeinventario ON valorinventariofinal.IDArticulo = articulosdeinventario.IDArticulo
WHERE
  IDSucursal = ?
  AND FechaCierre = ?');
  $sql->execute($datos);
  return $sql;
}

//! CONTEO FISICO
function ConteoFisicoPorFecha($id)
{
  $producto = conexion()->prepare('SELECT * FROM  articulosdeinventario
  INNER JOIN  inventarioconteo ON articulosdeinventario.IDArticulo = inventarioconteo.IDArticulo
  WHERE  inventarioconteo.Fecha= ? AND inventarioconteo.TipoConteo = ? AND  inventarioconteo.IDSucursal = ?');
  $producto->execute($id);
  return   $producto->fetchAll(PDO::FETCH_ASSOC);
}


//? LISTAR TODAS LAS SALIDAS DEL INVENTARIO
function ListaSalidaInventario($datos)
{
  $reporte = conexion()->prepare('SELECT  articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo, inventariosalidas.* from inventariosalidas inner join articulosdeinventario on inventariosalidas.IDArticulo = articulosdeinventario.IDArticulo
  WHERE inventariosalidas.FechaMovimientoSalida BETWEEN ? AND ?  AND inventariosalidas.IDSucursal= ?  ');
  $reporte->execute($datos);
  return $reporte;
}

//? LISTAR TODAS LAS ENTRADAS DEL INVENTARIO
function ListaEntradaInventario($datos)
{
  $reporte = conexion()->prepare('SELECT  articulosdeinventario.CodigoArticulo, articulosdeinventario.DescripcionArticulo, inventarioentradas.* from inventarioentradas inner join articulosdeinventario on inventarioentradas.IDArticulo = articulosdeinventario.IDArticulo
  WHERE inventarioentradas.FechaMovimientoEntrada BETWEEN ? AND ?  AND inventarioentradas.IDSucursal= ?  ');
  $reporte->execute($datos);
  return $reporte;
}

//* CONSULTA GASTOS POR FRIGORIFICO RESUMIDO
function administracionReportGastosResumido($datos)
{
  $sql = conexionAdministracion()->prepare('SELECT
  gastos_administrativo.IDTipoGasto,
  SUM(MontoTotalGastoUSD) AS MontoTotalUSD,
  SUM(CASE WHEN FechaGasto = FechaTasa THEN (MontoTotalGastoUSD * TasaRefUSD) ELSE 0 END) AS MontoTotalBS
FROM
  gastos_administrativo
  INNER JOIN tipos_gasto ON gastos_administrativo.IDTipoGasto = tipos_gasto.IDTipoGasto
  INNER JOIN historial_tasa_bcv ON gastos_administrativo.FechaGasto = historial_tasa_bcv.FechaTasa
WHERE
  EstadoGasto = 2
  AND IDCentroCosto = ?
  AND FechaGasto BETWEEN ? AND ?
GROUP BY tipos_gasto.IDTipoGasto');
  $sql->execute($datos);
  return $sql;
}

//* CONSULTA GASTOS GENERAL FRIGORIFICO RESUMIDO
function administracionReportGastosResumidoGeneral($datos)
{
  $sql = conexionAdministracion()->prepare('SELECT
  gastos_administrativo.IDTipoGasto,
  SUM(MontoTotalGastoUSD) AS MontoTotalUSD,
  SUM(CASE WHEN FechaGasto = FechaTasa THEN (MontoTotalGastoUSD * TasaRefUSD) ELSE 0 END) AS MontoTotalBS
FROM
  gastos_administrativo
  INNER JOIN tipos_gasto ON gastos_administrativo.IDTipoGasto = tipos_gasto.IDTipoGasto
  INNER JOIN historial_tasa_bcv ON gastos_administrativo.FechaGasto = historial_tasa_bcv.FechaTasa
WHERE
  EstadoGasto = 2
  AND IDCentroCosto IN(36,35,34,33,32,39,40)
  AND FechaGasto BETWEEN ? AND ?
GROUP BY tipos_gasto.IDTipoGasto');
  $sql->execute($datos);
  return $sql;
}


//* REPORTE DE ENTREGA DE PRODUCTOS
function reportCobrarListaEntregaProductos($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  facturasresumen
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
  INNER JOIN caja ON facturasresumen.IDCaja = caja.IDCaja
WHERE
  facturasresumen.Estatus = 3
  AND facturasresumen.IDSucursal = ?
  AND DATE(facturasresumen.FechaHora) BETWEEN ? AND ?');
  $sql->execute($datos);
  return $sql;
}
