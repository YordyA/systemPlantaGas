<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../conteoFisico/inventarioConteo.php';
require_once '../sessionStart.php';
require_once '../inventario/inventarioMain.php';

if (isset($_SESSION['carritoVenta']['productos'])) {
    $i = LimpiarCadena($_GET['i']);
    $cantidad = LimpiarCadena($_GET['cantidad']);
    
    if ($i == '' || $cantidad == '') {
        echo json_encode(false);
        exit();
    }
    
    // Verificar que el índice existe en el carrito
    if (!isset($_SESSION['carritoVenta']['productos'][$i])) {
        echo json_encode(false);
        exit();
    }
    
    $consulta = verificarProductoIDVenta($_SESSION['carritoVenta']['productos'][$i]['IDproducto'])->fetch(PDO::FETCH_ASSOC);
    
    // Verificar si la consulta devolvió resultados
    if (!$consulta) {
        echo json_encode(false);
        exit();
    }
    


    // Validación de capacidad del cilindro
    // Corregí la lógica: la existencia debe ser mayor o igual al doble de la capacidad
    $capacidadValida = ($Existencia >= ($consulta['CapacipadCilindro'] * 2));
    
    // Corregí: usar PrecioVenta consistentemente en ambos casos
    $Precio = $consulta['TipoMoneda'] == 1 ? 
        round(floatval($consulta['PrecioVenta'] * $usd), 2) : 
        round(floatval($consulta['PrecioVenta']), 2);
    
        // Actualizar el precio en caso de que haya cambiado
        $_SESSION['carritoVenta']['productos'][$i]['precio'] = $Precio;
        $_SESSION['carritoVenta']['productos'][$i]['cantidad'] = round(floatval($cantidad), 3);
        $_SESSION['carritoVenta']['productos'][$i]['subtotal'] = round(floatval($Precio * $cantidad), 2);
        echo json_encode(true);
} else {
    echo json_encode(false);
    exit();
}