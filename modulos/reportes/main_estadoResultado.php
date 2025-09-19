<?php
function CostosMataderoAcopio($id)
{
    $sql  = conexionCentroAcopio()->prepare("SELECT ventas.fecha, ventas.cantidad, ventas.precio, ventas.precio_subtotal, unidades_produccion.unidad_produccion, productos.descripcion 
                                              FROM ventas
                                              INNER JOIN unidades_produccion ON ventas.id_cliente = unidades_produccion.id_unidad_produccion
                                              INNER JOIN productos ON ventas.id_producto = productos.id_producto 
                                              WHERE ventas.fecha BETWEEN ? AND ? AND unidades_produccion.id_unidad_produccion = ? 
                                              AND ventas.anulado = 0 AND ventas.tipo_despacho = 3");
    $sql->execute($id);
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

function CostosMataderoCanales($id)
{
    $sql = conexionMatadero()->prepare("SELECT SUM(despacho_detalle.SubTotalPrecio) as total 
                                        FROM despacho_detalle 
                                        INNER JOIN despacho_resumen ON despacho_detalle.NroDespacho = despacho_resumen.NroDespacho
                                        WHERE despacho_resumen.IDCliente = ? AND despacho_resumen.Fecha BETWEEN ? AND ? 
                                        AND EstadoDespacho = 2");
    $sql->execute($id);
    return $sql;
}

function tasas_dolar($datos)
{
    $reporte = conexion()->prepare("SELECT * FROM tasadolarhistorial WHERE Fecha BETWEEN ? AND ?");
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
                                     WHERE facturasresumen.IDSucursal = ? AND facturasdetalle.IDSucursal = ?
                                       AND facturasresumen.Fecha BETWEEN ? AND ?
                                       AND facturasresumen.Estatus < 2
                                     GROUP BY articulosdeinventario.IDArticulo");
    $reporte->execute($datos);
    return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

function donaciones_resumen($id)
{
    $reportes = conexion()->prepare("SELECT SUM(SubTotal) as Total FROM donacionesdetalle 
                                     INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion 
                                     WHERE donacionesresumen.IDSucursal = ? 
                                       AND donacionesdetalle.IDSucursal = ? 
                                       AND donacionesresumen.Fecha BETWEEN ? AND ? 
                                       AND donacionesresumen.TipoConsumo = 0 
                                       AND donacionesresumen.Estatus = 0;");
    $reportes->execute($id);
    return $reportes->fetch(PDO::FETCH_ASSOC);
}

function consumo_resumen($id)
{
    $reportes = conexion()->prepare("SELECT SUM(SubTotal) as Total FROM donacionesdetalle 
                                     INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion 
                                     WHERE donacionesresumen.IDSucursal = ? 
                                       AND donacionesdetalle.IDSucursal = ? 
                                       AND donacionesresumen.Fecha BETWEEN ? AND ? 
                                       AND donacionesresumen.TipoConsumo = 1 
                                       AND donacionesresumen.Estatus = 0;");
    $reportes->execute($id);
    return $reportes->fetch(PDO::FETCH_ASSOC);
}

function donaciones_trabajadores($id)
{
    $reportes = conexion()->prepare("SELECT SUM(SubTotal) as Total FROM donacionesdetalle 
                                     INNER JOIN donacionesresumen ON donacionesdetalle.NDonacion = donacionesresumen.NDonacion 
                                     WHERE donacionesresumen.IDSucursal = ? 
                                       AND donacionesdetalle.IDSucursal = ? 
                                       AND donacionesresumen.Fecha BETWEEN ? AND ? 
                                       AND donacionesresumen.TipoConsumo = 2 
                                       AND donacionesresumen.Estatus = 0;");
    $reportes->execute($id);
    return $reportes->fetch(PDO::FETCH_ASSOC);
}

function GastosAdministracion($datos)
{
    $reporte = conexionAdministracion()->prepare('SELECT * FROM gastos_administrativo WHERE FechaGasto BETWEEN ? AND ? 
                                                 AND IDCentroCosto = ? AND EstadoGasto = 2');
    $reporte->execute($datos);
    return $reporte->fetchAll(PDO::FETCH_ASSOC);
}

function inventario_final($datos)
{
    $reporte = conexion()->prepare("SELECT * FROM inventario WHERE Fecha = ? AND IDSucursal = ?");
    $reporte->execute($datos);
    return $reporte->fetchAll(PDO::FETCH_ASSOC);
}
