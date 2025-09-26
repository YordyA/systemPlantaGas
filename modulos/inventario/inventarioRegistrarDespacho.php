<?php
// Agregar headers para JSON y manejo de CORS
header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['id']) || $_GET['id'] == '') {
  $alerta = array(
    "alerta" => "simple",
    "titulo" => "¡OCURRIO UN ERROR INESPERADO!",
    "texto" => "ID de venta no proporcionado",
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

try {
    $reponsable = $_SESSION['PlantaGas']['nombreUsuario'];
    $IDPlanta = $_SESSION['PlantaGas']['IDPlanta'];
    $NroVenta = desencriptar($_GET['id']);
    
    // Verificar que la desencriptación fue exitosa
    if (!$NroVenta || $NroVenta == '') {
        throw new Exception("Error al desencriptar el ID de venta");
    }
    
    $consulta = reporteFacturasPorNroVenta([$IDPlanta, $NroVenta]);
    if ($consulta->rowCount() <= 0) {
        $alerta = [
            "alerta"  => "simple",
            "titulo"  => "¡VENTA NO ENCONTRADA!",
            "texto"   => "La venta especificada no existe en el sistema",
            "tipo"    => "error"
        ];
        echo json_encode($alerta);
        exit();
    }
    
    // Obtener datos básicos para el ticket
    $ticketData = [
        'empresa' => $_SESSION['PlantaGas']['Planta'],
        'items' => []
    ];

    $Existencia = 0;
    $IDInventario = 0;
    
    // Obtener inventario principal
    $inventario = almacenLista([$_SESSION['PlantaGas']['IDPlanta']]);
    if (!$inventario) {
        throw new Exception("Error al obtener el inventario");
    }
    
    foreach ($inventario as $row) {
        if ($row['Pricipal'] == 1) {
            $Existencia = $row['Cantidad'];
            $IDInventario = $row['IDInventario'];
            break;
        }
    }

    $TotalGas = 0;
    $NroVentaPlanta = 0;
    
    // Calcular total de gas requerido
    $facturas = reporteFacturasPorNroVenta([$IDPlanta, $NroVenta]);
    if (!$facturas) {
        throw new Exception("Error al obtener datos de la factura");
    }
    
    foreach ($facturas as $row) {
        $TotalGas += round($row['CapacipadCilindro'] * $row['Cantidad'], 2) * 2;
        $NroVentaPlanta = $row['NVentaResumen'];
        $ticketData['items'][] = [
            'descripcion' => $row['DescripcionTipo'] . ' ' . $row['DescripcionProducto'],
            'cantidad' => $row['Cantidad']
        ];
    }

    if ($Existencia < $TotalGas) {
        $alerta = [
            "alerta"  => "simple",
            "titulo"  => "¡EXISTENCIA INSUFICIENTE!",
            "texto"   => "Existencia actual: " . $Existencia . " kg. Se requieren: " . $TotalGas . " kg",
            "tipo"    => "error"
        ];
        echo json_encode($alerta);
        exit();
    }

    $observacion = 'DESPACHO DE GAS POR VENTA NRO ' . $NroVentaPlanta;
    
    // Realizar el despacho
    $resultado = almacenCantidadRestar([$TotalGas, $IDInventario]);

    $resultadoMovimiento = MovimientosDeAlmacenRegistrar([
        $IDPlanta,
        date('Y-m-d'),
        2,
        $IDInventario,
        $Existencia,
        $TotalGas,
        $IDInventario,
        $observacion,
        $reponsable
    ]);
    
    if (!$resultadoMovimiento) {
        throw new Exception("Error al registrar el movimiento de almacén");
    }

    $ticketData['total'] = $TotalGas;
    $ticketData['nro_venta'] = $NroVentaPlanta;

    $alerta = [
        "alerta"  => "actualizacion",
        "titulo"  => "¡Despacho Registrado!",
        "texto"   => "Los datos fueron registrados correctamente. Despachado: " . $TotalGas . " kg",
        "tipo"    => "success",
        "ticket_data"  => $NroVenta
    ];
    echo json_encode($alerta);
    
} catch (Exception $e) {
    $alerta = [
        "alerta"  => "simple",
        "titulo"  => "¡ERROR EN EL PROCESO!",
        "texto"   => $e->getMessage(),
        "tipo"    => "error"
    ];
    echo json_encode($alerta);
}
?>