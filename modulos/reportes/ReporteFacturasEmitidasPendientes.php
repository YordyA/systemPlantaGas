<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../dependencias.php';

$tabla = '';
$estatus = array(
  '<span class="status-btn success-btn">CANCELADA</span>',
  '<span class="status-btn warning-btn">POR CANCELAR</span>',
  '<span class="status-btn close-btn"> ANULADA</span>'
);

foreach (reporteFacturasPendientePorDespacho([$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['Fecha'] . '</td>';
  $tabla .= '<td>' . $row['NVentaResumen'] . '</td>';
  $tabla .= '<td>' . $row['NFacturaFiscal'] . '</td>';
  $tabla .= '<td>' . $row['DescripcionCaja'] . '</td>';
  $tabla .= '<td>' . $row['RifCliente'] . '</td>';
  $tabla .= '<td>' . $row['NombreCliente'] . '</td>';
  $tabla .= '<td>' . $estatus[$row['Estatus']] . '</td>';
  $tabla .= '<td>
              <button class="btn btn-lg factura" value="' . Encriptar($row['IDResumenVenta']) . '">
                <i class="lni lni-files"></i>
              </button>
            </td>';
  $tabla .= '<td>
    <button class="btn btn-lg btn-success despachar-btn" value="' . Encriptar($row['IDResumenVenta']) . '">
        <i class="lni lni-checkmark-circle"></i> DESPACHAR
    </button>
  </td>';
  $tabla .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);