<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'inventarioPlantaMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);

$html = '';
foreach (reportInventarioPlantaMovimientosPorArticulos([$del, $del, $hasta, $del, $hasta, $del, $del, $hasta, $del, $hasta, $del, $hasta]) as $row) {
  $html .= '<tr>';
  $html .= '<td>' . htmlspecialchars($row['CodigoProducto']) . '</td>'; // Código Artículo
  $html .= '<td>' . htmlspecialchars($row['DescripcionArticulo']) . '</td>'; // Descripción Artículo
  $html .= '<td>' . htmlspecialchars($row['InventarioInicial']) . '</td>'; // Inicial
  $html .= '<td>' . htmlspecialchars($row['Entradas']) . '</td>'; // Entradas
  $html .= '<td>' . htmlspecialchars($row['Salidas']) . '</td>'; // Salidas
  $html .= '<td>' . htmlspecialchars($row['InventarioFinal']) . '</td>'; // Final
  $html .= '</tr>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
