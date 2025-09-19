<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
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
require_once 'usuariosMain.php';

$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDUsuario = Desencriptar($_GET['id']);
$consulta = usuariosVerificarXID([$IDUsuario]);
if ($consulta->rowCount() != 1) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "EL USUARIO NO SE ENCUENTRA REGISTRADO",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

$nombreUsuario = limpiarCadena($_POST['nombreUsuario']);
$usuario = limpiarCadena($_POST['usuario']);
$clave1 = limpiarCadena($_POST['clave1']);
$clave2 = limpiarCadena($_POST['clave2']);
$IDPrivilegio = isset($_POST['IDPrivilegio']) ? desencriptar($_POST['IDPrivilegio']) : $_SESSION['PlantaGas']['IDPrivilegio'];

if ($nombreUsuario == '' || $usuario == '' || $IDPrivilegio == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

if ($usuario != $consulta['Usuario']) {
  if (usuariosVerificarXUSUARIO([$usuario])->rowCount() > 0) {
    $alerta = [
      "alerta"  => "simple",
      "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
      "texto"   => "EL USUARIO INGRESADO YA SE ENCUENTRA REGISTRADO",
      "tipo"    => "error"
    ];
    echo json_encode($alerta);
    exit();
  }
}

if ($clave1 != '' || $clave2 != '') {
  if ($clave1 != $clave2) {
    $alerta = [
      "alerta"  => "simple",
      "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
      "texto"   => "LAS CLAVES INGRESADAS NO COINCIDEN",
      "tipo"    => "error"
    ];
    echo json_encode($alerta);
    exit();
  } else {
    $claveHash = password_hash($clave1, PASSWORD_BCRYPT, ['cost' => 10]);
  }
} else {
  $claveHash = $consulta['Clave'];
}

usuariosActualizar(
  [
    $nombreUsuario,
    $usuario,
    $claveHash,
    $IDPrivilegio,
    ($fechaHoraModificacion . ' - ' . $responsable),
    $IDUsuario
  ]
);
if ($_SESSION['PlantaGas']['IDUsuario'] == $IDUsuario) {
  $_SESSION['PlantaGas']['nombreUsuario'] = $nombreUsuario;
  $_SESSION['PlantaGas']['usuario'] = $usuario;
}
$alerta = [
  "alerta"  => ($_SESSION['PlantaGas']['IDUsuario'] == $IDUsuario ? "recargar" : "volver"),
  "titulo"  => ($_SESSION['PlantaGas']['IDUsuario'] == $IDUsuario ? "¡PERFIL ACTUALIZADO!" : "¡USUARIO ACTUALIZADO!"),
  "texto"   => ($_SESSION['PlantaGas']['IDUsuario'] == $IDUsuario ? "EL PERFIL HA SIDO ACTUALIZADO" : "EL USUARIO HA SIDO ACTUALIZADO CON EXITO"),
  "tipo"    => "success"
];
echo json_encode($alerta);