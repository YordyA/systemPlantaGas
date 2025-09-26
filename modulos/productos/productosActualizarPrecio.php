<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once 'productosMain.php';

$idArticulo = LimpiarCadena($_GET['id']);
$precio = LimpiarCadena($_POST['precioVenta']);
preciosActualizar([$precio, $idArticulo]);
$alerta = [
  "alerta" => "actualizacion",
  "titulo" => "¡Precio de Venta Actualizado!",
  "texto" => "El Precio de Venta de Actualizo Correctamente",
  "tipo" => "success"
];
echo json_encode($alerta);
