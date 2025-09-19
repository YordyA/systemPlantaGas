<?php
require_once '../main.php';
require_once 'inventarioPlantaMain.php';

foreach (inventarioPlantaLista() as $row) {
  if ($row['Existencia'] === 0) {
    continue;
  }

  inventarioPlantaRegistrarExistencia(
    [
      date('Y-m-d'),
      $row['IDInvPlanta'],
      $row['Existencia'],
      $row['PrecioCosto']
    ]
  );
}
