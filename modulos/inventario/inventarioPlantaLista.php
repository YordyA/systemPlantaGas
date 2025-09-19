<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once 'inventarioMain.php';

$IDPlanta = $_SESSION['PlantaGas']['IDPlanta'];
$html = '';
foreach (almacenLista([$IDPlanta]) as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['DescripcionAlmacen'] . '</td>';
  $html .= '<td>' . number_format($row['Cantidad'], 2) . '</td>';
  $html .= '<td>
              <button class="btn btn-lg btnRetirar" value="' . encriptar($row['IDInventario']) . '">
                <i class="lni lni-circle-minus"></i>
              </button>
            </td>';
  $html .= '<td>
              <a class="btn btn-lg" href="index.php?vista=almacenActualizar&id=' . encriptar($row['IDInventario']) . '">
                <i class="lni lni-pencil"></i>
              </a>
            </td>';
  $html .= '<td>
              <button class="btn btn-lg btnEliminar" value="' . encriptar($row['IDInventario']) . '">
                <i class="lni lni-cross-circle"></i>
              </button>
            </td>';
  $html .= '</tr>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
