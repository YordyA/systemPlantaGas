<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../clientes/clientesMain.php';

$buscador = limpiarCadena($_GET['buscador']);
$html = [];
foreach (clientesBuscardor(['%' . $buscador . '%', '%' . $buscador . '%']) as $row) {
  $html[] = [
    'value' => encriptar($row['IDCliente']),
    'label' => $row['RazonSocialCliente']
  ];
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
