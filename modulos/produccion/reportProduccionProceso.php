<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'produccionMain.php';

$html = '';
foreach (reportProduccioneEnProceso() as $row) {
  $html .= '<tr>';
  $html .= '<td>' . $row['FechaProduccion'] . '</td>';
  $html .= '<td>' . $row['FechaCaducidad'] . '</td>';
  $html .= '<td>' . $row['CodigoArticulo'] . '</td>';
  $html .= '<td>' . $row['DescripcionArticulo'] . '</td>';
  $html .= '<td>' . generarCeros($row['NroLote'], 5) . '</td>';
  $html .= '<td>' . $row['ResponsableProduccion'] . '</td>';
  $html .= '<td>
  <button class="btn btn-lg btnDetalleInv" value="' . encriptar($row['IDProduccionResumen']) . '">
    <i class="lni lni-ticket"></i>
  </button>
</td>';
  $html .= '<td>
              <button class="btn btn-lg btnDetalle" value="' . encriptar($row['IDProduccionResumen']) . '">
                <i class="lni lni-ticket"></i>
              </button>
            </td>';
  if ($row['EstadoProduccion'] == 2 || $row['EstadoProduccion'] == 3) {
    $html .= '<td>
    <a class="btn btn-lg" href="index.php?vista=produccionSubProductos&id=' . encriptar($row['IDProduccionResumen']) . '">
  <i class="lni lni-check-box"></i>
    </a>
  </td>';
  } else {
    $html .= '<td> <span class="status-btn warning-btn">DESACTIVADO</span>     </td>';
  }
  if ($row['EstadoProduccion'] == 3 || $row['EstadoProduccion'] == 2) {
    $html .= '<td>
          <a class="btn btn-lg" href="index.php?vista=produccionCostosRegistrar&id=' . encriptar($row['IDProduccionResumen']) . '">
        <i class="lni lni-money-protection"></i>
          </a>
        </td>';
  } else {
    $html .= '<td> <span class="status-btn warning-btn">EN PROCESO</span>     </td>';
  }
  $consultaSubproductos = produccionConsultarSubProductosXID([$row['IDProduccionResumen']]);
  if ($consultaSubproductos->rowCount() == 0) {
    $html .= '<td>
                <a class="btn btn-lg" href="index.php?vista=produccionAnular&id=' . encriptar($row['IDProduccionResumen']) . '">
                  <i class="lni lni-cross-circle"></i>
                </a>
              </td>';
    $html .= '</tr>';
  } else {
    $html .= '<td> <span class="status-btn success-btn">ACTIVO</span>     </td>';
    $html .= '</tr>';
  }
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
