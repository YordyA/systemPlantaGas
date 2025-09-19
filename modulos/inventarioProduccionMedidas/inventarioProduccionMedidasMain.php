<?php

//* CONSULTAR LISTA DE UNIDADES DE MEDIADAS
function inventarioProduccionMedidasLista()
{
  return conexion()->query('SELECT * FROM inventario_produccion_medidas WHERE EstadoUnidadMedida = 1');
}