<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
if (isset($_SESSION['carritoVenta']['productos'])) {
  $i = LimpiarCadena($_GET['i']);
  if ($i == '') {
    echo json_encode(false);
    exit();
  }

  if (count($_SESSION['carritoVenta']['productos']) > 1) {
    unset($_SESSION['carritoVenta']['productos'][$i]);
  } else {
    unset($_SESSION['carritoVenta']);
  }
  echo json_encode(true);
} else {
  echo json_encode(false);
  exit();
}
