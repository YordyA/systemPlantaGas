<?php
// Validación simplificada pero efectiva
if (empty($_GET['id']) || empty($_POST['IDInvProduccion']) || empty($_POST['cantUtilizar']) || empty($_POST['totalCosto'])) {
  echo json_encode([
    "alerta" => "simple",
    "titulo" => "¡ERROR!",
    "texto" => "Faltan datos obligatorios",
    "tipo" => "error"
  ]);
  exit();
}

require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once 'produccionMain.php';
require_once '../inventarioProduccion/inventarioProduccionMain.php';

// Datos básicos
$fechaActual = date('Y-m-d');
$responsable = $_SESSION['PlantaGas']['nombreUsuario'];
$IDProduccionResumen = desencriptar($_GET['id']);

// Verificar producción
$produccion = produccionConsultarXID([$IDProduccionResumen])->fetch(PDO::FETCH_ASSOC);
if (!$produccion) {
  echo json_encode([
    "alerta" => "simple",
    "titulo" => "¡ERROR!",
    "texto" => "Producción no encontrada",
    "tipo" => "error"
  ]);
  exit();
}

$conceptoRetiro = 'PRODUCCION LOTE NRO ' . generarCeros($produccion['NroLote'], 5);

$conexion = conexion();
// Iniciar transacción
$conexion->beginTransaction();

try {
  // Calcular costo total previo
  $totalCostoProduccion = 0;
  $stmt = $conexion->prepare("SELECT CantidadUtilizada, CostoUtilizado FROM produccion_detalles WHERE IDProduccionResumen = ?");
  $stmt->execute([$IDProduccionResumen]);
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $totalCostoProduccion += round($row['CantidadUtilizada'] * $row['CostoUtilizado'], 3);
  }

  // PASO 1: VALIDACIÓN COMPLETA ANTES DE PROCESAR
  $errores = [];
  $itemsParaProcesar = [];

  foreach ($_POST['IDInvProduccion'] as $indice => $id) {
    $idProducto = desencriptar($id);
    $tipoProducto = desencriptar($_POST['Tipo'][$indice]);
    $cantidad = $_POST['cantUtilizar'][$indice];
    $costoTotal = $_POST['totalCosto'][$indice];

    if (!is_numeric($cantidad)) {
      $errores[] = "Cantidad inválida para el producto en posición " . ($indice + 1);
      continue;
    }

    if ($tipoProducto != 3) { // Solo validar existencia para materias primas
      $materiaPrima = inventarioProduccionVerificarXID([$idProducto])->fetch(PDO::FETCH_ASSOC);

      if ($materiaPrima['Existencia'] < $cantidad) {
        $errores[] = "Existencia insuficiente: " . $materiaPrima['DescripcionProducto'] .
          " (Necesario: $cantidad, Disponible: " . $materiaPrima['Existencia'] . ")";
        continue;
      }
    }
  }

  // Si hay errores, cancelar todo
  if (!empty($errores)) {
    throw new Exception("Errores de validación:\n- " . implode("\n- ", $errores));
  }
} catch (Exception $e) {
  $conexion->rollBack();
  echo json_encode([
    "alerta" => "simple",
    "titulo" => "¡ERROR!",
    "texto" => $e->getMessage(),
    "tipo" => "error"
  ]);
}

$totalA = 0;
foreach ($_POST['IDInvProduccion'] as $indice => $id) {
  $idProducto = desencriptar($id);
  $tipoProducto = desencriptar($_POST['Tipo'][$indice]);
  $cantidad = $_POST['cantUtilizar'][$indice];
  $costoTotal = $_POST['totalCosto'][$indice];

  // Validar cantidad
  if (!is_numeric($cantidad)) {
    throw new Exception("Cantidad inválida en índice $indice");
  }

  // Para materias primas (tipo != 3)
  if ($tipoProducto != 3) {
    $materiaPrima = inventarioProduccionVerificarXID([$idProducto])->fetch(PDO::FETCH_ASSOC);
    inventarioProduccionRetirarExistencia([$cantidad, $idProducto]);
    inventarioProduccionRegistrarMovimiento([
      $fechaActual,
      2,
      $idProducto,
      $materiaPrima['Existencia'],
      $cantidad,
      $idProducto,
      $conceptoRetiro,
      $responsable
    ]);

    $costoUnitario = $materiaPrima['CostoUnitario'];
  } else {
    if ($cantidad > 0) {
      $costoUnitario = round($costoTotal / $cantidad, 4);
    }
  }

  // Registrar detalle una sola vez
  if ($cantidad > 0) {
    $consulta = IDProductodDetallesProcesos([$IDProduccionResumen, $idProducto]);
    if ($consulta->rowCount() != 1) {
      produccionRegistrarDetalle([$IDProduccionResumen, $idProducto, $costoUnitario, $cantidad]);
    }
  }
  $totalA += $costoTotal;
}

// Calcular costo final
$totalVentaSubProductos = 0;
foreach (produccionConsultarSubProductosYPreciosXID([$IDProduccionResumen]) as $row) {
  $totalVentaSubProductos += $row['CantidadTotal'] * $row['PrecioUnitario'];
}

$nuevoCosto = round((($totalCostoProduccion + $totalA) - $totalVentaSubProductos) / $produccion['CantidadProducida'], 2);
produccionActualizarCosto([$nuevoCosto, $responsable, $IDProduccionResumen]);
produccionActualizarEstadoProduccion([1, $responsable . date('d-m-Y H:i:s'), $IDProduccionResumen]);

