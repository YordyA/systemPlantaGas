<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../dependencias.php';
$del = LimpiarCadena($_GET['del']);
$hasta = LimpiarCadena($_GET['hasta']);

$tabla = '';

foreach (reporteFacturasEmitidasCxC([$_SESSION['PlantaGas']['IDPlanta'], $del, $hasta]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['Fecha'] . '</td>';
  $tabla .= '<td>' . $row['NVentaResumen'] . '</td>';
  $tabla .= '<td>' . $row['NFacturaFiscal'] . '</td>';
  $tabla .= '<td>' . $row['RifCliente'] . '</td>';
  $tabla .= '<td>' . $row['NombreCliente'] . '</td>';
  $tabla .= '<td>' . number_format($row['total'], 2) . '</td>';
  $tabla .= '<td>
              <button class="btn btn-lg factura" value="' . Encriptar($row['IDResumenVenta']) . '">
                <i class="lni lni-files"></i>
              </button>
            </td>';
  $tabla .= '<td>
                <button class="btn btn-lg pagar" value="' . Encriptar($row['IDResumenVenta']) . '">
                  <i class="lni lni-coin"></i>
                </button>
            </td>';
  $tabla .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
