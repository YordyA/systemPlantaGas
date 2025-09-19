<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'CobrarMain.php';
require_once '../dependencias.php';

$lockFile = 'lockfile.lock';
$lockHandle = fopen($lockFile, 'w');
if (flock($lockHandle, LOCK_EX)) {

  if (!isset($_SESSION['carritoVenta']['cliente']) || !isset($_SESSION['carritoVenta']['productos'])) {
    echo '
      <script>
        alert("SIN VENTA POR REGISTRAR")
        window.location.href = "https://agrofloracorpogaba.org.ve/systemFrigorificos/Vender"
      </script>';
    exit();
  }


  if ($_SESSION['carritoVenta']['cliente']['montoAbonadoBs'] < $_SESSION['carritoVenta']['cliente']['totalVenta']) {
    echo '
        <script>
          alert("NO HA CANCELADO EL TOTAL DE LA VENTA")
          window.location.href = "https://agrofloracorpogaba.org.ve/systemFrigorificos/Vender"
        </script>';
    exit();
  }

  $responsable =$_SESSION['PlantaGas']['nombreUsuario'];
  $IDSucursal =$_SESSION['PlantaGas']['IDPlanta'];
  $Nventa = NVenta();
  $NroCaja = $_SESSION['PlantaGas']['NroCaja'];
  $fecha = date('Y-m-d');
  $ultimaFacturaFiscal = 0;

  //* DATOS DEL CLIENTE
  $IDCliente = $_SESSION['carritoVenta']['cliente']['IDcliente'] ?? 19168;

  //^ PRECIO DE LAS MONEDAS DE REFERENCIA
  $tasaRef =$_SESSION['PlantaGas']['Dolar'];
  $tasCopRef = ($_SESSION['PlantaGas']['IDPlanta'] == 2) ? 4300 : 5000;

  $totalVenta = 0;
  $exento = 0;
  $gravado = 0;

  $IDFacturaResumen = registrarResumenVenta(
    [
      $IDSucursal,
      $NroCaja,
      $IDCliente,
      $Nventa,
      $fecha,
      date('Y-m-d H:i:s'),
      $responsable,
      0,
      $fecha
    ]
  );

  foreach ($_SESSION['carritoVenta']['productos'] as $row) {
    $totalVenta += round(floatval($row['subtotal']), 2);
    RegistrarDetalleVenta(
      [
        $IDFacturaResumen,
        $row['IDproducto'],
        floatval($row['precio']),
        floatval($row['cantidad']),
        floatval($row['subtotal']),
      ]
    );
    if ($row['alicuota'] == '0.00') {
      $exento += round(floatval($row['subtotal']), 2);
    } else if ($row['alicuota'] == '0.16') {
      $gravado += round(floatval($row['subtotal']), 2);
    }
  }

  //& MEDIOS DE PAGOS
  $totalExacto = 0;

  //^ MONTO ABONADO POR BIOPAGO
  $montoBiopago = $_SESSION['carritoVenta']['cliente']['montoBiopagoBs'];
  $totalExacto += round(floatval($montoBiopago), 2);

  //^ MONTO ABONADO POR PUNTO DE VENTA
  $montoPuntoVenta = $_SESSION['carritoVenta']['cliente']['montoPuntoVentaBs'];
  $totalExacto += round(floatval($montoPuntoVenta), 2);

  //^ PAGO MOVIL & TRANSFERENCIA
  $montoTotalPagoMovil = $_SESSION['carritoVenta']['cliente']['montoTotalPagoMovil'];
  $referenciaPagoMovil = $_SESSION['carritoVenta']['cliente']['referenciasMontosPagoMovil'];
  $totalExacto += round(floatval($montoTotalPagoMovil), 2);

  //^ MONTO ABONADO POR EFECTIVO
  $montoEfectivo =  $_SESSION['carritoVenta']['cliente']['montoEfectivoBs'];

  //! MONTO TOTAL DE USD EN BS
  $montoEfectivoUSD = round(floatval($_SESSION['carritoVenta']['cliente']['montoTotalUsd'] * $tasaRef), 2);
  //! MONTO TOTAL DE COP EN BS
  $montoEfectivoCOP = round(floatval(($_SESSION['carritoVenta']['cliente']['montoTotalCop'] / $tasCopRef) * $tasaRef), 2);

  $totalEfectivo = round(floatval($montoEfectivoCOP + $montoEfectivoUSD + $montoEfectivo), 2);
  $TotalMediosPago =  round(floatval($totalEfectivo + $montoPuntoVenta + $montoBiopago + $montoTotalPagoMovil), 2);

  $montoVueltoPagoMovil = 0;
  $montoTotalVueltoEfectivo = 0;
  $vuelto = 0;

  if ($totalEfectivo != '') {
    $montoVueltoBS = floatval($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoBs']);
    $montoVueltoUSD = floatval(round($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoUsd'] * $tasaRef, 2));
    $montoVueltoCOP = floatval(round(($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoCop'] / $tasCopRef) * $tasaRef, 2));

    $montoVueltoPagoMovil = floatval($_SESSION['carritoVenta']['cliente']['montoVueltoEntregadoPagoMovil']);

    $montoTotalVueltoEfectivo = floatval($montoVueltoBS + $montoVueltoUSD + $montoVueltoCOP);

    $vuelto = round(floatval(($_SESSION['carritoVenta']['cliente']['montoAbonadoBs'] - $_SESSION['carritoVenta']['cliente']['totalVenta'])), 2);
  }

  registrarMediosPagos(
    [
      $IDFacturaResumen,
      round(floatval($totalEfectivo), 2),
      round(floatval($montoPuntoVenta), 2),
      round(floatval($montoBiopago), 2),
      substr($referenciaPagoMovil, 0, -2),
      round(floatval($montoTotalPagoMovil), 2),
      $vuelto,
      $montoVueltoPagoMovil,
      $montoTotalVueltoEfectivo
    ]
  );

  ActualizarResumenVenta(
    [
      $totalVenta,
      $exento,
      $gravado - round(floatval($gravado - ($gravado / 1.16)), 2),
      round(floatval($gravado - ($gravado / 1.16)), 2),
      $IDFacturaResumen
    ]
  );

  if ($_SESSION['carritoVenta']['cliente']['montoTotalUsd'] != 0) {
    cobrarRegistrarBilleteUSD(
      [
        $IDFacturaResumen,
        $_SESSION['carritoVenta']['cliente']['billetesUsd'][1],
        $_SESSION['carritoVenta']['cliente']['billetesUsd'][2],
        $_SESSION['carritoVenta']['cliente']['billetesUsd'][5],
        $_SESSION['carritoVenta']['cliente']['billetesUsd'][10],
        $_SESSION['carritoVenta']['cliente']['billetesUsd'][20],
        $_SESSION['carritoVenta']['cliente']['billetesUsd'][50],
        $_SESSION['carritoVenta']['cliente']['billetesUsd'][100],
        $responsable,
        1
      ]
    );
    cobrarRegistrarBilleteUSD(
      [
        $IDFacturaResumen,
        $_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][1],
        $_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][2],
        $_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][5],
        $_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][10],
        $_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][20],
        $_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][50],
        $_SESSION['carritoVenta']['cliente']['billetesUsdVuelto'][100],
        $responsable,
        2
      ]
    );
  }

  if ($_SESSION['carritoVenta']['cliente']['montoTotalCop'] != 0) {
    cobrarRegistrarBilleteCOP(
      [
        $IDFacturaResumen,
        $_SESSION['carritoVenta']['cliente']['billetesCop'][50],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][100],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][200],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][500],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][1000],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][2000],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][5000],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][10000],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][20000],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][50000],
        $_SESSION['carritoVenta']['cliente']['billetesCop'][100000],
        $responsable,
        1
      ]
    );
    cobrarRegistrarBilleteCOP(
      [
        $IDFacturaResumen,
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][50],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][100],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][200],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][500],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][1000],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][2000],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][5000],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][10000],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][20000],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][50000],
        $_SESSION['carritoVenta']['cliente']['billetesCopVuelto'][100000],
        $responsable,
        2
      ]
    );
  }

  if ($_SESSION['carritoVenta']['cliente']['IDPagoMovil'] != 0) {
    cobrarActualizarVueltoBDV(
      [
        $IDFacturaResumen,
        $_SESSION['carritoVenta']['cliente']['IDPagoMovil']
      ]
    );
  }

  unset($_SESSION['carritoVenta']);
  echo '<script>window.location.href = "https://agrofloracorpogaba.org.ve/systemFrigorificos/modulos/cobrar/CobrarEmitirFacturacFiscal.php?n=' . Encriptar($IDFacturaResumen) . '&c=' . Encriptar($IDCliente) . '"</script>';

  flock($lockHandle, LOCK_UN);
} else {
  echo json_encode([false, 'POR FAVOR ESPERE']);
}
fclose($lockHandle);
