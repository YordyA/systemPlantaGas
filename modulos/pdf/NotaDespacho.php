<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';

// Incluir la librería de ESC/POS
require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

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
    WHERE facturasresumen.IDResumenVenta = ? AND  facturasresumen.IDSucursal = ?
');
$consulta->execute([$NroVenta,$_SESSION['PlantaGas']['IDPlanta']]);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

if (empty($consulta)) {
    die('Venta no encontrada');
}

// Crear un conector para la impresora
// Local (Windows)
$connector = new WindowsPrintConnector("POS-58C");

// Red
// $connector = new NetworkPrintConnector("192.168.1.100", 9100);

// Crear la instancia de la impresora
$printer = new Printer($connector);

try {
    // Imprimir título
    $printer->setJustification(Printer::JUSTIFY_CENTER); // ✅ en lugar de setAlignment
    $printer->setEmphasis(true);
    $printer->text($_SESSION['PlantaGas']['Planta']. "\n");
    $printer->text("NOTA DE DESPACHO DE GAS\n");
    $printer->setEmphasis(false);
    $printer->feed(1);

    // Número de venta
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("No. VENTA: " . $consulta[0]['NVentaResumen'] . "\n");
    $printer->feed(1);

    // Cliente
    $printer->text("CLIENTE: " . utf8_decode($consulta[0]['NombreCliente']) . "\n");
    $printer->feed(1);

    // Encabezado de ítems
    $printer->text(str_pad("DESCRIPCION", 15) . str_pad("CANT", 10) . "LTS\n");
    $printer->text(str_repeat("-", 32) . "\n");

    // Ítems
    $total = 0;
    $totalGas = 0;

    foreach ($consulta as $row) {
        $descripcion = $row['DescripcionProducto'];

        // Acortar si excede 20 caracteres
        if (strlen($descripcion) > 20) {
            $descripcion = substr($descripcion, 0, 17) . '...';
        }

        $cantidad = number_format($row['Cantidad']);
        $lts = number_format(($row['Cantidad'] * $row['CapacipadCilindro']) * 2);

        $printer->text(str_pad($descripcion, 15) . str_pad($cantidad, 10) . $lts . "\n");

        $total += $row['Cantidad'];
        $totalGas +=   round(($row['Cantidad'] * $row['CapacipadCilindro']) * 2);
    }

    // Separador
    $printer->text(str_repeat("-", 32) . "\n");

$printer->text(str_pad("", 15) . str_pad( number_format($total, 0, ',', '.'), 10) . number_format($totalGas, 0, ',', '.') . "\n");


$printer->feed(2);


    $printer->feed(2);

    // Cortar papel
    $printer->cut();
} finally {
    // Siempre cerrar
    $printer->close();
}
