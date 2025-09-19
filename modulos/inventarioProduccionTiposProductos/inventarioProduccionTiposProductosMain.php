<?php

//* CONSULTAR LISTA DE TIPOS DE PRODUCTOS
function inventarioProduccionTiposProductosLista()
{
  return conexion()->query('SELECT * FROM inventario_produccion_tipos_productos WHERE EstadoTipoProducto = 1');
}