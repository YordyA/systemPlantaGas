<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'despachosMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$IDTipoDesp = desencriptar($_GET['id']);

$html = '';
foreach (reportDespahosRealizados([$del, $hasta], $IDTipoDesp) as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['DescripcionTipoDesp'] . '</td>';
  $html .= '<td>' . $row['FechaDesp'] . '</td>';
  $html .= '<td>' . generarCeros($row['NroNota'], 5) . '</td>';
  $html .= '<td>' . $row['RifCedulaCliente'] . '</td>';
  $html .= '<td>' . $row['RazonSocialCliente'] . '</td>';
  $html .= '<td>' . ($row['FacturaSerie'] != NULL ? $row['FacturaSerie'] : 'SIN INFORMACION') . '</td>';
  $html .= '<td>' . ($row['FacturaNro'] != NULL ? $row['FacturaNro'] : 'SIN INFORMACION') . '</td>';
  $html .= '<td>' . ($row['FacturaNroControl'] != NULL ? $row['FacturaNroControl'] : 'SIN INFORMACION') . '</td>';
  $html .= '<td>' . number_format($row['MontoTotalUSD'], 2) . '</td>';
  $html .= '<td>' . number_format($row['MontoExento'], 2) . '</td>';
  $html .= '<td>' . number_format($row['MontoBaseImponible'], 2) . '</td>';
  $html .= '<td>' . number_format($row['MontoIva'], 2) . '</td>';
  $html .= '<td>' . number_format($row['MontoExento'] + $row['MontoBaseImponible'] + $row['MontoIva'], 2) . '</td>';
  $html .= '<td><i>' . $row['ObservacionDesp'] . '</i></td>';
  $html .= '<td>
              <a class="btn btn-lg" href="modulos/pdf/PDFNotaControl.php?id=' . encriptar($row['IDDespachoResumen']) . '" target="_blank">
                <i class="lni lni-printer"></i>
              </a>
            </td>';
  $html .= '<td>
              <a class="btn btn-lg" href="modulos/pdf/PDFNotaDespachoUSD.php?id=' . encriptar($row['IDDespachoResumen']) . '" target="_blank">
                <i class="lni lni-printer"></i>
              </a>
            </td>';
  $html .= '<td>
            <a class="btn btn-lg" href="modulos/pdf/PDFNotaDespachoBS.php?id=' . encriptar($row['IDDespachoResumen']) . '" target="_blank">
              <i class="lni lni-printer"></i>
            </a>
          </td>';
  if ($row['IDTipoDespacho'] == 1 && $row['EstadoDesp'] == 1) {
    $html .= '<td>
                <a class="btn btn-lg" href="index.php?vista=despachosFacturar&id=' . encriptar($row['IDDespachoResumen']) . '">
                  <i class="lni lni-money-location"></i>
                </a>
              </td>';
  } else {
    $html .= '<td><span class="status-btn close-btn">DESACTIVADO</span></td>';
  }
  if ($row['EstadoDesp'] == 1 && time() < strtotime('+3 days', strtotime($row['FechaDesp']))) {
    $html .= '<td>
                <a class="btn btn-lg" href="index.php?vista=despachosAnular&id=' . encriptar($row['IDDespachoResumen']) . '">
                  <i class="lni lni-cross-circle"></i>
                </a>
              </td>';
  } else {
    $html .= '<td>' . $arrayEstadoDespacho[$row['EstadoDesp']] . '</td>';
  }
  $html .= '<td>' . $row['ResponsableDesp'] . '</td>';
  $html .= '</tr>';
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
