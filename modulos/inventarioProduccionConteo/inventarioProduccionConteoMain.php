<?php

//* GENERAR NRO DE CONTEO
function inventarioProduccionConteoGenerarNro()
{
  $sql = conexion()->query('SELECT NroConteo FROM inventario_produccion_conteo GROUP BY NroConteo ORDER BY NroConteo DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
  if ($sql === FALSE) return 1;
  return $sql['NroConteo'] + 1;
}

//* REGISTRAR CONTEO
function inventarioProduccionConteoRegistrar($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  inventario_produccion_conteo (
    FechaCierreConteo,
    NroConteo,
    IDInvProduccion,
    CantSistema,
    CantFisica,
    Diferencia,
    ResponsableConteo,
    EstadoConteo
  )
VALUES
  (?, ?, ?, ?, ?, ?, ?, 2)');
  $sql->execute($datos);
}

//* ACTUALIZAR ESTADO DEL CONTEO
function inventarioProduccionConteoActualizarEstado($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_produccion_conteo SET EstadoConteo = ?, UltimaActualiacionConteo = ? WHERE NroConteo = ?');
  $sql->execute($datos);
}

//* CONSULTAR CONTEO
function inventarioProduccionConteoConsultar($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  inventario_produccion_conteo
  INNER JOIN inventario_produccion ON inventario_produccion_conteo.IDInvProduccion = inventario_produccion.IDInvProduccion
  INNER JOIN inventario_produccion_tipos_productos ON inventario_produccion.IDTipoProducto = inventario_produccion_tipos_productos.IDTipoProducto
WHERE
  NroConteo = ?');
  $sql->execute($datos);
  return $sql;
}

//* ACTUALIZAR ESTADO DEL CONTEO
function inventarioProduccionConteoActualizarEstadoXFecha($datos)
{
  $sql = conexion()->prepare('UPDATE inventario_produccion_conteo SET EstadoConteo = 1 WHERE EstadoConteo = 2 AND YEAR (FechaCierreConteo) = ? AND MONTH (FechaCierreConteo) = ?');
  $sql->execute($datos);
  return $sql;
}

//* REPORTE DE CONTEOS REALIZADOS
function reportInventarioProduccionConteoRealizados($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  inventario_produccion_conteo
WHERE
  inventario_produccion_conteo.FechaCierreConteo BETWEEN ? AND ?
GROUP BY
  NroConteo');
  $sql->execute($datos);
  return $sql;
}

//* CONSULTAR CONTEO FISICO POR MES
function inventarioProduccionConteoConsultarXMes($datos)
{
  $sql = conexion()->prepare('SELECT * FROM inventario_produccion_conteo WHERE YEAR(FechaCierreConteo) = ? AND MONTH(FechaCierreConteo) = ?');
  $sql->execute($datos);
  return $sql;
}
