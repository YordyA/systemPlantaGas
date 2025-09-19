<?php
require_once '../dependencias.php';
require_once '../sessionStart.php';

$html = '';
if (isset($_SESSION['conteoFisico']['datelle']) && count($_SESSION['conteoFisico']['datelle']) > 0) {
  foreach ($_SESSION['conteoFisico']['datelle'] as $indice => $row) {
    $html .= '<tr>';
    $html .= '<td>' . $row['codigo'] . '</td>';
    $html .= '<td>' . $row['descripcion'] . '</td>';
    $html .= '<td>' . number_format($row['cantSistema'], 6, ',', '.') . '</td>';
    $html .= '<td>
                <button class="btn btn-outline-danger btnCantFisica" value="' . encriptar($indice) . '">
                  ' . number_format($row['cantFisica'], 6, ',', '.') . '
                </button>
              </td>';
    $html .= '<td>' . number_format($row['diferencia'], 6, ',', '.') . '</td>';
    $html .= '</tr>';
  }
} else {
  $html = '<tr><td colspan="5" class="text-center">No Hay Información</td></tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
