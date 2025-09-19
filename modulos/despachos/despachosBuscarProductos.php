<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../inventario/inventarioMain.php';

$buscador = limpiarCadena($_GET['buscador']);
$html = [];
foreach (inventarioBuscarProductos(['%' . $buscador . '%', '%' . $buscador . '%', '%' . $buscador . '%']) as $row) {
  $html[] = [
    'value'   => encriptar($row['IDInvProducto']),
    'label'   => $row['DescripcionProducto'] . ' - ' . $row['DescripcionPresentacion'],
  ];
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);