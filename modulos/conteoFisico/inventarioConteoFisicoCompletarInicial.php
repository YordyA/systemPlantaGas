<?php
require_once '../main.php';
require_once 'inventarioConteo.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once '../inventarioPlanta/inventarioPlantaMain.php';


$N_Conteo =  N_Conteo([$_SESSION['PlantaGas']['IDPlanta']]);
foreach ($_SESSION['conteo'] as $row) {

  if ($row['cantidad'] == 0) {
    $inventario = 0;
    $dif = -1  *  $row['existencia'];
  } else {
    $inventario = $row['cantidad'];
    $dif =  $row['diferencia'];
  }
  ActualizarExistencia([$inventario, $row['id_producto'], $_SESSION['PlantaGas']['IDPlanta']]);
  RegistrarConteoFisico(
    [
      $_SESSION['PlantaGas']['IDPlanta'],
      $N_Conteo,
      date('Y-m-d'),
      $row['id_producto'],
      $row['existencia'],
      $inventario,
      $dif,
      $_SESSION['PlantaGas']['nombreUsuario'],
      0
    ]
  );

  if ($dif < 0) {
    inventarioPlantaRegistrarMovimiento(
      [
        date('Y-m-d'),
        2,
        $row['id_producto'],
        $row['existencia'],
        $inventario,
        $row['id_producto'],
        'APERTURA DE INVENTARIO',
       $_SESSION['PlantaGas']['nombreUsuario']
      ]
    );
  } elseif ($dif > 0) {
    inventarioPlantaRegistrarMovimiento(
      [
        date('Y-m-d'),
        1,
        $row['id_producto'],
        $row['existencia'],
        $inventario,
        $row['id_producto'],
        'APERTURA DE INVENTARIO',
        $_SESSION['PlantaGas']['nombreUsuario']
      ]
    );
  }
}

$_SESSION['PlantaGas']['PesajeInicial'] = date('Y-m-d');

unset($_SESSION['conteo']);
echo json_encode([true, encriptar($N_Conteo)]);
