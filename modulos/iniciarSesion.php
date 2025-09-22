<?php
$usuario = limpiarCadena($_POST['usuario']);
$clave = limpiarCadena($_POST['clave']);
$fecha = date('Y-m-d');
if ($usuario == '' || $clave == '') {
  echo "<script>
          swal.fire({
            icon: 'error',
            title: '¡OCURRIÓ UN ERROR INESPERADO!',
            text: 'TODOS LOS CAMPOS QUE SON OBLIGATORIOS'
              })
        </script>";
  exit();
}

$consultaUsuario = usuariosVerificarXUSUARIO([$usuario]);
if ($consultaUsuario->rowCount() != 1) {
  echo "<script>
          swal.fire({
            icon: 'error',
            title: '¡OCURRIÓ UN ERROR INESPERADO!',
            text: 'USUARIO O CLAVE INCORRECTOS'
              })
        </script>";
  exit();
}
$consultaUsuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

if (!password_verify($clave, $consultaUsuario['Clave'])) {
  echo "<script>
          swal.fire({
            icon: 'error',
            title: '¡OCURRIÓ UN ERROR INESPERADO!',
            text: 'USUARIO O CLAVE INCORRECTOS'
              })
        </script>";
  exit();
}

$verificar_dolar = conexionAdministrativo()->prepare('SELECT * FROM historial_tasa_bcv WHERE FechaTasa = ?');
$verificar_dolar->execute([$fecha]);
if ($verificar_dolar->rowCount() != 1) {
  echo "<script>
          swal.fire({
            icon: 'error',
            title: '¡OCURRIO UN ERROR INESPERADO!',
            text: 'NO EXISTE TASA DE CAMBIO PARA EL DIA DE HOY'
              })
        </script>";
  exit();
}
$verificar_dolar = $verificar_dolar->fetch(PDO::FETCH_ASSOC);

$verificar_pesaje_inicial = conexion();
$verificar_pesaje_inicial = $verificar_pesaje_inicial->prepare('SELECT * FROM inventarioconteo WHERE TipoConteo = 0 AND Fecha = ? AND IDSucursal = ?');
$verificar_pesaje_inicial->execute([$fecha, $consultaUsuario['IDEmpresa']]);

$_SESSION['PlantaGas'] = [
  'IDUsuario'         => $consultaUsuario['IDUsuario'],
  'IDPlanta'          => $consultaUsuario['IDEmpresa'],
  'Planta'             => $consultaUsuario['DescripcionPlanta'],
  'IDPrivilegio'      => $consultaUsuario['IDPrivilegio'],
  'nombreUsuario'     => $consultaUsuario['NombreUsuario'],
  'usuario'           => $consultaUsuario['Usuario'],
  'privilegio'        => $consultaUsuario['DescripcionPrivilegio'],
  'Dolar'           => $verificar_dolar['TasaRefUSD'],
  'PesajeInicial'   => $verificar_pesaje_inicial->rowCount()
];

if (headers_sent()) {
  echo "<script> window.location.href='bienvenidos'; </script>";
} else {
  header("Location: bienvenidos");
}
