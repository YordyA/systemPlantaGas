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
  // Verificar y establecer la vista por defecto
  if (!isset($_GET['vista']) || $_GET['vista'] == '') {
    $_GET['vista'] = 'login';
  }
  
  // Verificar si el archivo de vista existe
  if (is_file('./vistas/' . $_GET['vista'] . '.php') && $_GET['vista'] != 'login' && $_GET['vista'] != '404') {
    
    // Verificar si la sesión existe y tiene la estructura esperada
    if (!isset($_SESSION['PlantaGas']) || empty($_SESSION['PlantaGas']) || !isset($_SESSION['PlantaGas']['IDPrivilegio'])) {
      include './inc/logout.php';
      exit();
    }
    
    // CORRECCIÓN: Cambiar "nabvar" por "navbar"
    $arrayNavbar = ['navbar.php', 'navbar.php', 'nabvarCaja.php', 'nabvarPlanta.php'];
    
    // Verificar que el índice de privilegio existe en el array
    $privilegio = $_SESSION['PlantaGas']['IDPrivilegio'];
    if (isset($arrayNavbar[$privilegio]) && is_file('./inc/' . $arrayNavbar[$privilegio])) {
      include './inc/' . $arrayNavbar[$privilegio];
    } else {
      // Fallback a navbar por defecto
      include './inc/navbar.php';
    }
    
    include './vistas/' . $_GET['vista'] . '.php';
    include './inc/script.php';
    
  } else {
    if ($_GET['vista'] == 'login') {
      include './vistas/login.php';
    } else {
      include './vistas/404.php'; // Cambié a vistas/404.php para consistencia
    }
  }
  ?>
</body>
</html>