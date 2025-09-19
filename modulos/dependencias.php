<?php
date_default_timezone_set('America/Caracas');
const METHOD = 'AES-256-CBC';
const SECRET_KEY = '$ing33218034@';
const SECRET_IV = '9256420';

//* RIF DE LA EMPRESA
const RIF = 'G00000000';
const RAZONSOCIAL = 'APURE GAS';
const DOMICILIOFISCAL = '';

//* LIMPIAR CADENA DE TEXTO
function limpiarCadena($cadena)
{
  $cadena = trim($cadena);
  $cadena = stripslashes($cadena);
  $cadena = str_ireplace("<script>", "", $cadena);
  $cadena = str_ireplace("</script>", "", $cadena);
  $cadena = str_ireplace("<script src>", "", $cadena);
  $cadena = str_ireplace("<script type=>", "", $cadena);
  $cadena = str_ireplace("SELECT * FROM", "", $cadena);
  $cadena = str_ireplace("DELETE FROM", "", $cadena);
  $cadena = str_ireplace("INSERT INTO", "", $cadena);
  $cadena = str_ireplace("DROP TABLE", "", $cadena);
  $cadena = str_ireplace("DROP DATABASE", "", $cadena);
  $cadena = str_ireplace("SHOW DATABASES", "", $cadena);
  $cadena = str_ireplace("<?php", "", $cadena);
  $cadena = str_ireplace("--", "", $cadena);
  $cadena = str_ireplace("^", "", $cadena);
  $cadena = str_ireplace("<", "", $cadena);
  $cadena = str_ireplace("[", "", $cadena);
  $cadena = str_ireplace("]>", "", $cadena);
  $cadena = str_ireplace("==", "", $cadena);
  $cadena = str_ireplace(";", "", $cadena);
  $cadena = str_ireplace("::", "", $cadena);
  $cadena = trim($cadena);
  $cadena = stripslashes($cadena);
  return $cadena;
}

//* ENCRIPTAR CADENA DE TEXTO
function encriptar($string)
{
  $output = false;
  $key = hash('sha256', SECRET_KEY);
  $iv = substr(hash('sha256', SECRET_IV), 0, 16);
  $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
  $output = base64_encode($output);
  return $output;
}

//* DESENCRIPTAR CADENAS DE TEXTO
function desencriptar($string)
{
  $key = hash('sha256', SECRET_KEY);
  $iv = substr(hash('sha256', SECRET_IV), 0, 16);
  $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
  return $output;
}

//* FUNCIÓN DE RELLENAR CEROS A LA IZQUIERDA
function generarCeros($numero, $longitud)
{
  return str_pad($numero, $longitud, '0', STR_PAD_LEFT);
}

//* RENDERIZAR INPUT
function renderInput($label, $name, $type, $value, $required = true, $rows = 1, $attr = '')
{
  $requiredAttr = $required ? 'required' : '';
  $fieldHtml = "<div class=\"mb-3\">
                  <label class=\"form-label text-dark\">$label</label>";
  if ($type == 'textarea') {
    $fieldHtml .= "<textarea class=\"form-control\" rows=\"$rows\" name=\"$name\" $requiredAttr>$value</textarea>";
  } else {
    $fieldHtml .= "<input type=\"$type\" class=\"form-control form-control-lg\" name=\"$name\" value=\"$value\" $requiredAttr $attr>";
  }
  $fieldHtml .= "</div>";
  return $fieldHtml;
}

//* VALIDAR CLIENTE
function validarCliente($cadena)
{
  // Convertir la primera letra en mayúscula
  $primerCaracter = strtoupper($cadena[0] ?? '');
  // Validar si es un RIF válido (J o G seguido de 9 dígitos)
  if (preg_match('/^[JG]\d{9}$/', $cadena)) {
    return false;
  }
  // Validar si es un formato de cliente válido (V, E, J, G, P, C)
  if (preg_match('/^[VEJGPC]/', $cadena)) {
    return false;
  }
  return true; // Si no cumple ninguna condición, retorna true
}

//* GENERAR COLORES ALEATORIOS
function generarColores($alpha)
{
  $r = rand(0, 255);
  $g = rand(0, 255);
  $b = rand(0, 255);
  return "rgba($r, $g, $b, $alpha)";
}

//* ARRAY DE ESTADOS
$estadosArray = [
  0 => '<span class="status-btn close-btn">DESACTIVADO</span>',
  1 => '<span class="status-btn success-btn">ACTIVO</span>'
];

//* ARRAY TIPO DE MOVIMIENTO DE INVENTARIO
$tipoMovArray = [
  1 => '<span class="status-btn success-btn">ENTRADA</span>',
  2 => '<span class="status-btn close-btn">SALIDA</span>'
];

$arrayEstadoProduccion = [
  '0' => '<span class="status-btn close-btn">ANULADO</span>',
  '2' => '<span class="status-btn warning-btn">PROCESO</span>',
  '1' => '<span class="status-btn success-btn">ACTIVO</span>'
];

$arrayEstadoDespacho = [
  '0' => '<span class="status-btn close-btn">ANULADO</span>',
  '1' => '<span class="status-btn success-btn">ACTIVO</span>'
];


$arrayTipoCisternaVehiculo = [
  '2' => '<span class="status-btn warning-btn">TERCEROS</span>',
  '1' => '<span class="status-btn success-btn">PROPIO</span>'
];

//* FECHA HORA
$fechaHoraModificacion = date('Y-m-d h:i:s A');

//* ARRAY DE VISTAS BLOQUEADAS POR FALTA DEL PESAJE FINAL
$arrayViewConteo = [
  'produccionRegistrar' => 0
];
