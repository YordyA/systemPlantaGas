<?php
require_once '../main.php';
require_once '../sessionStart.php';

$html = '';
foreach ($_SESSION['facturaManual']['detalle'] as $indice => $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['codigo'] . '</td>';
  $html .= '<td>' . $row['descripcion'] . '</td>';
  $html .= '<td>' . $row['cantidad'] . '</td>';
  $html .= '<td>
              <button class="btn btn-outline-danger text-bold btnPrecio" value="' . Encriptar($indice) . '">
                ' . number_format($row['precio'], 2, ',', '.') . '
              </button>
            </td>';
  $html .= '<td>' . number_format($row['cantidad'] * $row['precio'], 2, ',', '.') . '</td>';
  $html .= '</tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
