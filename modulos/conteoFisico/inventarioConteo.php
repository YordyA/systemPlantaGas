<?php
//! ULTIMO REGISTRO DE CONTEO FISICO
function N_Conteo($datos)
{
  $sentencia = conexion()->prepare("SELECT
  IDConteo
FROM
  inventarioconteo
WHERE
  IDSucursal = ?
ORDER BY
  IDConteo DESC
LIMIT
  1");
  $sentencia->execute($datos);
  $fila = $sentencia->fetch();
  if ($fila === FALSE) return 1;
  return $fila["IDConteo"] + 1;
}


//! VERIFICAR SI EXISTE CONTEO INICIAL
function VerificarConteoInicial($id)
{
  $producto = conexion()->prepare('SELECT
  *
FROM
  inventarioconteo
WHERE
  IDSucursal = ?
  AND Fecha = ?
  and TipoConteo = 0');
  $producto->execute($id);
  return $producto;
}

//! CONSULTAR ARTICULOS PARA CONTEO FISICO
function ConsultarArticulosConteoFisico($id)
{
  $producto = conexion()->prepare('SELECT
  *
FROM
  inventario_planta
WHERE
 IDPlanta = ? AND
  EstadoInventario = 1');
  $producto->execute($id);
  return   $producto->fetchAll(PDO::FETCH_ASSOC);
}

//! ACTUALIZAR EXISTENCIA CONTEO FISICO
function ActualizarExistencia($datos)
{
  $producto = conexion()->prepare('UPDATE inventario_planta
SET
  Cantidad = ?
WHERE
  IDInventario = ?
  AND IDPlanta = ?');
  return $producto->execute($datos);
}

//! REGISTRAR CONTEO FISICO
function RegistrarConteoFisico($datos)
{
  $producto = conexion()->prepare('INSERT INTO
  inventarioconteo (
    IDSucursal,
    IDConteo,
    Fecha,
    IDArticulo,
    ExistenciaSistema,
    ExistenciaFisica,
    Diferencia,
    Responsable,
    TipoConteo
  )
VALUES
  (?, ?, ?, ?, ?, ?, ?, ?, ?)');
  return $producto->execute($datos);
}





/////////////////////////////////////////////////////////////////////////////////

//! VERIFICAR PRODUCTO POR CODIGO
function verificarProductoCodigoVenta($codigo)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  productos
  INNER JOIN tipo_productos ON productos.IDTipoProducto = tipo_productos.IDTipo
WHERE
  productos.EstadoProducto = 1
  AND productos.Codigo = ?');
  $sql->execute([$codigo]);
  return $sql;
}

function verificarProductoIDVenta($codigo)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  productos
  INNER JOIN tipo_productos ON productos.IDTipoProducto = tipo_productos.IDTipo
WHERE
  productos.EstadoProducto = 1
  AND productos.IDProducto = ?');
  $sql->execute([$codigo]);
  return $sql;
}
/////////////////////////////////////////////////////////////////////////////////









//! ACTUALIZAR EXISTENCIA RESTANDO SALIDAD POR VENTA, ID
function ActualizarExistenciaRestar($datos)
{
  $sql = conexion()->prepare('UPDATE existenciaporsucursal
SET
  ExistenciaArticulo = ExistenciaArticulo - ?
WHERE
  IDArticulo = ?
  AND IDSucursal = ?');
  return $sql->execute($datos);
}

//! ACTUALIZAR EXISTENCIA SUMANDO ANULACION DE VENTA, ID
function ActualizarExistenciaSumar($datos)
{
  $sql = conexion()->prepare('UPDATE existenciaporsucursal
SET
  ExistenciaArticulo = ExistenciaArticulo + ?
WHERE
  IDArticulo = ?
  AND IDSucursal = ?');
  return $sql->execute($datos);
}

//! ACTUALIZAR EXISTENCIA Y PRECIO DE COSTO DE COCHINO EN CANAL
function inventarioRellenarExistenciaYCosto($datos)
{
  $sql = conexion()->prepare('UPDATE existenciaporsucursal
SET
  ExistenciaArticulo = ExistenciaArticulo + ?,
  CostoArticulo = ?
WHERE
  IDArticulo = ?
  AND IDSucursal = ?');
  return $sql->execute($datos);
}

//! ACTUALIZAR EXISTENCIA, PRECIO DE COSTO DE LOS PRODUCTOS POR SUCURSAL
function actualizarArticuloCostoExistencia($datos)
{
  $sql = conexion()->prepare('UPDATE existenciaporsucursal
SET
  CostoArticulo = ?,
  ExistenciaArticulo = ExistenciaArticulo + ?
WHERE
  IDArticulo = ?
  AND IDSucursal = ?');
  $sql->execute($datos);
  return $sql;
}

//! RELLENAR EXISTENCIA 
function inventarioRellenarExistencia($datos)
{
  $sql = conexion()->prepare('UPDATE existenciaporsucursal
SET
  ExistenciaArticulo = ExistenciaArticulo + ?
WHERE
  IDArticulo = ?
  AND IDSucursal = ?');
  return $sql->execute($datos);
}

//! RELLENAR EXISTENCIA Y ACTUALIZAR PRECIO COSTO
function inventarioRellenarExistenciaActualizaCosto($datos)
{
  $sql = conexion()->prepare('UPDATE existenciaporsucursal
SET
  ExistenciaArticulo = ExistenciaArticulo + ?,
  CostoArticulo = ?
WHERE
  IDArticulo = ?
  AND IDSucursal = ?');
  return $sql->execute($datos);
}

//!
function invnetarioRetirarExistencia($datos)
{
  $sql = conexion()->prepare('UPDATE existenciaporsucursal
SET
  ExistenciaArticulo = ExistenciaArticulo - ?
WHERE
  IDArticulo = ?
  AND IDSucursal = ?');
  return $sql->execute($datos);
}

//! CONTEO FISICO
function ConteoFisico($id)
{
  $producto = conexion()->prepare('SELECT * FROM  articulosdeinventario
  INNER JOIN  inventarioconteo ON articulosdeinventario.IDArticulo= inventarioconteo.IDArticulo
  WHERE  inventarioconteo.IDConteo= ? AND inventarioconteo.IDSucursal = ?');
  $producto->execute($id);
  return   $producto->fetchAll(PDO::FETCH_ASSOC);
}
