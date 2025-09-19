<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'produccionMain.php';

$IDProduccionResumen = desencriptar($_GET['id']);

$totalCostoProduccion = 0;
$totalProductosUtilizado = 0;
$html = '';
foreach (produccionConsultarXID([$IDProduccionResumen]) as $row) {
  $totalProductosUtilizado += $row['CantidadUtilizada'];
  $totalCostoProduccion += round($row['CantidadUtilizada'] * $row['CostoUtilizado'], 3);

  $html .= '<tr>';
  $html .= '<td>' . $row['DescripcionTipoProducto'] . '</td>';
  $html .= '<td>' . $row['CodigoProducto'] . '</td>';
  $html .= '<td>' . $row['DescripcionProducto'] . '</td>';
  $html .= '<td>' . number_format($row['CostoUtilizado'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['CantidadUtilizada'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['CantidadUtilizada'] * $row['CostoUtilizado'], 2, ',', '.') . '</td>';
  $html .= '</tr>';
}

$html .= '<tr>';
$html .= '<td colspan="4" class="text-center text-bold">T O T A L E S</td>';
$html .= '<td class="text-bold">' . number_format($totalProductosUtilizado, 2) . '</td>';
$html .= '<td class="text-bold">' . number_format($totalCostoProduccion, 2) . '</td>';
$html .= '</tr>';

echo json_encode($html, JSON_UNESCAPED_UNICODE);