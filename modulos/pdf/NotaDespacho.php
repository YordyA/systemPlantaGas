<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';


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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirigiendo al ticket...</title>
</head>
<body>
    <form id="formulario" action="http://localhost/ticket/ticket.php" method="POST">
        <input type="hidden" name="planta" value="<?php echo htmlspecialchars($_SESSION['PlantaGas']['Planta']); ?>">
        <input type="hidden" name="venta" value="<?php echo $consulta[0]['NVentaResumen']; ?>">
        <input type="hidden" name="cliente" value="<?php echo htmlspecialchars($consulta[0]['NombreCliente']); ?>">
        <input type="hidden" name="items" value='<?php echo json_encode($ticket['items']); ?>'>
        <input type="hidden" name="total_cant" value="<?php echo $ticket['total_cant']; ?>">
        <input type="hidden" name="total_lts" value="<?php echo $ticket['total_lts']; ?>">
    </form>
    
  <script>
    window.onload = function() {
      formulario.submit()
    }
  </script>
</body>
</html>