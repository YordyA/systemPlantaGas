<?php

function ProdutosRegistrar($datos)
{
  $conexion = conexion();
  $sql = $conexion->prepare('INSERT INTO
  productos (
    IDTipoProducto,	
    DescripcionProducto,	
    CapacipadCilindro,	
    PrecioVenta,	
    EstadoProducto	
  )
VALUES
  (?, ?, ?, ?, 1)');
  $sql->execute($datos);
  return $conexion->lastInsertId();
}

//* VERIFICAR ALMACEN X ID
function ProductosListaID($datos)
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


//* LISTA DE CISTERNAS
function ProductosLista()
{
  return conexion()->query('SELECT * FROM productos INNER JOIN tipo_productos on productos.IDTipoProducto = tipo_productos.IDTipo  WHERE EstadoProducto = 1');
}

function TiposProductos()
{
  return conexion()->query('SELECT * FROM tipo_productos WHERE Estado = 1');
}

function TiposCilindros()
{
  return conexion()->query('SELECT * FROM cilindros WHERE EstadoCilindro = 1');
}

