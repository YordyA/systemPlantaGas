<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';

// Incluir la librería de ESC/POS
require_once '../vendor/autoload.php';

// Obtener el número de venta desde la URL
$NroVenta = isset($_GET['id']) ? $_GET['id'] : null;
if (!$NroVenta) {
    die('Número de venta no especificado');
}

// Consultar los datos de la venta
$consulta = conexion()->prepare('SELECT *
    FROM facturasresumen
    INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
    INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
    INNER JOIN productos ON facturasdetalle.IDProducto = productos.IDProducto
    INNER JOIN tipo_productos ON productos.IDTipoProducto = tipo_productos.IDTipo
    WHERE facturasresumen.IDResumenVenta = ? AND facturasresumen.IDSucursal = ?
');
$consulta->execute([$NroVenta, $_SESSION['PlantaGas']['IDPlanta']]);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

if (empty($consulta)) {
    die('Venta no encontrada');
}

// Construir el array de datos del ticket
$ticket = [
    "planta"   => $_SESSION['PlantaGas']['Planta'],
    "venta"    => $consulta[0]['NVentaResumen'],
    "cliente"  => $consulta[0]['NombreCliente'],
    "items"    => [],
    "total_cant" => 0,
    "total_lts"  => 0
];

foreach ($consulta as $row) {
    $descripcion = $row['DescripcionProducto'];
    if (strlen($descripcion) > 20) {
        $descripcion = substr($descripcion, 0, 17) . '...';
    }

    $cantidad = (int) $row['Cantidad'];
    $lts      = (int) round(($row['Cantidad'] * $row['CapacipadCilindro']) * 2);

    $ticket['items'][] = [
        "descripcion" => $descripcion,
        "cantidad"    => $cantidad,
        "lts"         => $lts
    ];

    $ticket["total_cant"] += $cantidad;
    $ticket["total_lts"]  += $lts;
}

// Enviar los datos a ticket.php mediante POST
$url = "http://localhost/ticket/ticket.php";
$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($ticket)
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $result; // Opcional: mostrar respuesta
