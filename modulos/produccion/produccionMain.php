<?php

//* GENERAR NRO DE LOTE
function produccionGenerarNroLote($datos)
{
  $sql = conexion()->prepare('SELECT NroLote FROM produccion_resumen WHERE IDArticulo = ? GROUP BY NroLote ORDER BY NroLote DESC LIMIT 1');
  $sql->execute($datos);
  $sql = $sql->fetch(PDO::FETCH_ASSOC);
  if ($sql === FALSE) return 1;
  return $sql['NroLote'] + 1;
}

//* REGISTRAR RESUMEN DE LA PRODUCCION
function produccionRegistrarResumen($datos)
{
  $conexion = conexion();
  $sql = $conexion->prepare('INSERT INTO
  produccion_resumen (
    FechaProduccion,
    FechaCaducidad,
    IDArticulo,
    NroLote,
    IDEmpaque,
    ResponsableProduccion,
    EstadoProduccion
  )
VALUES
  (?, ?, ?, ?, ?, ?, 2)');
  $sql->execute($datos);
  return $conexion->lastInsertId();
}

//* REGISTRAR DETALLE
function produccionRegistrarDetalle($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  produccion_detalles (
    IDProduccionResumen,
    IDInvProduccion,
    CostoUtilizado,
    CantidadUtilizada
  )
VALUES
  (?, ?, ?, ?)');
  $sql->execute($datos);
}

//* REGISTRAR SUBPRODUCTOS
function produccionRegistrarSuproductos($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  produccion_subproductos (
    Fecha,
    IDProduccionResumen,
    IDInvProduccion,
    Cantidad
  )
VALUES
  (?, ?, ?, ?)');
  $sql->execute($datos);
}


//* ACTUALIZAR CANTIDAD PRODUCIDA
function produccionActualizar($datos)
{
  $sql = conexion()->prepare('UPDATE produccion_resumen SET CantidadProducida =  CantidadProducida + ?, UltimaActualizacionProduccion = ? WHERE IDProduccionResumen = ?');
  $sql->execute($datos);
}


//* ACTUALIZAR ESTADO DE LA PRODUCCION
function produccionActualizarEstadoProduccion($datos)
{
  $sql = conexion()->prepare('UPDATE produccion_resumen SET EstadoProduccion = ?, UltimaActualizacionProduccion = ? WHERE IDProduccionResumen = ?');
  $sql->execute($datos);
}

//* CONSULTAR PRODUCCION
function produccionConsultarXID($datos)
{
  $sql = conexion()->prepare('SELECT
    inventario_produccion_tipos_productos.DescripcionTipoProducto,
    inventario_produccion.IDInvProduccion,
    inventario_produccion.CodigoProducto,
    inventario_produccion.DescripcionProducto,
    inventario_produccion.IDTipoProducto,
    produccion_detalles.CostoUtilizado,
    produccion_detalles.CantidadUtilizada,
    produccion_resumen.FechaProduccion,
    produccion_resumen.FechaCaducidad,
    articulos.CodigoArticulo,
    articulos.DescripcionArticulo,
    articulos_alicuotas.DescripcionAlicuota,
    Empaque.CodigoProducto AS CodigoEmpaque,
    Empaque.DescripcionProducto AS DescripcionEmpaque,
    produccion_resumen.NroLote,
    produccion_resumen.CantidadProducida,
    produccion_resumen.EstadoProduccion,
    produccion_resumen.ResponsableProduccion,
    inventario_produccion_medidas.DescripcionUnidadMedida
FROM
    produccion_detalles
INNER JOIN inventario_produccion ON produccion_detalles.IDInvProduccion = inventario_produccion.IDInvProduccion
INNER JOIN inventario_produccion_tipos_productos ON inventario_produccion.IDTipoProducto = inventario_produccion_tipos_productos.IDTipoProducto
LEFT JOIN inventario_produccion_medidas ON inventario_produccion.IDUnidadMedida = inventario_produccion_medidas.IDUnidadMedida
INNER JOIN produccion_resumen ON produccion_detalles.IDProduccionResumen = produccion_resumen.IDProduccionResumen
LEFT JOIN inventario_produccion AS Empaque ON produccion_resumen.IDEmpaque = Empaque.IDInvProduccion
INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
INNER JOIN articulos_alicuotas ON articulos.IDAlicuota = articulos_alicuotas.IDAlicuota
WHERE
    produccion_detalles.IDProduccionResumen = ? ORDER BY DescripcionTipoProducto DESC');
  $sql->execute($datos);
  return $sql;
}

//* CONSULTAR PRODUCCION SUBPRODUCTOS
function produccionConsultarSubProductosXID($datos)
{
  $sql = conexion()->prepare("SELECT
    produccion_subproductos.Fecha,
    produccion_subproductos.IDProduccionResumen,
    SUM(CASE WHEN produccion_subproductos.IDInvProduccion = '1' THEN produccion_subproductos.Cantidad ELSE 0 END) AS Harina,
    SUM(CASE WHEN produccion_subproductos.IDInvProduccion = '13' THEN produccion_subproductos.Cantidad ELSE 0 END) AS Pico,
    SUM(CASE WHEN produccion_subproductos.IDInvProduccion = '14' THEN produccion_subproductos.Cantidad ELSE 0 END) AS Barrido,
    SUM(CASE WHEN produccion_subproductos.IDInvProduccion = '15' THEN produccion_subproductos.Cantidad ELSE 0 END) AS Afrecho,
    SUM(CASE WHEN produccion_subproductos.IDInvProduccion = '16' THEN produccion_subproductos.Cantidad ELSE 0 END) AS Fecula,
    SUM(CASE WHEN produccion_subproductos.IDInvProduccion = '17' THEN produccion_subproductos.Cantidad ELSE 0 END) AS Descarte,
    SUM(CASE WHEN produccion_subproductos.IDInvProduccion = '18' THEN produccion_subproductos.Cantidad ELSE 0 END) AS Impurezas
FROM
    produccion_subproductos
WHERE
    produccion_subproductos.IDProduccionResumen = ?
GROUP BY
    produccion_subproductos.Fecha;");
  $sql->execute($datos);
  return $sql;
}

//* CONSULTAR PRODUCCION SUBPRODUCTOS
function produccionConsultarSubProductosYPreciosXID($datos)
{
  $sql = conexion()->prepare("SELECT 
  produccion_subproductos.IDInvProduccion, 
  produccion_subproductos.IDProduccionResumen, 
  inventario_produccion.DescripcionProducto, 
  SUM(produccion_subproductos.Cantidad) AS CantidadTotal, 
  inventario_produccion.PrecioUnitario, 
  SUM(produccion_subproductos.Cantidad * inventario_produccion.PrecioUnitario) AS Total 
  FROM produccion_subproductos 
  INNER JOIN inventario_produccion ON produccion_subproductos.IDInvProduccion = inventario_produccion.IDInvProduccion 
  WHERE inventario_produccion.IDTipoProducto = 5 and produccion_subproductos.IDProduccionResumen = ? 
  GROUP BY inventario_produccion.IDInvProduccion");
  $sql->execute($datos);
  return $sql;
}

//* REPORTE DE HISTORIAL DE PRODUCCIONES
function reportProduccionHistorial($datos, $IDTipoDesp)
{
  $sql = conexion()->prepare('SELECT
  produccion_resumen.IDProduccionResumen,
  produccion_resumen.FechaProduccion,
  produccion_resumen.FechaCaducidad,
  produccion_resumen.NroLote,
  produccion_resumen.ResponsableProduccion,
  produccion_resumen.EstadoProduccion,
  produccion_resumen.CantidadProducida,
  articulos.CodigoArticulo,
  articulos.DescripcionArticulo,
  inventario_produccion.DescripcionProducto,
  ROUND(SUM(produccion_detalles.CostoUtilizado * produccion_detalles.CantidadUtilizada), 2) AS TotalCostoProduccion,
  ROUND(SUM(produccion_detalles.CostoUtilizado * produccion_detalles.CantidadUtilizada) / produccion_resumen.CantidadProducida, 2) AS CostoXSaco
FROM
  produccion_detalles
  INNER JOIN produccion_resumen ON produccion_resumen.IDProduccionResumen = produccion_detalles.IDProduccionResumen
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  INNER JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
WHERE
  produccion_resumen.FechaProduccion BETWEEN ? AND ? ' . ($IDTipoDesp != '' ? 'AND produccion_resumen.IDArticulo  = ' . $IDTipoDesp : '') . '
GROUP BY
  produccion_resumen.IDProduccionResumen');
  $sql->execute($datos);
  return $sql;
}

//* REPORTE DE HISTORIAL DE PRODUCCIONES EM PROCESO
function reportProduccioneEnProceso()
{
  $sql = conexion()->prepare('SELECT
  produccion_resumen.IDProduccionResumen,
  produccion_resumen.FechaProduccion,
  produccion_resumen.FechaCaducidad,
  produccion_resumen.NroLote,
  produccion_resumen.ResponsableProduccion,
  produccion_resumen.EstadoProduccion,
  produccion_resumen.CantidadProducida,
  articulos.CodigoArticulo,
  articulos.DescripcionArticulo,
  inventario_produccion.DescripcionProducto
FROM
  produccion_detalles
  INNER JOIN produccion_resumen ON produccion_resumen.IDProduccionResumen = produccion_detalles.IDProduccionResumen
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  LEFT JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
WHERE
   produccion_resumen.EstadoProduccion in (2,3) GROUP BY produccion_resumen.IDProduccionResumen');
  $sql->execute();
  return $sql;
}

//* GRAFICA DE PRODUCICON POR ARTICULO
function reportProduccionXArticulos($datos, $IDMedida)
{
  $sql = conexion()->prepare('SELECT
  articulos.DescripcionArticulo,
  inventario_produccion.DescripcionProducto,
  ROUND(SUM(produccion_resumen.CantidadProducida * inventario_produccion.CapacidadEmpaque), 2) AS TotalProducidoKG,
  ROUND(SUM(produccion_resumen.CantidadProducida), 2) AS TotalProducidoUND
FROM
  produccion_resumen
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  INNER JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
WHERE
  produccion_resumen.FechaProduccion BETWEEN ? AND ?
GROUP BY
  produccion_resumen.IDArticulo' . ($IDMedida == 'und' ? ', produccion_resumen.IDEmpaque' : ''));
  $sql->execute($datos);
  return $sql;
}

//* REPORTE DE HISTORIAL DE PRODUCCIONES
function reportProduccionDiaria($datos)
{
  $sql = conexion()->prepare('SELECT
  articulos.DescripcionArticulo,
  inventario_produccion.DescripcionProducto,
  ROUND(SUM(produccion_resumen.CantidadProducida * inventario_produccion.CapacidadEmpaque), 2) AS TotalProducidoKG,
  ROUND(SUM(produccion_resumen.CantidadProducida), 2) AS TotalProducidoUND
FROM
  produccion_resumen
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  INNER JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
WHERE
  produccion_resumen.FechaProduccion = ?
GROUP BY
  produccion_resumen.IDArticulo');
  $sql->execute($datos);
  return $sql;
}

function EliminarSubproductos($datos)
{
  $sql = conexion()->prepare('DELETE FROM produccion_subproductos WHERE Fecha = ? AND IDProduccionResumen = ?');
  $sql->execute($datos);
}

//* ACTUALIZAR COSTOS DE PRODUCCION 
function produccionActualizarCosto($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET PrecioCosto = ?, UltimiaActualiacionInvPlanta = ? WHERE IDProduccionResumen = ?');
  $sql->execute($datos);
}
