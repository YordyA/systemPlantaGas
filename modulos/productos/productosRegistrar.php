<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'cisternasMain.php';

$precioUnitario = limpiarCadena($_POST['precioUnitario']);
$Tipo = desencriptar(limpiarCadena($_POST['IDTipoProducto']));
$Alicuota = desencriptar(limpiarCadena($_POST['IDAlicuota']));
$Codigo = limpiarCadena($_POST['codigoProducto']);
$Descripcion = limpiarCadena($_POST['descripcionProducto']);

if ($precioUnitario == '' ||  $Tipo == '' || $Alicuota == '' || $Descripcion == '' || $codigo == '' || $capacidad == '' ) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo"    => "error"  
  ];
  echo json_encode($alerta);
  exit();
}

ProdutosRegistrar([$Tipo, $empresaC, $modelo, $capacidad]);

$alerta = [
  "alerta"  => "limpiar",
  "titulo"  => "¡CISTERNA REGISTRADA!",
  "texto"   => "LA CISTERNA HA SIDO REGISTRADA CON EXITO",
  "tipo"    => "success"
];
echo json_encode($alerta);
