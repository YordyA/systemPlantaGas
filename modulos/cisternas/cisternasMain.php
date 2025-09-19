<?php

//* VERIFICAR CISTERNA X ID
function cisternasVerificarXID($datos)
{
  $sql = conexion()->prepare('SELECT * FROM vehiculos_cisternas WHERE EstadoCisterna = 1 AND IDCisterna = ?');
  $sql->execute($datos);
  return $sql;
}

//* REGISTRAR CISTERNA
function cisternasRegistrar($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  vehiculos_cisternas (TipoCisterna, EmpresaC, Modelo, Capacidad, EstadoCisterna) VALUES (?, ?, ?, ?, 1)');
  $sql->execute($datos);
  return $sql;
}

//* ACTUALIZAR CISTERNA
function cisternasActualizar($datos)
{
  $sql = conexion()->prepare('UPDATE vehiculos_cisternas SET TipoCisterna = ?, EmpresaC = ?, Modelo = ?, Capacidad = ?, UltimaActualizacionCisterna = ? WHERE IDCisterna = ?');
  $sql->execute($datos);
}

//* ACTUALIZAR ESTADO DEL CISTERNAS
function cisternasActualizarEstado($datos)
{
  $sql = conexion()->prepare('UPDATE vehiculos_cisternas SET EstadoCisterna = ?, UltimaActualizacionCisterna = ? WHERE IDCisterna = ?');
  $sql->execute($datos);
}

//* LISTA DE CISTERNAS
function cisternasLista()
{
  return conexion()->query('SELECT * FROM vehiculos_cisternas WHERE EstadoCisterna = 1');
}

//* BUSCAR CISTERNAS
function cisternasBuscador($datos)
{
  $sql = conexion()->prepare('SELECT * FROM vehiculos_cisternas WHERE EstadoCisterna = 1 AND (TipoCisterna LIKE ? OR EmpresaC LIKE ?)');
  $sql->execute($datos);
  return $sql;
}
