<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'inventarioPlantaMain.php';

$html = '';
foreach (inventarioPlantaLista() as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['CodigoArticulo'] . '</td>';
  $html .= '<td>' . $row['DescripcionArticulo'] . '</td>';
  $html .= '<td>' . $row['DescripcionEmpaque'] . '</td>';
  $html .= '<td>' . generarCeros($row['NroLote'], 5) . '</td>';
  $html .= '<td>' . number_format($row['Existencia'], 2) . '</td>';
  $html .= '<td>' . number_format($row['PrecioCosto'], 2) . '</td>';
  $html .= '<td>
              <button class="btn btn-outline-success btnPrecio" value="' . encriptar($row['IDInvPlanta']) . '">
                ' . number_format($row['PrecioVenta'], 3) . '
              </button>
            </td>';
  $html .= '<td>
              <button class="btn btn-lg btnRetirar" value="' . encriptar($row['IDInvPlanta']) . '">
                <i class="lni lni-circle-minus"></i>
              </button>
            </td>';
  $html .= '<td>' . $estadosArray[0] . '</td>';
  // $html .= '<td>
  //             <button class="btn btn-lg btnEliminar" value="' . encriptar($row['IDInvPlanta']) . '">
  //               <i class="lni lni-cross-circle"></i>
  //             </button>
  //           </td>';
  $html .= '</tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);