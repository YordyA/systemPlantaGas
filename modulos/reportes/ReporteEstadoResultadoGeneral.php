<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../inventario/inventario_main.php';
require_once 'reportes_main.php';
require_once '../despachosMatadero/despachosMataderoMain.php';


function CostoPlantaLacteos($datos) {
    $sql = conexionPlantaLacteos()->prepare('SELECT
    SUM(facturas_detalle.Cantidad * facturas_detalle.PrecioCosto) AS MontoTotalUSD
FROM
    facturas_resumen
INNER JOIN facturas_detalle ON facturas_resumen.NroDespacho = facturas_detalle.NroDespacho
WHERE
    EstadoFactura = 2 AND FechaDespacho BETWEEN ? AND ? AND IDCliente IN(1,2,3,4,5)');
    $sql->execute($datos);
    return $sql;
}


function CostosMataderoAcopio($id)
{
    $sql  = conexionCentroAcopio()->prepare('SELECT
    ventas.fecha,
    ventas.cantidad,
    ventas.precio,
    ventas.precio_subtotal,
    unidades_produccion.unidad_produccion,
    productos.descripcion
FROM
    ventas
INNER JOIN unidades_produccion ON ventas.id_cliente = unidades_produccion.id_unidad_produccion
INNER JOIN productos ON ventas.id_producto = productos.id_producto
WHERE
    ventas.fecha BETWEEN ? AND ? AND unidades_produccion.id_unidad_produccion IN(1056,1057,1058,1059,1060,1061) AND ventas.anulado = 0 AND ventas.tipo_despacho = 3');
    $sql ->execute($id);
  return     $sql ->fetchAll(PDO::FETCH_ASSOC);
}

function CostosMataderoCanales($id)
{
    $sql  = conexionMatadero()->prepare("SELECT
    SUM(despacho_detalle.SubTotalPrecio) AS Total
FROM
    despacho_detalle
INNER JOIN despacho_resumen ON despacho_detalle.NroDespacho = despacho_resumen.NroDespacho
WHERE
    despacho_resumen.IDCliente IN(77,78,79,80,81,82) AND despacho_resumen.Fecha BETWEEN ? AND ? AND EstadoDespacho = 2");
    $sql ->execute($id);
  return $sql;
}

function tasas_dolar($datos)
{
  $reporte = conexion()->prepare("SELECT * from tasadolarhistorial WHERE Fecha BETWEEN ? AND ?");
  $reporte->execute($datos);
  return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

function facturacionXArticuloResumidoGeneral($datos)
{
  $sql = conexion()->prepare('SELECT
SUM(Exento) AS MontoExento,
SUM(Gravado) AS MontoGravado,
SUM(Iva) AS MontoIva
FROM
    facturasresumen
WHERE
    Fecha BETWEEN ? AND ? AND facturasresumen.Estatus < 2');
  $sql->execute($datos);
  return $sql->fetch(PDO::FETCH_ASSOC);
}

//^ REPORTE DE DONACIONES RESUMIDO
function donacionesResumenGeneral($datos)
{
  $sql = conexion()->prepare('SELECT
    SUM(SubTotal) AS Total
FROM
    donacionesdetalle
INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion
WHERE
    donacionesresumen.Fecha BETWEEN ? AND ? AND donacionesresumen.TipoConsumo = 0 AND donacionesresumen.Estatus = 0');
  $sql->execute($datos);
  return  $sql->fetch(PDO::FETCH_ASSOC);
}

//^ TOTAL DE CONSUMO
function consumoResumenGeneral($datos)
{
  $sql = conexion()->prepare('SELECT
    SUM(SubTotal) AS Total
FROM
    donacionesdetalle
INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion
WHERE
    donacionesresumen.Fecha BETWEEN ? AND ? AND donacionesresumen.TipoConsumo = 1 AND donacionesresumen.Estatus = 0');
  $sql->execute($datos);
  return  $sql->fetch(PDO::FETCH_ASSOC);
}

function donacionesTrabajadoresGeneral($id)
{
  $sql = conexion()->prepare('SELECT
    SUM(SubTotal) AS Total
FROM
    donacionesdetalle
INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion
WHERE
    donacionesresumen.Fecha BETWEEN ? AND ? AND donacionesresumen.TipoConsumo = 2 AND donacionesresumen.Estatus = 0');
  $sql->execute($id);
  return  $sql->fetch(PDO::FETCH_ASSOC);
}

# REPORTES DE GASTOS POR TIPO DE GASTO
function reporteGastosGeneral($datos)
{
  $sql = conexion()->prepare('SELECT * FROM compras WHERE Fecha BETWEEN ? AND ?');
  $sql->execute($datos);
  return $sql;
}

function estadoResultadoReportComsumoCombosGeneral($datos)
{
  $sql = conexionCentroAcopio()->prepare('SELECT
    SUM(precio_subtotal) AS TotalUSD
FROM
    ventas
WHERE
    id_cliente IN(1056,1057,1058,1059,1060,1061) AND tipo_despacho = 1 AND anulado != 1 AND fecha BETWEEN ? AND ?');
  $sql->execute($datos);
  return $sql->fetch(PDO::FETCH_ASSOC);
}

function estadoResultadoReportCombosGeneral($datos)
{
  $sql = conexionCentroAcopio()->prepare('SELECT
  SUM(precio_subtotal) AS TotalUSD
FROM
  ventas
WHERE
  id_cliente IN(1056,1057,1058,1059,1060,1061)
  AND tipo_despacho = 2
  AND anulado != 1
  AND fecha BETWEEN ? AND ?');
  $sql->execute($datos);
  return $sql->fetch(PDO::FETCH_ASSOC);
}

function estadoResultadoReportComprasGeneral($datos)
{
  $sql = conexionCompras()->prepare('SELECT
  SUM(TotalCompraUSD) AS TotalUSD,
  SUM(TotalCompraBS) AS TotalBS
FROM
  compras_resumen
WHERE
  IDUnidadProduccion IN(32,33,34,35,36,39)
  AND Estatus != 1
  AND Fecha BETWEEN ? AND ?');
  $sql->execute($datos);
  return $sql->fetch(PDO::FETCH_ASSOC);
}

function inventarioFinalGeneral($datos)
{
  $sql = conexion()->prepare('SELECT * FROM valorinventariofinal WHERE FechaCierre = ?');
  $sql->execute($datos);
  return $sql->fetchAll(PDO::FETCH_ASSOC);
}


$fecha_inicio = LimpiarCadena($_GET['fecha_inicio']);
$fecha_final = LimpiarCadena($_GET['fecha_final']);

$consultarGastoCompras = estadoResultadoReportComprasGeneral([$fecha_inicio, $fecha_final]);

$consultarGastoCombosMatadero = estadoResultadoReportCombosGeneral([$fecha_inicio, $fecha_final]);

$consultarGastoComsumoMatadero = estadoResultadoReportComsumoCombosGeneral([$fecha_inicio, $fecha_final]);

$TotalCanales = CostosMataderoCanales([$fecha_inicio, $fecha_final])->fetch(PDO::FETCH_ASSOC);
$resultadodoaciones = donacionesResumenGeneral([$fecha_inicio, $fecha_final]);
$resultadoconsumos = consumoResumenGeneral([$fecha_inicio, $fecha_final]);
$resultadodonacionestrabajadores = donacionesTrabajadoresGeneral([$fecha_inicio, $fecha_final]);

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
$totalLacteos = CostoPlantaLacteos([$fecha_inicio, $fecha_final])->fetch(PDO::FETCH_ASSOC)['MontoTotalUSD'];
$totalTerceros = 0;
$totalAlquiler = 0;
$totalLiquidaciones = 0;
$totalUtilidades = 0;
$totalBonos = 0;


foreach (administracionReportGastosResumidoGeneral([$fecha_inicio, $fecha_final]) as $rowGastos) {
  if ($rowGastos['IDTipoGasto'] == 27) {
    $totalNomina += $rowGastos['MontoTotalUSD'];
  } else if ($rowGastos['IDTipoGasto'] == 2) {
    $totalCajaChica += $rowGastos['MontoTotalUSD'];
  } else if ($rowGastos['IDTipoGasto'] == 3) {
    $totalCombustible += $rowGastos['MontoTotalUSD'];
  } else if ($rowGastos['IDTipoGasto'] == 6) {
    $totaviaticos += $rowGastos['MontoTotalUSD'];
  } else if ($rowGastos['IDTipoGasto'] == 9) {
    $totalTerceros += $rowGastos['MontoTotalUSD'];
  } else if ($rowGastos['IDTipoGasto'] == 26) {
    $totalAlquiler += $rowGastos['MontoTotalUSD'];
  }else if ($rowGastos['IDTipoGasto'] == 21) {
    $totalLiquidaciones += $rowGastos['MontoTotalUSD'];
  }else if ($rowGastos['IDTipoGasto'] == 22) {
    $totalLiquidaciones += $rowGastos['MontoTotalUSD'];
  }else if ($rowGastos['IDTipoGasto'] == 23) {
    $totalLiquidaciones += $rowGastos['MontoTotalUSD'];
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

foreach (CostosMataderoAcopio([$fecha_inicio, $fecha_final]) as $row) {
    $totalAcopio+=$row['precio_subtotal'];
}

$consultaVentas = facturacionXArticuloResumidoGeneral([$fecha_inicio, $fecha_final]);

$totalVentasSinIVA = $consultaVentas['MontoExento'] + $consultaVentas['MontoGravado'];
$totalIVA = $consultaVentas['MontoIva'];


///////////////////////////////////////////////////////////////////////////////////////////
$totalSuma = 0;
foreach (inventarioFinalGeneral([$fecha_final]) as $row) {
  $valorTotal = $row['Costo'] * $row['Existencia'];
  $totalSuma += $valorTotal;
}

$fecha_anterior = date("Y-m-d", strtotime($fecha_inicio . "-1 day"));

$totalSumaFinal = 0;
foreach (inventarioFinalGeneral([$fecha_anterior]) as $roww) {
    $valorTotal = $roww['Costo'] * $roww['Existencia'];
    $totalSumaFinal += $valorTotal;
}
///////////////////////////////////////////////////////////////////////////////////////////


$ventas = $totalVentasSinIVA;
$gastos =   $TotalCanales['Total'] + $totalAcopio + $totalLacteos + $totalCombos + $totalConsumos +  $totalCajaChica + $totalComprasUSD + $totalNomina + $totalTerceros + $totalAlquiler + $totaviaticos + (($resultadodonacionestrabajadores['Total'] + $resultadoconsumos['Total'] + $resultadodoaciones['Total'])  / $promediodolar ) + $totalBonos + $totalUtilidades + $totalLiquidaciones;

$y = ($totalVentasSinIVA);
$x = $y / $promediodolar;
$resultado1 = number_format(($x +   $totalSuma) - $gastos - $totalSumaFinal, 2);
$resultado2 = number_format(($ventas +  ($totalSuma * $promediodolar)) - ($gastos * $promediodolar) - ($totalSumaFinal * $promediodolar), 2);

$tabla = '';

$tabla .= '<tr>';
$tabla .= '<td style="background-color: #FF603F;"><center><b>DESDE: ' . $fecha_inicio  . ' </b></td>';
$tabla .= '<td style="background-color: #FF603F;"><b>.</b> </td>';
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
$tabla .= '<td>' . number_format($totalAcopio + $TotalCanales['Total'], 2) . '</td>';
$tabla .= '<td>' . number_format(($totalAcopio + $TotalCanales['Total']) * $promediodolar, 2)  . '</td>';
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
$tabla .= '<td>' . number_format($totalComprasBS, 2) . '</td>';
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
