<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../dependencias.php';
$del = LimpiarCadena($_GET['del']);
$hasta = LimpiarCadena($_GET['hasta']);

$tabla = '';
$tipoDonaciones = array(
  0 => 'CONTRIBUCION SOCIAL EMPRESARIAL',
  1 => 'AUTOCONSUMO',
  2 => 'CONTRIBUCION SOCIAL TRABAJADOR'
);

$Estado = array(
  1 => 'ANULADO',
  0 => 'CONFIRMADO'
);

foreach (reporteFacturasDonaciones([$_SESSION['PlantaGas']['IDPlanta'], $del, $hasta]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $tipoDonaciones[$row['TipoConsumo']] . '</td>';
  $tabla .= '<td>' . $row['Fecha'] . '</td>';
  $tabla .= '<td>' . $row['NDonacion'] . '</td>';
  $tabla .= '<td>' . $row['RifCliente'] . '</td>';
  $tabla .= '<td>' . $row['NombreCliente'] . '</td>';
  $tabla .= '<td>' . number_format($row['MontoTotalBS'], 2) . '</td>';
  $tabla .= '<td>' . number_format($row['MontoTotalUSD'], 2) . '</td>';
  $tabla .= '<td>
              <a class="btn btn-lg" href="modulos/pdf/Donacion.php?id=' . Encriptar($row['IDDonaciones']) . '" target="_blank">
                <i class="lni lni-files"></i>
              </a>
            </td>';
  if ($row['Estatus'] == 1) {
    $tabla .= '<td>' . $Estado[$row['Estatus']] . '</td>';
  } elseif ($row['Fecha'] == date('Y-m-d') && $row['Estatus'] == 0) {
    $tabla .= '<td><button class="btn btn-lg btnEliminar" value="' . Encriptar($row['IDDonaciones']) . '"><i class="lni lni-cross-circle"></i></button></td>';
  } elseif ($row['Fecha'] < date('Y-m-d')) {
    $tabla .= '<td>' . $Estado[$row['Estatus']] . '</td>';
  }

  $tabla .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
