<?php
require_once '../main.php';
require_once '../inventario/inventario_main.php';
require_once '../usuario/UsuarioMain.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
if (!isset($_SESSION['carritoVenta']['productos'])) {
  echo json_encode([false, 'NO HAY PRODUCTOS']);
  exit();
}

$i = LimpiarCadena($_GET['i']);
$precioUSD = LimpiarCadena($_POST['precioEspecial']);

$usuario = LimpiarCadena($_POST['usuario']);
$clave = LimpiarCadena($_POST['clave']);

if ($i == '' || $precioUSD == '' || $usuario == '' || $clave == '') {
  echo json_encode([false, 'TODOS LOS CAMPOS SON REQUERIDOS']);
  exit();
}

$consultarUsuario = VerificarUsuarioPorUsuario($usuario);
if ($consultarUsuario->rowCount() == 0) {
  echo json_encode([false, 'EL USUARIO NO EXITE']);
  exit();
}

$consultarUsuario = $consultarUsuario->fetch(PDO::FETCH_ASSOC);
if (!password_verify($clave, $consultarUsuario['Clave'])) {
  echo json_encode([false, 'CLAVE O USUARIO INCORRECTOS']);
  exit();
}

if ($consultarUsuario['Privilegio'] == 2) {
  echo json_encode([false, 'NO TIENE PRIVILEGIO']);
  exit();
}

$tasaReferenciaUSD = round(floatval($_SESSION['PlantaGas']['Dolar']), 2);

$precioBolivares = round(floatval($precioUSD * $tasaReferenciaUSD), 2);

$_SESSION['carritoVenta']['productos'][$i]['precio'] = round(floatval($precioBolivares), 2);
$_SESSION['carritoVenta']['productos'][$i]['subtotal'] = round(floatval($_SESSION['carritoVenta']['productos'][$i]['cantidad'] * $precioBolivares), 2);
echo json_encode([true, 'PRECIO ESPECIAL INGRESADO']);
