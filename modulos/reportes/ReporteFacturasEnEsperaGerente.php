<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'reportes_main.php';

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
                <button class="btn btn-lg cuentaPorCobrar" value="' . Encriptar($row['NFacturaEspera']) .
    '">
                  <i class="lni lni-customer"></i>
                </button>
            </td>';
  $tabla .= '<td>
                <button class="btn btn-lg donacion" value="' . Encriptar($row['NFacturaEspera']) . '">
                  <i class="lni lni-gift"></i>
                </button>
            </td>';
  $tabla .= '<td>
                <button class="btn btn-lg consumo" value="' . Encriptar($row['NFacturaEspera']) . '">
                  <i class="lni lni-dinner"></i>
                </button>
            </td>';
  $tabla .= '<td>
                <button class="btn btn-lg btnDonacionTrabajador" value="' . Encriptar($row['NFacturaEspera']) . '">
                  <i class="lni lni-consulting"></i>
                </button>
            </td>';
  $tabla .= '<td>
            <button class="btn btn-lg btnEntregaProductos" value="' . Encriptar($row['NFacturaEspera']) . '">
              <i class="lni lni-handshake"></i>
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
