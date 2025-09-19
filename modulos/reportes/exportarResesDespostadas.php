<?php
require_once '../main.php';
require_once 'reportes_main.php';
require_once '../sessionStart.php';

$del = LimpiarCadena($_GET['del']);
$hasta = LimpiarCadena($_GET['hasta']);
$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];

$tabla = '';

foreach (reporteListaResesDespostadas([$del, $hasta, $IDSucursal]) as $data) {
    $tabla  .= '<tr>';
    $tabla  .= '<td>' . $data['Fecha'] .'</td>';
    $tabla  .= '<td> RES Nro: ' . $data['CodigoRes'] .'</td>';
    $tabla  .= '<td>' . number_format($data['Peso'],3) .'</td>';
    $tabla  .= '<td>' . $data['TotalCosto'] .'</td>';
    $tabla .= '<td>
                <a class="btn btn-lg" target="_blank" href="modulos/pdf/DesposteDeRes.php?IDRes=' . encriptar($data['IDRes']) . '">
                  <i class="lni lni-printer"></i>
                </a>
              </td>';
$tabla .= '<td>
                <a class="btn btn-lg" href="index.php?vista=InventarioAnularRes&id=' . encriptar($data['ID']) . '">
                  <i class="lni lni-cross-circle"></i>
                </a>
            </td>';
    $tabla  .= '</tr>';
  }

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);