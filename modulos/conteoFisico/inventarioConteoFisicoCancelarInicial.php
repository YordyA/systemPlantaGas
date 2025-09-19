<?php
require_once '../sessionStart.php';

if (isset($_SESSION['conteo'])) {
  unset($_SESSION['conteo']);
  echo json_encode(true);
}else {
  echo json_encode(false);
}