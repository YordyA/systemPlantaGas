<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'productosMain.php';

$html = '';

foreach (ProductosLista() as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['Codigo'] . '</td>';
  $html .= '<td>' . $row['DescripcionTipo'] . '</td>';
  $html .= '<td><i>' . $row['DescripcionProducto'] . '</i></td>';
  $html .= '<td><i>' . number_format($row['CapacipadCilindro'], 2, ',', '.') . '</i></td>';
  $html .= '<td>
                <button class="text-center btn btn-outline-success btnActualizarPrecio" value="' . $row['IDArticulo'] . '">
                    ' . number_format($row['PrecioArticulo'], 2) . '
                </button>
              </td>';
  $tabla .= '</tr>';
}
echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
