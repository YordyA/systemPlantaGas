<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once 'usuariosMain.php';

$html = '';
foreach (usuariosLista() as $row) {
  if ($row['IDUsuario'] == $_SESSION['PlantaGas']['IDUsuario']) {
    continue;
  }

  $html .= '<tr>';
  $html .= '<td>' . $row['NombreUsuario'] . '</td>';
  $html .= '<td>' . $row['Usuario'] . '</td>';
  $html .= '<td>' . $row['DescripcionPrivilegio'] . '</td>';
  $html .= '<td>
              <a class="btn btn-lg" href="index.php?vista=usuariosActualizar&id=' . encriptar($row['IDUsuario']) . '">
                <i class="lni lni-pencil"></i>
              </a>
            </td>';
  $html .= '<td>
              <button class="btn btn-lg btnEliminar" value="' . encriptar($row['IDUsuario']) . '">
                <i class="lni lni-cross-circle"></i>
              </button>
            </td>';
  $html .= '</tr>';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
