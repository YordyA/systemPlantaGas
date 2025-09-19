<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';
require_once '../dependencias.php';
$tabla = '';

foreach (reporteFacturasEnEspera([$_SESSION['PlantaGas']['IDPlanta'], 1]) as $row) {
  $tabla .= '<tr>';
  $tabla .= '<td>' . $row['NFacturaEspera'] . '</td>';
  $tabla .= '<td>' . $row['RifCliente'] . '</td>';
  $tabla .= '<td>' . $row['NombreCliente'] . '</td>';
  $tabla .= '<td>
              <button class="btn btn-lg factura" value="' . Encriptar($row['NFacturaEspera']) . '">
                <i class="lni lni-files"></i>
              </button>
            </td>';
  $tabla .= '<td>
                <button class="btn btn-lg montarFactura" value="' . Encriptar($row['NFacturaEspera']) . '">
                    <i class="lni lni-cart"></i>
                </button>
            </td>';
  $tabla .= '<td>
                <button class="btn btn-lg quitar" value="' . Encriptar($row['NFacturaEspera']) . '">
                  <i class="lni lni-cross-circle"></i>
                </button>
            </td>';

  $tabla .= '</tr>';
}

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
