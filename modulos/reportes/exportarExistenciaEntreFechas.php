<?php
require_once '../main.php';
require_once 'reportes_main.php';
require_once '../sessionStart.php';

$inicio = $_GET['del'];
$final = $_GET['hasta'];
$id = $_SESSION['PlantaGas']['IDPlanta'];


$result_salidas = SumaSalidasDeInventario([$inicio, $final, $id]);
$result_entradas = SumaEntradasDeInventario([$inicio, $final, $id]);


$result_inicial = ConteoFisicoInicial([$inicio, $id]);
// Combinar los resultados en un solo arreglo asociativo
$combined_results = array();
foreach ($result_salidas as $salida) {
  $codigo_articulo = $salida['CodigoArticulo'];
  $descripcion_articulo = $salida['DescripcionArticulo'];
  $salidas = $salida['salidas'];
  $entradas = 0;
  $conteo = 0;
  $operacion = 0;
  $combined_results[$codigo_articulo] = array(
    'descripcion_articulo' => $descripcion_articulo,
    'salidas' => $salidas,
    'entradas' => $entradas,
    'conteo' => $conteo,
    'operacion' => $operacion
  );
}

foreach ($result_entradas as $entrada) {
  $codigo_articulo = $entrada['CodigoArticulo'];
  $descripcion_articulo = $entrada['DescripcionArticulo'];
  $entradas = $entrada['entradas'];
  if (isset($combined_results[$codigo_articulo])) {
    $combined_results[$codigo_articulo]['entradas'] = $entradas;
  } else {
    $combined_results[$codigo_articulo] = array(
      'descripcion_articulo' => $descripcion_articulo,
      'salidas' => 0,
      'entradas' => $entradas,
      'conteo' => 0,
      'operacion' => 0
    );
  }
}

foreach ($result_inicial as $conteo) {
  $codigo_articulo = $conteo['CodigoArticulo'];
  $descripcion_articulo = $conteo['DescripcionArticulo'];
  $conteo = $conteo['inicial'];
  if (isset($combined_results[$codigo_articulo])) {
    $combined_results[$codigo_articulo]['conteo'] = $conteo;
    // Calcular la operación
    $salidas = $combined_results[$codigo_articulo]['salidas'];
    $entradas = $combined_results[$codigo_articulo]['entradas'];
    $operacion = $conteo + $entradas - $salidas;
    $combined_results[$codigo_articulo]['operacion'] = $operacion;
  } else {
    $combined_results[$codigo_articulo] = array(
      'descripcion_articulo' => $descripcion_articulo,
      'salidas' => 0,
      'entradas' => 0,
      'conteo' => $conteo,
      'operacion' => 0
    );
  }
}


$tabla = '';


foreach ($combined_results as $codigo_articulo => $data) {


  $tabla  .= '<tr>';
  $tabla  .= '<td>' . htmlspecialchars($codigo_articulo) .    '</td>';
  $tabla  .= '<td>' . htmlspecialchars($data['descripcion_articulo']) .    '</td>';
  $tabla  .= '<td>' . htmlspecialchars(number_format($data['conteo'], 3)) .    '</td>';
  $tabla  .= '<td>' . htmlspecialchars(number_format($data['entradas'], 3))  .    '</td>';
  $tabla  .= '<td>' . htmlspecialchars(number_format($data['salidas'], 3)) .    '</td>';
  $tabla  .= '<td>' . htmlspecialchars(number_format($data['operacion'], 3)) .    '</td>';
  $tabla  .= '</tr>';
}


echo json_encode($tabla, JSON_UNESCAPED_UNICODE);
