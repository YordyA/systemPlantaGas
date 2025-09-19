<?php
if (!isset($_GET['id']) || $_GET['id'] == '' || !isset($_POST['cantDesp']) || $_POST['cantDesp'] == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once '../inventario/inventarioMain.php';

$getsDesencriptar = desencriptar($_GET['id']);
$cantDesp = limpiarCadena($_POST['cantDesp']);

$consulta = inventarioVerificarXID([$getsDesencriptar]);
if ($consulta->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL PRODUCTO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

if ($consulta['Existencia'] < $cantDesp) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL PRODUCTO NO POSEE EXISTENCIA SUFICIENTE",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

if (isset($_SESSION['despacho']['detalle']) && count($_SESSION['despacho']['detalle']) == 7) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "HA ALCANZADO EL NRO DE ITEM PARA EL DESPACHO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$_SESSION['despacho']['detalle'][] = [
    'id'            => $getsDesencriptar,
    'codigo'        => $consulta['CodigoProducto'],
    'descripcion'   => $consulta['DescripcionProducto'],
    'presentacion'  => $consulta['DescripcionPresentacion'] ?? '',
    'existencia'    => $consulta['Existencia'],
    'precioVenta'   => $consulta['PrecioUnitario'],
    'cantidad'      => $cantDesp,
    'valorAlicuota' => $consulta['ValorAlicuota'] ?? 0
  ];

$alerta = [
  "alerta"  => "simple",
  "titulo"  => "¡PRODUCTO AGREGADO!",
  "texto"   => "EL PRODUCTO HA SIDO AGREGADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
