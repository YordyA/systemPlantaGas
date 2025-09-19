<?php
require_once '../main.php';
require_once 'reportes_main.php';
require_once '../sessionStart.php';

$tabla = '';

foreach (lista_de_conteo_fisicos($_SESSION['PlantaGas']['IDPlanta']) as $row) {
  if ($row['TipoConteo'] == 0) {
    $tipo =  '<span class="status-btn success-btn">PESAJE INICIAL</span>';
  } elseif ($row['TipoConteo'] == 1) {
    $tipo = '<span class="status-btn close-btn">PESAJE FINAL</span>';
  }

  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['Fecha'] . '</td>';
  $tabla .= '<td>' .  $row['IDConteo'] . '</td>';
  $tabla .= '<td>' .     $tipo . '</td>';
  $tabla .= '<td>
                  <div class=" text-center">
                    <a class="btn" target="_blank" href="modulos/pdf/ConteoFisico.php?IDConteo=' . encriptar($row['IDConteo']) . '">
                    <i class="lni lni-printer"></i>
                    </a>
                  </div>
                </td>';
  $tabla .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
