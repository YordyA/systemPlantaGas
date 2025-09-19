<?php

//* CONEXIÓN BASE DE DATOS LOCAL
function conexion()
{
  // Datos de conexión a la base de datos
  $host = 'localhost';
  $dbname = 'sistema4_planta_gas';
  $username = 'sistema4_administrador';
  $password = 'sistemas2025*';

  $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  //$conexion = new PDO('mysql:host=localhost;dbname=sistema4_planta_gas', 'root', '');
  $conexion->exec('SET CHARACTER SET utf8');
  return $conexion;
}

function conexionTerritorial()
{
  // Datos de conexión a la base de datos
  $host = 'localhost';
  $dbname = 'sistema4_territorio';
  $username = 'sistema4_administrador';
  $password = 'sistemas2025*';

  $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  //$conexion = new PDO('mysql:host=localhost;dbname=sistema4_territorio', 'root', '');
  $conexion->exec('SET CHARACTER SET utf8');
  return $conexion;
}


// //* CONEXIÓN BASE DE DATOS LOCAL
function conexionAdministrativo()
{
  // Datos de conexión a la base de datos
  $host = 'localhost';
  $dbname = 'sistema4_administrativo';
  $username = 'sistema4_administrador';
  $password = 'sistemas2025*';

  //$conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conexion = new PDO('mysql:host=localhost;dbname=sistema4_administrativo', 'root', '');
  $conexion->exec('SET CHARACTER SET utf8');
  return $conexion;
}
