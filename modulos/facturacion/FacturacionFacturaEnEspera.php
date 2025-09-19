<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'FacturacionMain.php';
require_once '../dependencias.php';
if (!isset($_SESSION['carritoVenta']['cliente']) && !isset($_SESSION['carritoVenta']['productos'])) {
  echo json_encode([false, 'DEBE RELLENAR TODO LOS CAMPOS']);
  exit();
}

$NFacturaEnEspera = NFacturaEnEspera();

$IDCliente = $_SESSION['carritoVenta']['cliente']['IDcliente'];
$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];
$NroCaja = $_SESSION['caja']['NroCaja'] ?? 1;

foreach ($_SESSION['carritoVenta']['productos'] as $row) {
  RegistrarFacturaEnEspera(
    [
      $NFacturaEnEspera,
      $IDCliente,
      $IDSucursal,
      $NroCaja,
      $row['IDproducto'],
      $row['cantidad'],
      $row['precio']
    ]
  );
}

unset($_SESSION['carritoVenta']);
if (isset($_GET['i'])) {
  echo "<script>window.location.href = 'http://localhost/SistemFrigorifico/Vender'</script>";
} else {
  echo json_encode([true, 'FACTURA GUARDADA EN ESPERA']);
}