<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'produccionMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$p = desencriptar($_GET['p']);
$html = '';
foreach (reportProduccionHistorial([$del, $hasta],$p) as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['FechaProduccion'] . '</td>';
  $html .= '<td>' . $row['FechaCaducidad'] . '</td>';
  $html .= '<td>' . $row['CodigoArticulo'] . '</td>';
  $html .= '<td>' . $row['DescripcionArticulo'] . '</td>';
  $html .= '<td>' . $row['DescripcionProducto'] . '</td>';
  $html .= '<td>' . generarCeros($row['NroLote'], 5) . '</td>';
  $html .= '<td>' . number_format($row['CantidadProducida'], 0) . '</td>';
  $html .= '<td>' . number_format($row['CostoXSaco'], 2) . '</td>';
  $html .= '<td>' . number_format($row['TotalCostoProduccion'], 2) . '</td>';
  $html .= '<td>' . $row['ResponsableProduccion'] . '</td>';
  $html .= '<td>
              <button class="btn btn-lg btnDetalle" value="' . encriptar($row['IDProduccionResumen']) . '">
                <i class="lni lni-ticket"></i>
              </button>
            </td>';
  $html .= '<td>
              <a class="btn btn-lg" href="modulos/excel/EXCELProduccion.php?id=' . encriptar($row['IDProduccionResumen']) . '" target="_blank">
                <i class="lni lni-printer"></i>
              </a>
            </td>';
  if ($row['EstadoProduccion'] == 1 && $row['FechaProduccion'] == date('Y-m-d')) {
    $html .= '<td>
                <a class="btn btn-lg" href="index.php?vista=produccionAnular&id=' . encriptar($row['IDProduccionResumen']) . '">
                  <i class="lni lni-cross-circle"></i>
                </a>
              </td>';
  } else {
    $html .= '<td>' . $arrayEstadoProduccion[$row['EstadoProduccion']] . '</td>';
  }
  $html .= '</tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
