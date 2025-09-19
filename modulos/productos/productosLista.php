<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'cisternasMain.php';

$html = '';
foreach (cisternasLista() as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $arrayTipoCisternaVehiculo[$row['TipoCisterna']] . '</td>';
  $html .= '<td>' . $row['EmpresaC'] . '</td>';
  $html .= '<td><i>' . $row['Modelo'] . '</i></td>';
  $html .= '<td><i>' . number_format($row['Capacidad'], 2, ',', '.') . '</i></td>';
  $html .= '<td>
              <a class="btn btn-lg" href="index.php?vista=cisternasActualizar&id=' . encriptar($row['IDCisterna']) . '">
                <i class="lni lni-pencil"></i>
              </a>
            </td>';
  $html .= '<td>
              <button class="btn btn-lg btnEliminar" value="' . encriptar($row['IDCisterna']) . '">
                <i class="lni lni-cross-circle"></i>
              </button>
            </td>';
  $html .= '</tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
