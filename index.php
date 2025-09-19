<?php
$peticion = true;
require './modulos/sessionStart.php';
require './modulos/dependencias.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EMPRESA BATALLA MATA DE LA MIEL GAS APURE C.A</title>
  <link rel="icon" type="image/png" href="favicon.ico">
  <?php include './inc/links.php'; ?>
</head>

<body>
  <?php
  if (!isset($_GET['vista']) || $_GET['vista'] == '') {
    $_GET['vista'] = 'login';
  }
  if (is_file('./vistas/' . $_GET['vista'] . '.php') && $_GET['vista'] != 'login' && $_GET['vista'] != '404') {
    if (!isset($_SESSION['PlantaGas']) || $_SESSION['PlantaGas'] == []) {
      include './inc/logout.php';
      exit();
    }
    include './inc/navbar.php';
    include './vistas/' . $_GET['vista'] . '.php';
    include './inc/script.php';
  } else {
    if ($_GET['vista'] == 'login') {
      include './vistas/login.php';
    } else {
      include './inc/404.php';
    }
  }
  ?>
</body>

</html>