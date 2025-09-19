<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../inventario/inventario_main.php';
require_once 'reportes_main.php';
require_once '../despachosMatadero/despachosMataderoMain.php';

function CostoPlantaLacteos($datos) {
    $sql = conexionPlantaLacteos()->prepare('SELECT
    SUM(SubTotalCosto) AS MontoTotalUSD
FROM
    facturas_resumen
INNER JOIN facturas_detalle ON facturas_resumen.NroDespacho = facturas_detalle.NroDespacho
WHERE
    EstadoFactura != 1 AND FechaDespacho BETWEEN ? AND ? AND IDCliente = ?');
    $sql->execute($datos);
    return $sql;
}

function CostosMataderoAcopio($id)
{
    $sql  = conexionCentroAcopio()->prepare("SELECT ventas.fecha, ventas.cantidad, ventas.precio, ventas.precio_subtotal, unidades_produccion.unidad_produccion, productos.descripcion FROM ventas
INNER JOIN unidades_produccion ON ventas.id_cliente = unidades_produccion.id_unidad_produccion
INNER join productos on ventas.id_producto = productos.id_producto WHERE ventas.fecha BETWEEN ? AND ? AND unidades_produccion.id_unidad_produccion = ? AND ventas.anulado = 0 AND ventas.tipo_despacho = 3");
    $sql ->execute($id);
  return     $sql ->fetchAll(PDO::FETCH_ASSOC);
}

function CostosMataderoCanales($id)
{
    $sql  = conexionMatadero()->prepare("SELECT SUM(despacho_detalle.SubTotalPrecio) as total FROM despacho_detalle INNER JOIN despacho_resumen ON despacho_detalle.NroDespacho = despacho_resumen.NroDespacho
    WHERE despacho_resumen.IDCliente = ? AND despacho_resumen.Fecha BETWEEN ? AND ? AND EstadoDespacho = 2");
    $sql ->execute($id);
  return $sql;
}

function GastosAdministracion($datos)
{
    $reporte = conexionAdministracion()->prepare('SELECT * FROM gastos_administrativo WHERE FechaGasto BETWEEN ? AND ? AND IDCentroCosto = ? AND EstadoGasto = 2');
    $reporte->execute($datos);
    return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

$fecha_inicio = LimpiarCadena($_GET['fecha_inicio']);
$fecha_final = LimpiarCadena($_GET['fecha_final']);
$IDSucursal = $_SESSION['PlantaGas']['IDPlanta'];

$IDSucursalesFrigorificosCompras = array(
  1 => 32,
  2 => 33,
  3 => 35,
  4 => 36,
  5 => 34
);

$IDSucursalesFrigorificosMatadero = array(
  1 => 1056,
  2 => 1057,
  3 => 1058,
  4 => 1059,
  5 => 1060
);

$IDSucursalesFrigorificosMataderoCanales = array(
  1 => 77,
  2 => 78,
  3 => 79,
  4 => 80,
  5 => 81,
  6 => 82,
);


$consultarGastoCompras = estadoResultadoReportCompras(
  [
    $IDSucursalesFrigorificosCompras[$IDSucursal],
    $fecha_inicio,
    $fecha_final
  ]
);

$consultarGastoCombosMatadero = estadoResultadoReportCombos(
  [
    $IDSucursalesFrigorificosMatadero[$IDSucursal],
    $fecha_inicio,
    $fecha_final
  ]
);

$consultarGastoComsumoMatadero = estadoResultadoReportComsumo(
  [
    $IDSucursalesFrigorificosMatadero[$IDSucursal],
    $fecha_inicio,
    $fecha_final
  ]
);

  $TotalCanales = CostosMataderoCanales([$IDSucursalesFrigorificosMataderoCanales[$IDSucursal],$fecha_inicio, $fecha_final])->fetch(PDO::FETCH_ASSOC);
  
function tasas_dolar($datos)
{
  $reporte = conexion()->prepare("SELECT * from tasadolarhistorial WHERE Fecha BETWEEN ? AND ?");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

function facturacion_por_articulo_resumido($datos)
{
  $reporte = conexion()->prepare("SELECT facturasresumen.*, articulosdeinventario.DescripcionArticulo, articulosdeinventario.CodigoArticulo, articulosdeinventario.IDAlicuota,
  SUM(facturasdetalle.Cantidad) as sum_cantidad,
  SUM(facturasdetalle.SubTotal) as sum_total
FROM facturasresumen
  INNER JOIN facturasdetalle ON facturasresumen.NVenta = facturasdetalle.NVenta
  INNER JOIN articulosdeinventario ON facturasdetalle.IDProducto = articulosdeinventario.IDArticulo
WHERE facturasresumen.IDSucursal = ? AND
facturasdetalle.IDSucursal = ?
  AND facturasresumen.Fecha BETWEEN ? AND ?
  AND facturasresumen.Estatus < 2
GROUP BY articulosdeinventario.IDArticulo");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}


//^ REPORTE DE DONACIONES RESUMIDO
function donaciones_resumen($id)
{
  $reportes = conexion()->prepare("SELECT SUM(SubTotal) as Total FROM donacionesdetalle  INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion WHERE 
donacionesresumen.IDSucursal = ? AND donacionesdetalle.IDSucursal= ? AND donacionesresumen.Fecha BETWEEN ? AND ?
  AND donacionesresumen.TipoConsumo = 0 and donacionesresumen.Estatus = 0;");
  $reportes->execute($id);
  return  $reportes->fetch(PDO::FETCH_ASSOC);
}

//^ TOTAL DE CONSUMO
function consumo_resumen($id)
{
  $reportes = conexion()->prepare("SELECT SUM(SubTotal) as Total FROM donacionesdetalle  INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion WHERE 
donacionesresumen.IDSucursal = ? AND donacionesdetalle.IDSucursal= ? AND donacionesresumen.Fecha BETWEEN ? AND ?
  AND donacionesresumen.TipoConsumo = 1 and donacionesresumen.Estatus = 0;");
  $reportes->execute($id);
  return  $reportes->fetch(PDO::FETCH_ASSOC);
}

function donaciones_trabajadores($id)
{
  $reportes = conexion()->prepare("SELECT SUM(SubTotal) as Total FROM donacionesdetalle  INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion WHERE 
donacionesresumen.IDSucursal = ? AND donacionesdetalle.IDSucursal= ? AND donacionesresumen.Fecha BETWEEN ? AND ?
  AND donacionesresumen.TipoConsumo = 2 and donacionesresumen.Estatus = 0;");
  $reportes->execute($id);
  return  $reportes->fetch(PDO::FETCH_ASSOC);
}

$resultadodoaciones = donaciones_resumen([$IDSucursal,$IDSucursal, $fecha_inicio, $fecha_final]);
$resultadoconsumos = consumo_resumen([$IDSucursal,$IDSucursal, $fecha_inicio, $fecha_final]);
$resultadodonacionestrabajadores = donaciones_trabajadores([$IDSucursal, $IDSucursal, $fecha_inicio, $fecha_final]);

$totalComprasUSD = $consultarGastoCompras['TotalUSD'];
$totalComprasBS = $consultarGastoCompras['TotalBS'];
$totalCombos = $consultarGastoCombosMatadero['TotalUSD'];
$totalConsumos=  $consultarGastoComsumoMatadero['TotalUSD'];

$totalProductos = 0;
$totalTransferencias = 0;
$totalNomina = 0;
$totalCajaChica = 0;
$totalCombustible = 0;
$totalMatadero = 0;
$totalAcopio = 0;
$totalLacteos = CostoPlantaLacteos([$fecha_inicio, $fecha_final,  $IDSucursal])->fetch(PDO::FETCH_ASSOC)['MontoTotalUSD'];
$totalTerceros = 0;
$totalAlquiler = 0;
$totalLiquidaciones = 0;
$totalUtilidades = 0;
$totalBonos = 0;
$totaviaticos =0;
foreach (GastosAdministracion([$fecha_inicio, $fecha_final,  $IDSucursalesFrigorificosCompras[$IDSucursal]]) as $rowGastos) {
  if ($rowGastos['IDTipoGasto'] == 27) {
        $totalNomina += $rowGastos['MontoTotalGastoUSD'];
    }elseif ($rowGastos['IDTipoGasto'] == 21) {
        $totalLiquidaciones += $rowGastos['MontoTotalGastoUSD'];
    }elseif ($rowGastos['IDTipoGasto'] == 22) {
        $totalUtilidades += $rowGastos['MontoTotalGastoUSD'];
    }elseif ($rowGastos['IDTipoGasto'] == 23) {
        $totalbonos += $rowGastos['MontoTotalGastoUSD'];
    }elseif ($rowGastos['IDTipoGasto'] == 2) { 
        $totalCajaChica += $rowGastos['MontoTotalGastoUSD'];
    }elseif ($rowGastos['IDTipoGasto'] == 6) {
        $totaviaticos += $rowGastos['MontoTotalGastoUSD'];
    }else if ($rowGastos['IDTipoGasto'] == 26){
        $totalAlquiler += $rowGastos['MontoTotalGastoUSD'];
    }
}

$totaltasas = 0;
$totalregistros = 0;

foreach (tasas_dolar([$fecha_inicio, $fecha_final]) as $row2) {
  $totaltasas += $row2['TasaCambiariaHistorial'];
  $totalregistros++;
}

$promediodolar = round($totaltasas / $totalregistros, 2);
$totalventas = 0;
$totaldolares = 0;
$totalbs = 0;
$totalventasiniva = 0;
$iva = 0;
$totalVentasSinIVA = 0;
$totalIVA = 0;


if($_SESSION['PlantaGas']['IDPlanta'] == 1){

    $rif='G-200112441-1';
    
    }elseif($_SESSION['PlantaGas']['IDPlanta'] == 2){
    
    $rif='G-200112441-2';
    
    }elseif($_SESSION['PlantaGas']['IDPlanta'] == 3){
    
    $rif='G-200112441-3';
    
    }elseif($_SESSION['PlantaGas']['IDPlanta'] == 4){
    
    $rif='G-200112441-4';
    
    }elseif($_SESSION['PlantaGas']['IDPlanta'] == 5){
    
    $rif='G-200112441-5';
    }
    
    
    foreach (CostosMatadero([$fecha_inicio, $fecha_final,$rif]) as $row) {

        $totalMatadero+=$row['precio_subtotal'];
    }

    foreach (CostosMataderoAcopio([$fecha_inicio, $fecha_final,$IDSucursalesFrigorificosMatadero[$IDSucursal]]) as $row) {

        $totalAcopio+=$row['precio_subtotal'];
    }


foreach (facturacion_por_articulo_resumido([$IDSucursal, $IDSucursal, $fecha_inicio, $fecha_final]) as $row) {
  if ($row['IDAlicuota'] == 1) {
    $totalventasiniva = $row['sum_total'];
  } else if ($row['IDAlicuota'] == 2) {
    $totalventasiniva = $row['sum_total'] / 1.16;
    $iva = $row['sum_total'] - $totalventasiniva;
  }

  $totalVentasSinIVA += $totalventasiniva;
  $totalIVA += $iva;
}


///////////////////////////////////////////////////////////////////////////////////////////
$totalSuma = 0;
foreach (inventario_final([$fecha_final, $IDSucursal]) as $row) {
  $valorTotal = $row['Costo'] * $row['Existencia'];
  $totalSuma += $valorTotal;
}

$fecha_anterior = date("Y-m-d", strtotime($fecha_inicio . "-1 day"));

$totalSumaFinal = 0;
foreach (inventario_final([$fecha_anterior, $IDSucursal]) as $roww) {
  $valorTotal = $roww['Costo'] * $roww['Existencia'];
  $totalSumaFinal += $valorTotal;
}
///////////////////////////////////////////////////////////////////////////////////////////


$ventas = $totalVentasSinIVA;
$gastos =   $TotalCanales['total'] + $totalAcopio + $totalLacteos + $totalCombos + $totaviaticos + $totalConsumos +  $totalCajaChica + $totalComprasUSD + $totalNomina + $totalTerceros + $totalAlquiler + (($resultadodonacionestrabajadores['Total'] + $resultadoconsumos['Total'] + $resultadodoaciones['Total'])  / $promediodolar ) + $totalBonos + $totalUtilidades + $totalLiquidaciones;

$y = ($totalVentasSinIVA);
$x = $y / $promediodolar;
$resultado1 = number_format(($x +   $totalSuma) - $gastos - $totalSumaFinal, 2);
$resultado2 = number_format(($ventas +  ($totalSuma * $promediodolar)) - ($gastos * $promediodolar) - ($totalSumaFinal * $promediodolar), 2);

$tabla = '';

$tabla .= '<tr>';
$tabla .= '<td style="background-color: #FF603F;"><center><b>DESDE: ' . $fecha_inicio  . ' </b></td>';
$tabla .= '<td style="background-color: #FF603F;"><b>TASA PROMEDIO: '.$promediodolar.'</b> </td>';
$tabla .= '<td style="background-color: #FF603F;"><b>HASTA: ' . $fecha_final . '</b></td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td></td>';
$tabla .= '<td></td>';
$tabla .= '<td></td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td style="background-color: lightred;"><center><b>INVENTARIO INICIAL</b></td>';
$tabla .= '<td style="background-color: lightred;"><b>' . number_format($totalSumaFinal, 2) . '</b></td>';
$tabla .= '<td style="background-color: lightred;"><b>' . number_format($totalSumaFinal *  $promediodolar, 2) . '</b></td>';
$tabla .= '</tr>';


$tabla .= '<tr>';
$tabla .= '<td style="background-color: lightblue;"><center><b>VENTAS</b></td>';
$tabla .= '<td style="background-color: lightblue;"><b>' . number_format($ventas / $promediodolar, 2) . '</b></td>';
$tabla .= '<td style="background-color: lightblue;"><b>' . number_format($ventas, 2) . '</b></td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td>VENTAS DE PRODUCTOS</td>';
$tabla .= '<td>' . number_format($totalVentasSinIVA / $promediodolar, 2) . '</td>';
$tabla .= '<td>' . number_format($totalVentasSinIVA, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td style="background-color: lightgreen;"><b><center>INVENTARIO FINAL</center></b></td>';
$tabla .= '<td style="background-color: lightgreen;"><b>' . number_format($totalSuma, 2)  . '</b></td>';
$tabla .= '<td style="background-color: lightgreen;"><b>' . number_format($totalSuma * $promediodolar, 2) .  '</b></td>';
$tabla .= '</tr>';


$tabla .= '<td></td>';
$tabla .= '<td></td>';
$tabla .= '<td></td>';


$tabla .= '<tr>';
$tabla .= '<td style="background-color: lightsalmon;"><center><b>GASTOS</b></td>';
$tabla .= '<td style="background-color: lightsalmon;"><b>' . number_format($gastos, 2) . '</b></td>';
$tabla .= '<td style="background-color: lightsalmon;"><b>' . number_format(floatval($gastos * $promediodolar), 2) . '</b></td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> COSTO DE VENTA MATADERO</td>';
$tabla .= '<td>' . number_format($totalAcopio + $TotalCanales['total'], 2) . '</td>';
$tabla .= '<td>' . number_format(($totalAcopio + $TotalCanales['total']) * $promediodolar, 2)  . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> COSTO DE VENTA PLANTA LACTEOS</td>';
$tabla .= '<td>' . number_format($totalLacteos, 2) . '</td>';
$tabla .= '<td>' . number_format($totalLacteos * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> ALQUILER DE LOCAL </td>';
$tabla .= '<td>' . number_format($totalAlquiler, 2) . '</td>';
$tabla .= '<td>' . number_format($totalAlquiler * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS DE NOMINA: SALARIOS </td>';
$tabla .= '<td>' . number_format($totalNomina, 2) . '</td>';
$tabla .= '<td>' . number_format($totalNomina * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS DE NOMINA: LIQUIDACIONES</td>';
$tabla .= '<td>' . number_format($totalLiquidaciones, 2) . '</td>';
$tabla .= '<td>' . number_format($totalLiquidaciones * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS DE NOMINA: BONOS </td>';
$tabla .= '<td>' . number_format($totalBonos, 2) . '</td>';
$tabla .= '<td>' . number_format($totalBonos * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS DE NOMINA: UTILIDADES</td>';
$tabla .= '<td>' . number_format($totalUtilidades, 2) . '</td>';
$tabla .= '<td>' . number_format($totalUtilidades * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS POR CAJA</td>';
$tabla .= '<td>' . number_format($totalCajaChica, 2) . '</td>';
$tabla .= '<td>' . number_format($totalCajaChica * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS POR VIATICOS</td>';
$tabla .= '<td>' . number_format($totaviaticos, 2) . '</td>';
$tabla .= '<td>' . number_format($totaviaticos * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS POR COMBOS TRABAJADORES </td>';
$tabla .= '<td>' . number_format($totalCombos, 2) . '</td>';
$tabla .= '<td>' . number_format($totalCombos * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS POR CONSUMOS TRABAJADORES </td>';
$tabla .= '<td>' . number_format($totalConsumos, 2) . '</td>';
$tabla .= '<td>' . number_format($totalConsumos * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td> GASTOS POR COMPRAS</td>';
$tabla .= '<td>' . number_format($totalComprasUSD, 2) . '</td>';
$tabla .= '<td>' . number_format($totalComprasUSD * $promediodolar, 2) . '</td>';
$tabla .= '</tr>';


$tabla .= '<tr>';
$tabla .= '<td style="background-color:  #ffd041;"><b><center>CONTRIBUCIONES SOCIALES</center></b></td>';
$tabla .= '<td style="background-color:  #ffd041;"><b>' . number_format($resultadodoaciones['Total'] / $promediodolar, 2) . '</b></td>';
$tabla .= '<td style="background-color:  #ffd041;"><b>' . number_format($resultadodoaciones['Total'], 2) . '</b></td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td style="background-color:  #ffd041;"><b><center>BONO DE TRABAJADORES</center></b></td>';
$tabla .= '<td style="background-color:  #ffd041;"><b>' . number_format($resultadodonacionestrabajadores['Total'] / $promediodolar, 2) . '</b></td>';
$tabla .= '<td style="background-color:  #ffd041;"><b>' . number_format($resultadodonacionestrabajadores['Total'], 2) . '</b></td>';
$tabla .= '</tr>';

$tabla .= '<tr>';
$tabla .= '<td style="background-color:  #ffd041;"><b><center>AUTOCONSUMO</center></b></td>';
$tabla .= '<td style="background-color:  #ffd041;"><b>' . number_format($resultadoconsumos['Total'] / $promediodolar, 2) . '</b></td>';
$tabla .= '<td style="background-color:  #ffd041;"><b>' . number_format($resultadoconsumos['Total'], 2) . '</b></td>';
$tabla .= '</tr>';

if ($resultado1 > 0) {
  $tabla .= '<tr>';
  $tabla .= '<td style="background-color: #8FFF80 ;"><center><b>RESULTADO</b></td>';
  $tabla .= '<td style="background-color:#8FFF80  ;"><b>' . $resultado1 . '</b></td>';
  $tabla .= '<td style="background-color:#8FFF80  ;"><b>' . $resultado2 . '</b></td>';
  $tabla .= '</tr>';
} elseif ($resultado1 < 0) {
  $tabla .= '<tr>';
  $tabla .= '<td style="background-color: lightcoral;"><center><b>RESULTADO</b></td>';
  $tabla .= '<td style="background-color: lightcoral;"><b>' . $resultado1 . '</b></td>';
  $tabla .= '<td style="background-color: lightcoral;"><b>' . $resultado2 . '</b></td>';
  $tabla .= '</tr>';
}
$tabla .= '<tr>';
$tabla .= '<td></td>';
$tabla .= '<td></td>';
$tabla .= '<td></td>';
$tabla .= '</tr>';



$tabla .= '<tr>';
$tabla .= '<td></td>';
$tabla .= '<td></td>';
$tabla .= '<td></td>';
$tabla .= '</tr>';


echo json_encode($tabla, JSON_UNESCAPED_UNICODE);