$conexion->commit();


$datos = $IDProduccionResumen;
$resultadoProduccion = produccionConsultarXID([$datos]);
$resultadoSubproductos = produccionConsultarSubProductosXID([$datos]);

// Array para los productos con su información
$productos_mensaje = [];
$productos_Costos = [];
// Iteramos sobre los resultados de producción
foreach ($resultadoProduccion as $row) {
  // Calculamos el rendimiento
  $rendimiento = 0;
  if ($row['CantidadUtilizada'] > 0) {
    $rendimiento = number_format($row['CantidadUtilizada'] / $row['CantidadProducida'], 2);
  }

  if ($row['IDTipoProducto'] == 1) {
    $productos_mensaje[] = '*Lote:* ' . $row['NroLote'] . "\n" .
      '*Materia Prima:* ' . $row['DescripcionProducto'] . "\n" .
      'Cantidad Utilizada: ' . number_format($row['CantidadUtilizada'], 2) . ' ' . $row['DescripcionUnidadMedida'] . "\n" .
      'Producto Terminado: ' . number_format($row['CantidadProducida'], 2) . ' ' . $row['DescripcionUnidadMedida'] . "\n" .
      'Costo Unitario UND: ' . number_format($nuevoCosto, 2) . ' $' . "\n" .
      'Rendimiento: ' . $rendimiento . '';
  } else {
    $productos_Costos[] =
      '*Item:* ' . $row['DescripcionProducto'] . "\n" .
      'Cantidad Utilizada: ' . number_format($row['CantidadUtilizada'], 2) . ' ' . $row['DescripcionUnidadMedida'] . "\n";
  }
}

// Array para los subproductos con su cantidad
$subproductos_mensaje = [];

// Iteramos sobre los resultados de subproductos
foreach ($resultadoSubproductos as $row) {
  // Generamos el mensaje para cada tipo de subproducto
  $subproductos_mensaje[] = '*Fecha de Producción:* ' . date('d/m/Y', strtotime($row['Fecha'])) . "\n" .
    '_Harina: ' . number_format($row['Harina'], 2) . ' kg_' . "\n" .
    '_Pico: ' . number_format($row['Pico'], 2) . ' kg_' . "\n" .
    '_Barrido: ' . number_format($row['Barrido'], 2) . ' kg_' . "\n" .
    '_Afrecho: ' . number_format($row['Afrecho'], 2) . ' kg_' . "\n" .
    '_Fecula: ' . number_format($row['Fecula'], 2) . ' kg_' . "\n" .
    '_Descarte: ' . number_format($row['Descarte'], 2) . ' kg_' . "\n" .
    '_Impurezas: ' . number_format($row['Impurezas'], 2) . ' kg_' . "\n";
}

// Construimos el mensaje final
$mensaje = '*INFORME DE PRODUCCIÓN PLANTA DE HARINA LA APUREÑA C.A*' . "\n" .
  '' . "\n" .
  implode("\n", $productos_mensaje) . "\n\n" .
  '*Items Costos Asociados:*' . "\n" .
  '' . "\n" .
  implode("\n", $productos_Costos) . "\n" .
  '*Producto Terminado:*' . "\n" .
  '' . "\n" .
  implode("\n", $subproductos_mensaje);

// Números de teléfono a los que enviar el mensaje
$telefonos = ['+584243042184', '+584269388197', '+584163341967', '+584264072217', '+584140217388'];
//$telefonos = ['+584269388197'];
// Parámetros para la solicitud
$params = array(
  'token' => 'aedcc231uu8wfzpv',  // Token de tu instancia
  'to' => implode(',', $telefonos), // Números de teléfono separados por coma
  'body' => $mensaje
);

// Inicializar cURL
$curl = curl_init();

// Configurar la solicitud cURL
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ultramsg.com/instance110370/messages/chat",  // URL de la API
  CURLOPT_RETURNTRANSFER => true,  // Queremos la respuesta como cadena
  CURLOPT_ENCODING => "",  // No especificamos tipo de codificación
  CURLOPT_MAXREDIRS => 10,  // Número máximo de redirecciones
  CURLOPT_TIMEOUT => 30,  // Tiempo de espera máximo
  CURLOPT_SSL_VERIFYHOST => 0,  // No verificar el host SSL (a evitar en producción)
  CURLOPT_SSL_VERIFYPEER => 0,  // No verificar el certificado SSL (a evitar en producción)
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",  // Usamos el método POST
  CURLOPT_POSTFIELDS => http_build_query($params),  // Pasamos los parámetros en formato query string
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded"  // Indicamos el tipo de contenido
  ),
));

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($curl);

// Obtener el código de estado HTTP de la respuesta
$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Verificar si hubo un error en cURL
$err = curl_error($curl);

// Cerrar la sesión de cURL
curl_close($curl);

// Manejo de errores y la respuesta
if ($err) {
  echo "Error cURL: " . $err;
} else {
  // Verificar el código de estado HTTP
  if ($status_code == 200) {
    //echo "Mensaje enviado exitosamente.";
  } else {
    // Si el código no es 200, mostrar el código y la respuesta completa para depuración
    // echo "Error al enviar el mensaje. Código de estado HTTP: " . $status_code . "<br>";
    //echo "Respuesta de la API: " . $response;
  }
}

$alerta = [
  "alerta"  => "volver",
  "titulo"  => "¡ÉXITO!",
  "texto"   => "Costos registrados correctamente",
  "tipo"    => "success"
];
echo json_encode($alerta);
