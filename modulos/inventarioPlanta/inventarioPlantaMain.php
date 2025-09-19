<?php

//* VERIFICAR PRODUCCION EN INVENTARIO PLANTA POR ID
function inventarioPlantaVerificarXID($datos)
{
  $sql = conexion()->prepare('SELECT
  inventario_planta.IDInvPlanta,
  inventario_planta.Existencia,
  inventario_planta.PrecioCosto,
  inventario_planta.PrecioVenta,
  articulos.CodigoArticulo,
  articulos.DescripcionArticulo,
  articulos_alicuotas.DescripcionAlicuota,
  articulos_alicuotas.ValorAlicuota,
  inventario_produccion.DescripcionProducto AS DescripcionEmpaque,
  produccion_resumen.NroLote
FROM
  inventario_planta
  INNER JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  INNER JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  INNER JOIN articulos_alicuotas ON articulos.IDAlicuota = articulos_alicuotas.IDAlicuota
WHERE
  inventario_planta.EstadoInvPlanta = 1
  AND inventario_planta.IDInvPlanta = ?');
  $sql->execute($datos);
  return $sql;
}

//* VERIFICAR PRODUCCION EN INVENTARIO PLANTA POR ID DE PRODUCCION RESUMEN 
function inventarioPlantaVerificarXIDPRODUCCIONRESUMEN($datos)
{
  $sql = conexion()->prepare('SELECT
  inventario_planta.IDInvPlanta,
  inventario_planta.Existencia,
  inventario_planta.PrecioCosto,
  inventario_planta.PrecioVenta,
  articulos.CodigoArticulo,
  articulos.DescripcionArticulo,
  articulos_alicuotas.DescripcionAlicuota,
  articulos_alicuotas.ValorAlicuota,
  inventario_produccion.DescripcionProducto AS DescripcionEmpaque,
  produccion_resumen.NroLote
FROM
  inventario_planta
  INNER JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  LEFT JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  INNER JOIN articulos_alicuotas ON articulos.IDAlicuota = articulos_alicuotas.IDAlicuota
WHERE
  inventario_planta.EstadoInvPlanta = 1
  AND produccion_resumen.IDProduccionResumen = ?');
  $sql->execute($datos);
  return $sql;
}

//* REGISTRAR PRODUCTOS DE PLANTA
function inventarioPlantaRegistrar($datos)
{
  $conexion = conexion();
  $sql = $conexion->prepare('INSERT INTO
  inventario_planta (
    IDProduccionResumen,
    IDArticulo,
    FechaExpe,
    Existencia,
    EstadoInvPlanta
  )
VALUES
  (?, ?, ?, ?, 1)');
  $sql->execute($datos);
  return $conexion->lastInsertId();
}

//* LISTA DE PRODUCTOS
function inventarioPlantaLista()
{
  return conexion()->query('SELECT
  inventario_planta.IDInvPlanta,
  inventario_planta.Existencia,
  inventario_planta.PrecioCosto,
  inventario_planta.PrecioVenta,
  inventario_planta.EstadoInvPlanta,
  articulos.IDArticulo,
  articulos.CodigoArticulo,
  articulos.DescripcionArticulo,
  inventario_produccion.DescripcionProducto AS DescripcionEmpaque,
  produccion_resumen.NroLote
FROM
  inventario_planta
  INNER JOIN articulos ON inventario_planta.IDArticulo = articulos.IDArticulo
  INNER JOIN articulos_alicuotas ON articulos.IDAlicuota = articulos_alicuotas.IDAlicuota
  INNER JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  LEFT JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
WHERE
  EstadoInvPlanta = 1
  AND inventario_planta.Existencia > 0');
}

//* LISTA DE PRODUCTOS
function inventarioPlantaPorProduccion($datos)
{
  $sql = conexion()->prepare('SELECT
* 
FROM
  inventario_planta
  INNER JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  INNER JOIN articulos_alicuotas ON articulos.IDAlicuota = articulos_alicuotas.IDAlicuota
  LEFT JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
WHERE
  EstadoInvPlanta = 1 AND  inventario_planta.IDProduccionResumen = ? ');
  $sql->execute($datos);
  return $sql;
}

function inventarioPlantaPorProduccionIDyArticulo($datos)
{
  $sql = conexion()->prepare('SELECT
* 
FROM
  inventario_planta
  INNER JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  INNER JOIN articulos_alicuotas ON articulos.IDAlicuota = articulos_alicuotas.IDAlicuota
  LEFT JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
WHERE
  EstadoInvPlanta = 1 AND  inventario_planta.IDProduccionResumen = ? AND inventario_planta.IDArticulo = ?');
  $sql->execute($datos);
  return $sql;
}



//* ACTUALIZAR PRODUCTO
function inventarioPlantaActualizarPrecio($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET PrecioVenta = ?, UltimiaActualiacionInvPlanta = ? WHERE IDInvPlanta = ?');
  $sql->execute($datos);
}

//* RETIRAR EXISTENCIA
function inventarioPlantaRetirarExistencia($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET Existencia = Existencia - ? WHERE IDInvPlanta = ?');
  $sql->execute($datos);
}

//* RELLENAR EXISTENCIA
function inventarioPlantaRellenarExistencia($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET Existencia = Existencia + ? WHERE IDInvPlanta = ?');
  $sql->execute($datos);
}


//* ACTUALIZAR ESTADO
function inventarioPlantaActualizarEstado($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET EstadoInvPlanta = ?, UltimiaActualiacionInvPlanta = ? WHERE IDInvPlanta = ?');
  $sql->execute($datos);
}

//* REGISTRAR MOVIMIENTO DE INVENTARIO DE PLANTA
function inventarioPlantaRegistrarMovimiento($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  inventario_planta_movimientos (
    FechaMov,
    TipoMov,
    IDInvPlanta,
    ExistenciaAnterior,
    Movimiento,
    ExistenciaActual,
    ObservacionMov,
    ResponsableMov
  ) VALUES (?, ?, ?, ?, ?,(SELECT Cantidad FROM inventario_planta WHERE IDInventario = ?), ?, ?)');
  $sql->execute($datos);
}

//* REGISTRAR EXISTENCIA FINAL
function inventarioPlantaRegistrarExistencia($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  inventario_planta_existencia (
    FecahRegExistenciaPlanta,
    IDInvPlanta,
    ExistenciaSistema,
    CostoU
  )
VALUES
  (?, ?, ?, ?)');
  $sql->execute($datos);
}

//* MOVIMIENTO DE INVENTARIO DE PLANTA
function reportInventarioPlantaMovimientos($datos, $tipoMov)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  inventario_planta_movimientos
  INNER JOIN inventario_planta ON inventario_planta_movimientos.IDInvPlanta = inventario_planta.IDInvPlanta
  INNER JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  INNER JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
WHERE
  inventario_planta_movimientos.FechaMov BETWEEN ? AND ?' . ($tipoMov != '' ? ' AND inventario_planta_movimientos.TipoMov = ' . $tipoMov : ' ORDER BY inventario_planta_movimientos.IDInvPlantaMov ASC'));
  $sql->execute($datos);
  return $sql;
}

function reportInventarioPlantaMovimientosPorArticulos($datos)
{
  $sql = conexion()->prepare("SELECT 
    articulos.IDArticulo,
    articulos.DescripcionArticulo,
    inventario_produccion.CodigoProducto,

    -- Inventario Inicial: Existencia antes de la fecha de inicio
    COALESCE((
        SELECT SUM(CASE 
                    WHEN inventario_planta_movimientos.TipoMov = 1 THEN inventario_planta_movimientos.Movimiento
                    WHEN inventario_planta_movimientos.TipoMov = 2 THEN -inventario_planta_movimientos.Movimiento
                    ELSE 0
                 END)
        FROM inventario_planta_movimientos
        JOIN inventario_planta ON inventario_planta_movimientos.IDInvPlanta = inventario_planta.IDInvPlanta
        JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
        WHERE produccion_resumen.IDArticulo = articulos.IDArticulo
              AND inventario_planta_movimientos.FechaMov < ?
    ), 0) AS InventarioInicial,

    -- Entradas dentro del rango de fechas
    COALESCE(SUM(CASE 
        WHEN inventario_planta_movimientos.TipoMov = 1 
             AND inventario_planta_movimientos.FechaMov BETWEEN ? AND ?
        THEN inventario_planta_movimientos.Movimiento
        ELSE 0
    END), 0) AS Entradas,

    -- Salidas dentro del rango de fechas
    COALESCE(SUM(CASE 
        WHEN inventario_planta_movimientos.TipoMov = 2 
             AND inventario_planta_movimientos.FechaMov BETWEEN ? AND ?
        THEN inventario_planta_movimientos.Movimiento
        ELSE 0
    END), 0) AS Salidas,

    -- Inventario Final: Inventario Inicial + Entradas - Salidas
    (COALESCE((
        SELECT SUM(CASE 
                    WHEN inventario_planta_movimientos.TipoMov = 1 THEN inventario_planta_movimientos.Movimiento
                    WHEN inventario_planta_movimientos.TipoMov = 2 THEN -inventario_planta_movimientos.Movimiento
                    ELSE 0
                 END)
        FROM inventario_planta_movimientos
        JOIN inventario_planta ON inventario_planta_movimientos.IDInvPlanta = inventario_planta.IDInvPlanta
        JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
        WHERE produccion_resumen.IDArticulo = articulos.IDArticulo
              AND inventario_planta_movimientos.FechaMov < ?
    ), 0) 
    + 
    COALESCE(SUM(CASE 
        WHEN inventario_planta_movimientos.TipoMov = 1 
             AND inventario_planta_movimientos.FechaMov BETWEEN ? AND ?
        THEN inventario_planta_movimientos.Movimiento
        ELSE 0
    END), 0) 
    - 
    COALESCE(SUM(CASE 
        WHEN inventario_planta_movimientos.TipoMov = 2 
             AND inventario_planta_movimientos.FechaMov BETWEEN ? AND ?
        THEN inventario_planta_movimientos.Movimiento
        ELSE 0
    END), 0)) AS InventarioFinal

FROM inventario_planta_movimientos
JOIN inventario_planta ON inventario_planta_movimientos.IDInvPlanta = inventario_planta.IDInvPlanta
JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
JOIN inventario_produccion on produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
WHERE inventario_planta_movimientos.FechaMov BETWEEN ? AND ?
GROUP BY articulos.IDArticulo, articulos.DescripcionArticulo, inventario_produccion.CodigoProducto;");
  $sql->execute($datos);
  return $sql;
}
