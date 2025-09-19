<?php

//* VERIFICAR USUARIO POR ID
function usuariosVerificarXID($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  usuarios
  INNER JOIN usuarios_privilegios ON usuarios.IDPrivilegio = usuarios_privilegios.IDPrivilegio
WHERE
  EstadoUsuario = 1
  AND IDUsuario = ?');
  $sql->execute($datos);
  return $sql;
}

//* VERIFICAR USUARIO POR USUARIO
function usuariosVerificarXUSUARIO($datos)
{
  $sql = conexion()->prepare('SELECT
  *
FROM
  usuarios
  INNER JOIN usuarios_privilegios ON usuarios.IDPrivilegio = usuarios_privilegios.IDPrivilegio
  INNER JOIN plantas ON usuarios.IDEmpresa = plantas.IDPlanta
WHERE
  EstadoUsuario = 1
  AND Usuario = ?');
  $sql->execute($datos);
  return $sql;
}

//* LISTA DE PRIVILEGIOS
function usuariosListaPrivilegios()
{
  return conexion()->query('SELECT * FROM usuarios_privilegios WHERE EstadoPrivilegio = 1 ');
}

//* REGISTRAR USUARIO
function usuariosRegistrar($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  usuarios (
    NombreUsuario,
    Usuario,
    Clave,
    IDPrivilegio,
    EstadoUsuario
  ) VALUE (?, ?, ?, ?, 1)');
  $sql->execute($datos);
  return $sql;
}

//* LISTA DE USUARIOS 
function usuariosLista()
{
  return conexion()->query('SELECT
  *
FROM
  usuarios
  INNER JOIN usuarios_privilegios ON usuarios.IDPrivilegio = usuarios_privilegios.IDPrivilegio
WHERE
  EstadoUsuario = 1');
}

//* ACTUALIZAR USUARIO
function usuariosActualizar($datos)
{
  $sql = conexion()->prepare('UPDATE usuarios
SET
  NombreUsuario = ?,
  Usuario = ?,
  Clave = ?,
  IDPrivilegio = ?,
  UltimaActualizacionUsuario = ?
WHERE
  IDUsuario = ?');
  $sql->execute($datos);
}

//* ACTUALIZAR ESTADO
function usuariosActualizarEstado($datos)
{
  $sql = conexion()->prepare('UPDATE usuarios SET EstadoUsuario = ?, UltimaActualizacionUsuario = ? WHERE IDUsuario = ?');
  $sql->execute($datos);
}