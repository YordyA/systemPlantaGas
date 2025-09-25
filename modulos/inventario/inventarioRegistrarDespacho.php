<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "¡OCURRIO UN ERROR INESPERADO!",
    "texto" => "TODOS LOS CAMPOS QUE SON OBLIGATORIOS",
    "tipo" => "error"
  );
  echo json_encode($alerta);
  exit();
}

require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once 'inventarioMain.php';
require_once '../reportes/reportes_main.php';

$reponsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDPlanta = $_SESSION['PlantaGas']['IDPlanta'];
$NroVenta = desencriptar($_GET['id']);
$consulta = reporteFacturasPorNroVenta([$IDPlanta, $NroVenta]);
if ($consulta->rowCount() <= 0) { // Cambié < 0 por <= 0
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => "VENTA NO REGISTRADA",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}
$consulta = $consulta->fetch(PDO::FETCH_ASSOC);

// Obtener datos básicos para el ticket
$ticketData = [
    'empresa' => $_SESSION['PlantaGas']['Planta'],
    'items' => []
];

$Existencia = 0;
$IDInventario = 0;
foreach (almacenLista([$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
  if ($row['Pricipal'] == 1) {
    $Existencia = $row['Cantidad'];
    $IDInventario = $row['IDInventario'];
  }
}

$TotalGas = 0;
$NroVentaPlanta = 0;
foreach (reporteFacturasPorNroVenta([$IDPlanta, $NroVenta]) as $row) {
 $TotalGas += round($row['CapacipadCilindro'] * $row['Cantidad'],2) * 2;
 $NroVentaPlanta = $row['NVentaResumen'];
 $ticketData['items'][] = [
        'descripcion' => $row['DescripcionTipo'] . ' ' . $row['DescripcionProducto'],
        'cantidad' => $row['Cantidad']
    ];
}

if ($Existencia < $TotalGas) {
  $alerta = [
    "alerta"  => "simple",
    "titulo"  => "¡OCURRIO UN ERROR INESPERADO!",
    "texto"   => " EXISTENCIA INSUFICIENTE DE GAS",
    "tipo"    => "error"
  ];
  echo json_encode($alerta);
  exit();
}

$observacion = 'DESPACHO DE GAS POR VENTA NRO ' . $NroVentaPlanta;
almacenCantidadRestar([$TotalGas, $NroVenta]);
MovimientosDeAlmacenRegistrar(
  [
    $IDPlanta,
    date('Y-m-d'),
    2,
    $IDInventario,
    $Existencia,
    $TotalGas,
    $IDInventario,
    $observacion,
    $reponsable
  ]
);

// CORRECCIÓN: Usar $TotalGas en lugar de $total que no existe
$ticketData['total'] = $TotalGas; // Cambié $total por $TotalGas
$ticketData['nro_venta'] = $NroVentaPlanta;

$alerta = [
  "alerta"  => "actualizacion",
  "titulo"  => "¡Despacho Registrado!",
  "texto"   => "Los datos fueron registrados correctamente",
  "tipo"    => "success",
  "ticket_data"  => $ticketData
];
echo json_encode($alerta);
?>