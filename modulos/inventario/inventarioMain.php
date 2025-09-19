<?php

//* VERIFICAR ALMACEN X ID
function almacenVerificarXID($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  inventario_planta
WHERE
  EstadoInventario = 1
  AND IDInventario = ?');
  $sql->execute($datos);
  return $sql;
}

//* REGISTRAR ALMACEN
function almacenRegistrar($datos)
{
  $conexion = conexion();
  $sql = $conexion->prepare('INSERT INTO
  inventario_planta (
    IDPlanta,
    DescripcionAlmacen,
    EstadoInventario
  )
VALUES
  (?, ?, 1)');
  $sql->execute($datos);
  return $conexion->lastInsertId();
}

//* ACTUALIZAR ALMACEN
function almacenActualizar($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET  DescripcionAlmacen = ?,  UltimaActualicion = ? WHERE  IDInventario = ?');
  $sql->execute($datos);
}

//* ACTUALIZAR ALMACEN ESTADO
function almacenActualizarEstado($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET EstadoInventario = ?, UltimaActualicion = ? WHERE IDInventario = ?');
  $sql->execute($datos);
}

//* LISTAR ALMACENES DE INVENTARIO
function almacenLista($datos)
{
  $sql = conexion()->prepare('SELECT * FROM inventario_planta WHERE  IDPlanta = ? AND EstadoInventario = 1');
  $sql->execute($datos);
  return $sql;
}

//*
function MovimientosDeAlmacenRegistrar($datos)
{
  $conexion = conexion();
  $sql = $conexion->prepare('INSERT INTO inventario_planta_movimientos (IDPlanta, FechaMov,	TipoMov,	IDInvPlanta,	ExistenciaAnterior,	Movimiento,	ExistenciaActual,	ObservacionMov,	ResponsableMov)
VALUES
  (?, ?, ?, ?, ?, ?, (SELECT Cantidad FROM inventario_planta WHERE IDInventario = ?), ?, ?)');
  $sql->execute($datos);
  return $conexion->lastInsertId();
}

//* RESTAR CANTIDAD ALMACEN
function almacenCantidadRestar($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET  Cantidad = Cantidad - ? WHERE  IDInventario = ?');
  $sql->execute($datos);
}

//* SUMAR CANTIDAD ALMACEN
function almacenCantidadSumar($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_planta SET  Cantidad = Cantidad + ? WHERE  IDInventario = ?');
  $sql->execute($datos);
}

# REPORTE DE MOVIMIENTO DE INVENTARIO
function reportInventarioMovimientos($datos, $tipoMov)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  inventario_planta_movimientos
  INNER JOIN inventario_planta ON inventario_planta_movimientos.IDInvPlanta = inventario_planta.IDInventario
WHERE
  inventario_planta_movimientos.FechaMov BETWEEN ? AND ? AND inventario_planta_movimientos.IDPlanta = ? ' . ($tipoMov != '' ? ' AND inventario_planta_movimientos.TipoMov = ' . $tipoMov : ' ORDER BY inventario_planta_movimientos.IDInvPlantaMov ASC'));
  $sql->execute($datos);
  return $sql;
}

# REPORTE DE MOVIMIENTO DE INVENTARIO ENTRE FECHAS
function reportInventarioMovimientosEntreFechas($datos)
{
    $sql = conexion()->prepare("SELECT 
    inventario_planta.*,
            -- Inventario Inicial: Existencia antes de la fecha de inicio
            COALESCE((
                SELECT SUM(CASE 
                            WHEN inventario_planta_movimientos.TipoMov = 1 THEN inventario_planta_movimientos.Movimiento
                            WHEN inventario_planta_movimientos.TipoMov = 2 THEN -inventario_planta_movimientos.Movimiento
                            ELSE 0
                         END)
                FROM inventario_planta_movimientos
                JOIN inventario_planta ON inventario_planta_movimientos.IDInvPlanta = inventario_planta.IDInventario
                WHERE inventario_planta_movimientos.FechaMov < ? AND inventario_planta_movimientos.IDPlanta = ?
            ), 0) AS InventarioInicial,

            -- Entradas dentro del rango de fechas
            COALESCE(SUM(CASE 
                WHEN inventario_planta_movimientos.TipoMov = 1 
                     AND inventario_planta_movimientos.FechaMov BETWEEN ? AND ? AND inventario_planta_movimientos.IDPlanta = ?
                THEN inventario_planta_movimientos.Movimiento
                ELSE 0
            END), 0) AS Entradas,

            -- Salidas dentro del rango de fechas
            COALESCE(SUM(CASE 
                WHEN inventario_planta_movimientos.TipoMov = 2 
                     AND inventario_planta_movimientos.FechaMov BETWEEN ? AND ? AND inventario_planta_movimientos.IDPlanta = ?
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
                JOIN inventario_planta ON inventario_planta_movimientos.IDInvPlanta = inventario_planta.IDInventario
                WHERE inventario_planta_movimientos.FechaMov < ? AND inventario_planta_movimientos.IDPlanta = ?
            ), 0) 
            + 
            COALESCE(SUM(CASE 
                WHEN inventario_planta_movimientos.TipoMov = 1 
                     AND inventario_planta_movimientos.FechaMov BETWEEN ? AND ? AND inventario_planta_movimientos.IDPlanta = ?
                THEN inventario_planta_movimientos.Movimiento
                ELSE 0
            END), 0) 
            - 
            COALESCE(SUM(CASE 
                WHEN inventario_planta_movimientos.TipoMov = 2 
                     AND inventario_planta_movimientos.FechaMov BETWEEN ? AND ? AND inventario_planta_movimientos.IDPlanta = ?
                THEN inventario_planta_movimientos.Movimiento
                ELSE 0
            END), 0)) AS InventarioFinal

        FROM inventario_planta_movimientos
        INNER JOIN inventario_planta ON inventario_planta_movimientos.IDInvPlanta = inventario_planta.IDInventario
        WHERE inventario_planta_movimientos.FechaMov BETWEEN ? AND ? AND inventario_planta_movimientos.IDPlanta = ?
        GROUP BY inventario_planta.IDInventario");
    
    $sql->execute($datos);
    return $sql;
}