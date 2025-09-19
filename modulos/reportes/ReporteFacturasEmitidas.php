<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../dependencias.php';
$del = LimpiarCadena($_GET['del']);
$hasta = LimpiarCadena($_GET['hasta']);

$tabla = '';

$estatus = array(
  '<span class="status-btn success-btn">CANCELADA</span>',
  '<span class="status-btn warning-btn">POR CANCELAR</span>',
  '<span class="status-btn close-btn"> ANULADA</span>'
);

foreach (reporteFacturasEmitidas([$_SESSION['PlantaGas']['IDPlanta'], $del, $hasta]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['NVentaResumen'] . '</td>';
  if ($row['NFacturaFiscal'] != 0) {
    $tabla .= '<td>' . $row['NFacturaFiscal'] . '</td>';
  } else {
    $tabla .= '<td>
                <a class="btn btn-lg" href="modulos/pdf/PDFNotaEntrega.php?id=' . Encriptar($row['IDResumenVenta']) . '" target="_blank">
                  <i class="lni lni-printer"></i>
                </a>
              </td>';
  }

  $tabla .= '<td>' . $row['DescripcionCaja'] . '</td>';
  $tabla .= '<td>' . $row['RifCliente'] . '</td>';
  $tabla .= '<td>' . $row['NombreCliente'] . '</td>';
  $tabla .= '<td>' . $estatus[$row['Estatus']] . '</td>';
  $tabla .= '<td>
              <button class="btn btn-lg factura" value="' . Encriptar($row['IDResumenVenta']) . '">
                <i class="lni lni-files"></i>
              </button>
            </td>';
  if (date('Y-m-d') == $row['Fecha'] && $row['Estatus'] == 0 &&  $row['NFacturaFiscal'] != 0) {
    $tabla .= '<td>
                <button class="btn btn-lg anular" value="' . Encriptar($row['IDResumenVenta']) . '">
                  <i class="lni lni-cross-circle"></i>
                </button>
            </td>';
  } else {
    if ($row['NFacturaFiscal'] == 0) {
      $tabla .= '<td>
                        <a class="btn btn-lg" href="https://sistemasinternos.net/systemPlantaGas/modulos/cobrar/CobrarEmitirFacturacFiscal.php?n=' . Encriptar($row['IDResumenVenta']) . '&c=' . Encriptar($row['IDCliente']) . '&NroCaja=' . Encriptar($row['IDCaja']) . '">
                            <i class="lni lni-reply"></i>
                        </a>
                    </td>';
    } elseif ($_SESSION['PlantaGas']['IDUsuario'] == 1 || ($row['NFacturaFiscal'] != 0)) {
      $tabla .= '<td>
                        <a class="btn btn-lg" href="https://sistemasinternos.net/systemPlantaGas/modulos/cobrar/CobrarNotaCreditoMANUAL.php?n=' . Encriptar($row['IDResumenVenta']) . '&s=' . Encriptar($_SESSION['PlantaGas']['IDPlanta']) . '">
                            <i class="lni lni-reply"></i>
                        </a>
                    </td>';
    } else {
      $tabla .= '<td>
                  <span class="status-btn dark-btn">LA FACTURA NO SE PUEDE ANULAR</span>
                </td>';
    }
  }

  $tabla .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
