<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'inventarioProduccionConteoMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);

$html = '';
foreach (reportInventarioProduccionConteoRealizados([$del, $hasta]) as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['FechaCierreConteo'] . '</td>';
  $html .= '<td>' . date('Y', strtotime($row['FechaCierreConteo'])) . ' - ' . generarCeros($row['NroConteo'], 5) . '</td>';
  $html .= '<td>' . $row['ResponsableConteo'] . '</td>';
  $html .= '<td>
              <a class="btn btn-lg" href="modulos/excel/EXCELConteo.php?id=' . encriptar($row['NroConteo']) . '" target="_blak">
                <i class="lni lni-files"></i>
              </a>
            </td>';
  if ($row['EstadoConteo'] == 2 && $row['FechaCierreConteo'] == date('Y-m-d')) {
    $html .= '<td>
                <a class="btn btn-lg" href="index.php?vista=inventarioProduccionConteoAnular&id=' . encriptar($row['NroConteo']) . '">
                  <i class="lni lni-cross-circle"></i>
                </a>
              </td>';
  } else {
    $html .= '<td><span class="status-btn close-btn">DESACTIVADO</span></td>';
  }
  $html .= '</tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
