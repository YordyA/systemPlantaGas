<?php

//* VERIFICAR CLIENTE X ID
function clientesVerificarXID($datos)
{
  $sql = conexion()->prepare('SELECT * FROM clientes WHERE EstadoCliente = 1 AND IDCliente = ?');
  $sql->execute($datos);
  return $sql;
}

//* VERIFICAR CLIENTE X RIF / CEDULA
function clientesVerificarXRIFCEDULA($datos)
{
  $sql = conexion()->prepare('SELECT * FROM clientes WHERE EstadoCliente = 1 AND RifCedula = ?');
  $sql->execute($datos);
  return $sql;
}

//* REGISTRAR CLIENTE
function clientesRegistrar($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  clientes (
    RifCedula,
    RazonSocial,
    DomicilioFiscal,
    EstadoCliente
  )
VALUES
  (?, ?, ?, 1)');
  $sql->execute($datos);
  return $sql;
}

//* ACTUALIZAR CLIENTE
function clientesActualizar($datos)
{
  $sql = conexion()->prepare('UPDATE clientes SET RifCedula = ?, RazonSocial = ?, DomicilioFiscal = ?, TipoProductor = ?, UltimaActualizacionCliente = ? WHERE IDCliente = ?');
  $sql->execute($datos);
}

//* ACTUALIZAR ESTADO DEL CLIENTE
function clientesActualizarEstado($datos)
{
  $sql = conexion()->prepare('UPDATE clientes SET EstadoCliente = ?, UltimaActualizacionCliente = ? WHERE IDCliente = ?');
  $sql->execute($datos);
}

//* LISTA DE CLIENTES
function clientesLista()
{
  return conexion()->query('SELECT * FROM clientes WHERE EstadoCliente = 1');
}

//* BUSCAR CLIENTES
function clientesBuscador($datos)
{
  $sql = conexion()->prepare('SELECT * FROM clientes WHERE EstadoCliente = 1 AND (RifCedula LIKE ? OR RazonSocial LIKE ?)');
  $sql->execute($datos);
  return $sql;
}
