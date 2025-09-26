<?php

//* REGISTRAR TASA EN BASE DE DATO
function tasaReferenciaVerificarXFecha($datos)
{
  $sql = conexion()->prepare('SELECT * FROM historial_tasa_bcv WHERE FechaTasa = ?');
  $sql->execute($datos);
  return $sql;
}

//* REGISTRAR TASA EN BASE DE DATO
function tasaReferenciaRegistrar($datos)
{
  $sql = conexion()->prepare('INSERT INTO historial_tasa_bcv (FechaTasa, TasaRefUSD) VALUES (?, ?)');
  $sql->execute($datos);
}
