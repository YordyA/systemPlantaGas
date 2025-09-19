<?php
# VERIFICAR CLIENTE POR CEDULA
function verificarClienteDucumentoFiscal($dato)
{
  $sql = conexion()->prepare('SELECT * FROM clientes WHERE RifCliente = ? AND Estatus = 0');
  $sql->execute([$dato]);
  return $sql;
}

# REGISTRAR CLIENTE
function registrarCliente($datos)
{
  $sql = conexion()->prepare('INSERT INTO clientes (RifCliente, NombreCliente) VALUES (?,?)');
  return $sql->execute($datos);
}

# VERIFICAR EL FORMATO DEL CLIENTE
function verificarFormatoCliente($cadena)
{
  // Obtener el primer carácter de la cadena
  $primerCaracter = strtoupper(substr($cadena, 0, 1));
  // Validar si el primer carácter está en la lista permitida
  if (in_array($primerCaracter, ["V", "E", "J", "G", "P", "C"])) {
    return $primerCaracter; // Devolver el primer carácter si es válido
  } else {
    return false; // Retornar falso si no es válido
  }
}
