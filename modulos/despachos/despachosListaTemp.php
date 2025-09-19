<?php
require_once '../sessionStart.php';
require_once '../dependencias.php';

$html = [];
$html['totalDesp'] = 0;
$html['tablaTempInfo'] = '';

if (isset($_SESSION['despacho']['detalle']) && count($_SESSION['despacho']['detalle']) > 0) {
  $montoTotal = 0;
  foreach ($_SESSION['despacho']['detalle'] as $row) {
    $montoTotal += round($row['cantidad'] * $row['precioVenta'], 2);
    $html['tablaTempInfo'] .= '<tr>';
    $html['tablaTempInfo'] .= '<td>' . $row['codigo'] . '</td>';
    $html['tablaTempInfo'] .= '<td>' . $row['descripcion'] . ' ' . $row['presentacion'] . '</td>';
    $html['tablaTempInfo'] .= '<td>' . number_format($row['existencia'], 2, ',', '.') . '</td>';
    $html['tablaTempInfo'] .= '<td>
                                <button class="btn btn-outline-danger btnCant" value="' . encriptar($row['id']) . '">
                                  ' . number_format($row['cantidad'], 2, ',', '.') . '
                                </button>
                              </td>';
    $html['tablaTempInfo'] .= '<td>' . number_format(($row['precioVenta'] * $row['valorAlicuota']) + $row['precioVenta'], 2, ',', '.') . '</td>';
    $html['tablaTempInfo'] .= '<td>' . number_format($row['cantidad'] * $row['precioVenta'], 2, ',', '.') . '</td>';
    $html['tablaTempInfo'] .= '<td>
                                  <button class="btn btn-lg btnEliminar" value="' . encriptar($row['id']) . '">
                                    <i class="lni lni-cross-circle"></i>
                                  </button>
                                </td>';
    $html['tablaTempInfo'] .= '</tr>';
  }
  $html['totalDesp'] = '<b>' . number_format($montoTotal, 2, ',', '.') . '</b>';
} else {
  $html['tablaTempInfo'] = '<tr><td colspan="7" class="text-center">No Hay Informacion</td></tr>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
