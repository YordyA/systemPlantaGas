<?php
require_once '../sessionStart.php';

$tabla = [];
$tabla['tabla'] = '';
if (isset($_SESSION['conteo']) && count($_SESSION['conteo']) > 0) {
  foreach ($_SESSION['conteo'] as $i => $row) {

    // if ($row['codigo'] != 909 || $row['codigo'] != 908) {
    //   continue;
    // }

    if ($row['existencia'] == 0) {
      continue;
    }

    $tabla['tabla'] .= '<tr>';
    $tabla['tabla'] .= '<td>' . $row['codigo'] . '</td>';
    $tabla['tabla'] .= '<td>' . $row['descripcion'] . '</td>';
    $tabla['tabla'] .= '<td>' . number_format($row['existencia'], 3, '.', ',') . '</td>';
    $tabla['tabla'] .= '<td>
                          <button class="text-center btn btn-outline-danger actualizar_cantidad" value="' . $i . '">
                              ' . number_format($row['cantidad'], 3) . '
                          </button>
                        </td>';
    $tabla['tabla'] .= '<td><b>' . number_format($row['diferencia'], 3, '.', ',') . '</b></td>';
    $tabla['tabla'] .= '</tr>';
  }
} else {
  $tabla['tabla'] .= '<tr>';
  $tabla['tabla'] .= '<td colspan="7" class="text-center">No Hay Informacion</td>';
  $tabla['tabla'] .= '</tr>';
}
echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
