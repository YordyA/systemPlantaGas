<?php
require_once '../main.php';
require_once '../cobrar/CobrarMain.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
if (!isset($_GET['n']) || $_GET['n'] == '') {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "¡Ocurrio un error inesperado!",
    "texto" => "Debe rellenar todo los campos",
    "tipo" => "error"
  );
  echo json_encode($alerta);
  exit();
}

$NVenta = Desencriptar(LimpiarCadena($_GET['n']));
$efectivo = LimpiarCadena($_POST['efectivo']);
$tarjeta = LimpiarCadena($_POST['tarjeta']);
$bioPago = LimpiarCadena($_POST['bioPago']);
$pagoMovil = LimpiarCadena($_POST['pagoMovil']);
$crucesFAC = LimpiarCadena($_POST['cruces']);


$consulta = VentasPorNroVenta([Desencriptar(LimpiarCadena($_GET['n']))]);
$consulta = $consulta->fetch();

$suma1 = ($consulta['Transferencia'] + $consulta['Efectivo'] + $consulta['Tarjeta'] + $consulta['BioPago'] + $consulta['CrucesFacturas']);
$suma2 = ($efectivo + $tarjeta + $bioPago + $pagoMovil + $crucesFAC);

if (($suma1 + $suma2) <= $consulta['TotalFactura']) {
  if (abonarcxc([$efectivo, $tarjeta, $bioPago, $pagoMovil, $crucesFAC, $NVenta])) {
    if (($suma1 + $suma2) == $consulta['TotalFactura']) {

      cancelarCxC([date('Y-m-d'),$_SESSION['PlantaGas']['NombreUsuario'], $NVenta]);
      $alerta = array(
        "alerta" => "simple",
        "modal" => "#modalPagar",
        "titulo" => "!CxC Nro " . $NVenta . ' PAGADA¡',
        "texto" => "LA CxC FUE PAGADA CON EXITO",
        "tipo" => "success"
      );
      echo json_encode($alerta);
    } else {
      $alerta = array(
        "alerta" => "simple",
        "modal" => "#modalPagar",
        "titulo" => "!ABONO REGISTRADO¡",
        "texto" => "$suma2 Monto Fue Abonado Correcatmente Al Nro Venta $NVenta",
        "tipo" => "success"
      );
      echo json_encode($alerta);
    }
  }
} else {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "¡ABONO NO REGISTRADO!",
    "texto" => "EL ABONO NO PUEDE SER MAYOR AL TOTAL DE LA FACTURA",
    "tipo" => "error"
  );
  echo json_encode($alerta);
}
