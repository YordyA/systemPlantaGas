<?php
require_once '../main.php';
require_once '../sessionStart.php';

$i = LimpiarCadena($_GET['i']);
$cantidad = LimpiarCadena($_GET['cantidad']);

$_SESSION['conteo'][$i]['cantidad'] = $cantidad;
$_SESSION['conteo'][$i]['diferencia'] = $cantidad - $_SESSION['conteo'][$i]['existencia'];
echo json_encode(true);