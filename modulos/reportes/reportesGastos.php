<?php
require_once '../main.php';
require_once 'reportes_main.php';
require_once '../sessionStart.php';

$del = LimpiarCadena($_GET['d']);
$hasta = LimpiarCadena($_GET['h']);

//* ARRAY DE GASTO
$tipoGastoArray = [
    1 => 'COMPRAS',
    3 => 'COMBUSTIBLE',
    2 => 'CAJA CHICA',
    4 => 'CONSUMO',
    5 => 'TRANSFERENCIA GANADO HATOS',
    6 => 'VIATICOS',
    7 => 'COSTO MATADERO',
    8 => 'COSTO PLANTA LACTEOS',
    9 => 'COSTO TERCEROS',
    10 => 'AYUDAS MEDICA',
    11 => 'MAQUINARIA (UTM)',
    12 => 'FLETES (UTM)',
    13 => 'CONTRATISTAS',
    14 => 'AYUDAS MEDICAS TRASLADO',
    15 => 'PLANTA ALIMENTOS',
    16 => 'ACTIVOS (DEPRECIACIONES)',
    17 => 'PERDIDA (ACTIVO)',
    18 => 'FLETES TERCEROS',
    19 => 'MATERIA PRIMA',
    20 => 'PRODUCTO PARA LA VENTA',
    21 => 'LIQUIDACIONES',
    22 => 'UTILIDADES',
    23 => 'BONOS',
    24 => 'COMPRAS CAJA FRIGORIFICO CAJA',
    26 => 'ARRIENDO',
    27 => 'NOMINA',
    28 => 'TRANSFERENCIA ENTRE FRIGORIFICOS'
];

$arrayIDSucursal = [
    '1' => '32',
    '2' => '33',
    '3' => '35',
    '4' => '36',
    '5' => '34',
    '6' => '39',
    '7' => '40'
];

$tabla = '';
foreach (reportGastoAdministracion([$del, $hasta, $arrayIDSucursal[$_SESSION['PlantaGas']['IDPlanta']]]) as $row) {
    $tabla  .= '<tr>';
    $tabla  .= '<td>' . $row['FechaGasto'] . '</td>';
    $tabla  .= '<td>' . $row['ConceptoGasto'] . '</td>';
    $tabla  .= '<td>' . $row['NroFacturaComprobante'] . '</td>';
    $tabla  .= '<td>' . number_format($row['MontoTotalGastoUSD'], 2) . '</td>';
    $tabla  .= '<td>' . $tipoGastoArray[$row['IDTipoGasto']] . '</td>';
  }

echo json_encode($tabla, JSON_UNESCAPED_UNICODE);