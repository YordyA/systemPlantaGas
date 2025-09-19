<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../facturacion/FacturacionMain.php';
require_once '../dependencias.php';
if (isset($_GET['']) || isset($_GET[''])) {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "¡Ocurrio un error inesperado!",
    "texto" => "El usuario no existe en el sistema",
    "tipo" => "error"
  );
  echo json_encode($alerta);
  exit();
}

if (eliminarFacturaEnEspera([LimpiarCadena(Desencriptar($_GET['id'])), $_SESSION['PlantaGas']['IDPlanta']])) {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "¡FACTURA ELIMINADA!",
    "texto" => "La factura en espera fue eliminada",
    "tipo" => "success"
  );
  echo json_encode($alerta);
}
