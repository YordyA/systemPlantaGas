<?php
require_once '../sessionStart.php';
require_once '../dependencias.php';
if (isset($_SESSION['carritoVenta'])) {
  unset($_SESSION['carritoVenta']);
  echo json_encode(true);
} else {
  echo json_encode(false);
  exit();
}
