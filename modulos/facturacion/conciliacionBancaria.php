<?php
require_once '../main.php';
require_once '../dependencias.php';
$cedulaPagador = LimpiarCadena($_POST['cedula']);
$telefonoPagador = LimpiarCadena($_POST['telefono']);
$referencia = LimpiarCadena($_POST['referencia']);
$monto = LimpiarCadena($_POST['cantidad']);
$fechaPago = LimpiarCadena($_POST['fecha']);
$banco = LimpiarCadena($_POST['banco']);

if ($cedulaPagador == '' || $telefonoPagador == '' || $referencia == '' || $fechaPago == '' || $banco == '') {
  echo json_encode([false, 'TODO LOS CAMPOS SON REQUERIDOS']);
  exit();
}

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_PORT => "443",
  CURLOPT_URL => "https://bdvconciliacion.banvenez.com:443/getMovement",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"cedulaPagador\": \"$cedulaPagador\",\n  \"telefonoPagador\": \"$telefonoPagador\",\n  \"telefonoDestino\": \"04145790251\",\n  \"referencia\": \"$referencia\",\n  \"fechaPago\": \"$fechaPago\",\n  \"importe\": \"$monto\",\n  \"bancoOrigen\": \"$banco\"\n}",
  CURLOPT_HTTPHEADER => [
    "X-API-Key: C5AD11C3D276B8F746FA1CA21BBF5FDB",
    "content-type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
  // echo "cURL Error #:" . $err;
  echo json_encode([false, 'BDV NO RESPONDE']);
  exit();
} else {
  $response =  json_decode($response, true);
}

if ($response['code'] != 1000) {
  echo json_encode([false, 'PAGO MOVIL NO CONFIRMADO']);
  exit();
}

echo json_encode([true, 'PAGO MOVIL CONFIRMADO']);
