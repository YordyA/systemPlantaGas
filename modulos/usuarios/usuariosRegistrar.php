<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once 'usuariosMain.php';

$nombreUsuario = limpiarCadena($_POST['nombreUsuario']);
$usuario = limpiarCadena($_POST['usuario']);
$clave1 = limpiarCadena($_POST['clave1']);
$clave2 = limpiarCadena($_POST['clave2']);
$IDPrivilegio = desencriptar($_POST['IDPrivilegio']);

if ($nombreUsuario == '' || $usuario == '' || $clave1 == '' || $clave2 == '' || $IDPrivilegio == '') {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

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

if ($clave1 != $clave2) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "LAS CLAVES INGRESADAS NO COINCIDEN",
    "tipo"    => "error"
  ];
  exit();
} else {
  $claveHash = password_hash($clave1, PASSWORD_BCRYPT, ['cost' => 10]);
}

usuariosRegistrar(
  [
    $nombreUsuario,
    $usuario,
    $claveHash,
    $IDPrivilegio
  ]
);
$alerta = [
  "alerta"  => "limpiar",
  "titulo"  => "¡USUARIO REGISTRADO!",
  "texto"   => "EL USUARIO HA SIDO REGISTRADO CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
