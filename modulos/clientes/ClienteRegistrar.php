<?php
require_once '../main.php';
require_once 'ClienteMain.php';

function validarRif($cadena)
{
    $primerCaracter = strtoupper(substr($cadena, 0, 1));
    if (in_array($primerCaracter, ['G', 'J'])) {
        $validarCaracteres = explode($primerCaracter, $cadena);
        if (strlen($validarCaracteres[1]) == 9) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

$rif = strtoupper(LimpiarCadena($_POST['rif']));
$nombre = strtoupper(LimpiarCadena($_POST['nombre']));
$apellido = strtoupper(LimpiarCadena($_POST['apellido']));
$cliente = $nombre . ' ' . $apellido;

if ($rif == '' || $nombre == '' || $apellido == '') {
    $alerta = array(
        "alerta" => "simple",
        "titulo" => "¡Ocurrio un error inesperado!",
        "texto" => "No has llenado todos los campos que son obligatorios",
        "tipo" => "error"
    );
    echo json_encode($alerta);
    exit();
}

if (verificarFormatoCliente($rif) == false) {
    $alerta = array(
        "alerta" => "simple",
        "titulo" => "¡Ocurrio un error inesperado!",
        "texto" => "No has llenado todos los campos que son obligatorios",
        "tipo" => "error"
    );
    echo json_encode($alerta);
    exit();
}

if (validarRif($rif)) {
    $alerta = array(
        "alerta" => "rif",
        "titulo" => "¡Ocurrio un error inesperado!",
        "texto" => "El RIF debe contener Diez Caracteres",
        "tipo" => "error"
    );
    echo json_encode($alerta);
    exit();
}

if (verificarClienteDucumentoFiscal($rif)->rowCount() > 0) {
    $alerta = array(
        "alerta" => "simple",
        "titulo" => "¡Ocurrio un error inesperado!",
        "texto" => "La Cedula/Rif ya se encuentra registrado",
        "tipo" => "error"
    );
    echo json_encode($alerta);
    exit();
}

if (registrarCliente([$rif, $cliente]) == 1) {
    $alerta = [
        "alerta" => "limpiar",
        "titulo" => "¡CLIENTE REGISTRADO!",
        "texto" => "El cliente fue registrado",
        "tipo" => "success"
    ];
    echo json_encode($alerta);
}
