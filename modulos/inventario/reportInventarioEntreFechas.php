<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
require_once 'inventarioMain.php';

$del = limpiarCadena($_GET['d']);
$hasta = limpiarCadena($_GET['h']);
$IDPlanta = $_SESSION['PlantaGas']['IDPlanta'];
$html = '';
foreach (reportInventarioMovimientosEntreFechas([
    $del, $IDPlanta, 
    $del, $hasta, $IDPlanta, 
    $del, $hasta, $IDPlanta, 
    $del, $IDPlanta, 
    $del, $hasta, $IDPlanta, 
    $del, $hasta, $IDPlanta, 
    $del, $hasta, $IDPlanta]) as $row) {

    $html .= '<tr>'; // Abre una nueva fila
    $html .= '<td>' . htmlspecialchars($row['DescripcionAlmacen']) . '</td>'; // Descripción Almacén
    $html .= '<td>' . htmlspecialchars($row['InventarioInicial']) . '</td>'; // Inicial
    $html .= '<td>' . htmlspecialchars($row['Entradas']) . '</td>'; // Entradas
    $html .= '<td>' . htmlspecialchars($row['Salidas']) . '</td>'; // Salidas
    $html .= '<td>' . htmlspecialchars($row['InventarioFinal']) . '</td>'; // Final
    $html .= '</tr>'; // Cierra la fila
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
