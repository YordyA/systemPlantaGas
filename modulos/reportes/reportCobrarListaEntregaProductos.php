<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../dependencias.php';
$del = LimpiarCadena($_GET['d']);
$hasta = LimpiarCadena($_GET['h']);
$IDSucursla = $_SESSION['PlantaGas']['IDPlanta'];

$html = '';
foreach (reportCobrarListaEntregaProductos([$IDSucursla, $del, $hasta]) as $row) {
  $html .= '<tr>';
  $html .= '<td>' . date('d-m-Y', strtotime($row['FechaHora'])) . '</td>';
  $html .= '<td>' . $row['NVentaResumen'] . '</td>';
  $html .= '<td>' . $row['DescripcionCaja'] . '</td>';
  $html .= '<td>' . $row['RifCliente'] . '</td>';
  $html .= '<td>' . $row['NombreCliente'] . '</td>';
  $html .= '<td>
              <a class="btn btn-lg factura" href="modulos/pdf/PDFNotaEntrega.php?id=' . Encriptar($row['IDResumenVenta']) . '" target="_blank">
                <i class="lni lni-files"></i>
              </a>
            </td>';
  $html .= '<td>
            <a class="btn btn-lg factura" href="index.php?vista=venderFacturasManualVender&id=' . Encriptar($row['IDResumenVenta']) . '">
              <i class="lni lni-checkmark-circle"></i>
            </a>
          </td>';
  if (date('Y-m-d', strtotime($row['FechaHora'])) == date('Y-m-d')) {
    $html .= '<td>
                <button class="btn btn-lg btnEliminar" value="' . Encriptar($row['IDResumenVenta']) . '">
                  <i class="lni lni-cross-circle"></i>
                </button>
              </td>';
  } else {
    $html .= '<td><span class="status-btn close-btn">DESACTIVADO</span></td>';
  }
  $html .= '</tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
