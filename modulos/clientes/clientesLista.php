<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'clientesMain.php';

$html = '';
foreach (clientesLista() as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['RifCedula'] . '</td>';
  $html .= '<td>' . $row['RazonSocial'] . '</td>';
  $html .= '<td><i>' . $row['DomicilioFiscal'] . '</i></td>';
  $html .= '<td>
              <a class="btn btn-lg" href="index.php?vista=clientesActualizar&id=' . encriptar($row['IDCliente']) . '">
                <i class="lni lni-pencil"></i>
              </a>
            </td>';
  $html .= '<td>
              <button class="btn btn-lg btnEliminar" value="' . encriptar($row['IDCliente']) . '">
                <i class="lni lni-cross-circle"></i>
              </button>
            </td>';
  $html .= '</tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
