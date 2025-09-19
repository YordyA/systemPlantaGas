<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../conteoFisico/inventarioConteo.php';
require_once '../dependencias.php';
require_once '../inventario/inventarioMain.php';

if (!isset($_SESSION['carritoVenta']['cliente'])) {
  echo json_encode(4);
  exit();
}

$codigo = LimpiarCadena($_POST['codigo']);
$cantidad = LimpiarCadena($_POST['cantidad']);

$usd = $_SESSION['PlantaGas']['Dolar'];

$consulta = verificarProductoCodigoVenta($codigo);
if ($consulta->rowCount() != 1) {
  echo json_encode(3);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

if ($consulta['PrecioVenta'] == 0) {
  echo json_encode(2);
  exit();
}

if (isset($_SESSION['carritoVenta']['productos'])) {
  $i = array_search($consulta['IDProducto'], array_column($_SESSION['carritoVenta']['productos'], 'IDproducto'));
  $a = ($i === null || $i == []) ? true : $_SESSION['carritoVenta']['productos'][$i]['IDproducto'] != $consulta['IDProducto'];
} else {
  $a = true;
}

$Existencia = 0;
foreach (almacenLista([$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  if ($row['Pricipal'] == 1) {
    $Existencia = $row['Cantidad'];
  }
}

// Validación de capacidad del cilindro
 $capacidadValida = ($Existencia >= ($consulta['CapacipadCilindro'] * 2));
 $Precio = $consulta['TipoMoneda'] == 1 ? round(floatval($consulta['PrecioVenta'] * $usd), 2) : round(floatval($consulta['PrecioVenta']), 2);
if ($a) {
  if ($capacidadValida) {
    $_SESSION['carritoVenta']['productos'][] = [
      'IDproducto' => $consulta['IDProducto'],
      'descripcion' => $consulta['DescripcionTipo'] . ' ' . $consulta['DescripcionProducto'],
      'precio' => round(floatval($Precio), 2),
      'cantidad' => round(floatval($cantidad), 3),
      'subtotal' => round(round(floatval($Precio), 2) * $cantidad, 2),
      'alicuota' => $consulta['Alicuota']
    ];
    $_SESSION['carritoVenta']['productos'] = array_reverse($_SESSION['carritoVenta']['productos']);
    echo json_encode(1);
  } else {
    echo json_encode(2);
  }
} else {
  if ($capacidadValida) {
    $_SESSION['carritoVenta']['productos'][$i]['cantidad'] += round(floatval($cantidad), 3);
    $_SESSION['carritoVenta']['productos'][$i]['subtotal'] += round(floatval(($Precio) * $cantidad), 2);
    echo json_encode(1);
  } else {
    echo json_encode(2);
  }
}
