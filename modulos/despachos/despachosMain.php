<?php

//* CONSULTAR TIPO DE DESPACHOS
function despachosTiposLista()
{
  return conexion()->query('SELECT * FROM despachos_tipos WHERE EstadoTipoDesp = 1');
}

//* CONSULTAR TIPO DE DESPACHOS X ID
function despachosTiposVerificarXID($datos)
{
  $sql = conexion()->prepare('SELECT * FROM despachos_tipos WHERE EstadoTipoDesp = 1 AND IDTipoDespacho = ?');
  $sql->execute($datos);
  return $sql;
}

//* GENERARA NRO DE NOTA SEGUN EL TIPO DE DESPACHO
function despachosGenerarNroNota($datos)
{
  $sql = conexion()->prepare('SELECT NroNota FROM despachos_resumen WHERE IDTipoDesp = ? GROUP BY NroNota ORDER BY NroNota DESC LIMIT 1');
  $sql->execute($datos);
  $sql = $sql->fetch(PDO::FETCH_ASSOC);
  if ($sql === FALSE) return 1;
  return $sql['NroNota'] + 1;
}

//* REGISTRAR RESUMEN DEL DESPACHO
function despachosRegistrarResumen($datos)
{
  $conexion = conexion();
  $sql = $conexion->prepare('INSERT INTO
  despachos_resumen (
    FechaDesp,
    IDCliente,
    IDTipoDesp,
    NroNota,
    Chofer,
    ChoferCedula,
    ObservacionDesp,
    ResponsableDesp,
    EstadoDesp
  )
VALUES
  (?, ?, ?, ?, ?, ?, ?, ?, 1)');
  $sql->execute($datos);
  return $conexion->lastInsertId();
}

//* REGISTRAR DETALLE
function despachosRegistrarDetalle($datos)
{
  $sql = conexion()->prepare('INSERT INTO
  despachos_detalles (
    IDDespachoResumen,
    IDInvPlanta,
    IDInvProduccion,
    CantDesp,
    PrecioVentaDespUSD,
    ValorAlicuotaDesp
  )
VALUES
  (?, ?, ?, ?, ?, ?)');
  $sql->execute($datos);
}

//* ACTUALIZAR ESTADO DEL DESPACHO 
function despachosActualizarEstado($datos)
{
  $sql = conexion()->prepare('UPDATE despachos_resumen SET EstadoDesp = ?, UltimaActualiacionDesp = ? WHERE IDDespachoResumen = ?');
  $sql->execute($datos);
}

//* ACTUALIZAR PRECIO EN BS
function despachosActualizarPrecioBS($datos)
{
  $sql = conexion()->prepare('UPDATE despachos_detalles SET PrecioVentaDespBS = ? WHERE IDDespachoDetalle = ?');
  $sql->execute($datos);
}

//* INGREGAR DATOS DE LA FACTURA DEL DESPACHO
function despachosActualizarDatosFactura($datos)
{
  $sql = conexion()->prepare('UPDATE despachos_resumen SET FacturaSerie = ?, FacturaNro = ?, FacturaNroControl = ?, UltimaActualiacionDesp = ? WHERE IDDespachoResumen = ?');
  $sql->execute($datos);
}

//* CONSULTA DESPACHO DETALLADO
function despachosConsultarDespachoDetalladoXID($datos)
{
  $sql = conexion()->prepare('SELECT
  clientes.RifCedula,
  clientes.RazonSocial,
  clientes.DomicilioFiscal,
  despachos_tipos.DescripcionTipoDesp,
  despachos_resumen.FechaDesp,
  despachos_resumen.NroNota,
  despachos_resumen.Chofer,
  despachos_resumen.ChoferCedula,
  despachos_resumen.ObservacionDesp,
  despachos_resumen.ResponsableDesp,
  despachos_detalles.CantDesp,
  despachos_detalles.IDInvPlanta,
  despachos_detalles.IDInvProduccion,
  CASE WHEN despachos_detalles.IDInvPlanta IS NULL THEN inventario_produccion.DescripcionProducto ELSE articulos.DescripcionArticulo END AS DescripcionProducto,
  CASE WHEN despachos_detalles.IDInvPlanta IS NULL THEN inventario_produccion_medidas.DescripcionUnidadMedida ELSE empaques.DescripcionProducto END AS DescripcionPrecentacion,
  produccion_resumen.NroLote
FROM
  despachos_detalles
  LEFT JOIN inventario_planta ON despachos_detalles.IDInvPlanta = inventario_planta.IDInvPlanta
  LEFT JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  LEFT JOIN inventario_produccion AS empaques ON produccion_resumen.IDEmpaque = empaques.IDInvProduccion
  LEFT JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  LEFT JOIN inventario_produccion ON despachos_detalles.IDInvProduccion = inventario_produccion.IDInvProduccion
  LEFT JOIN inventario_produccion_medidas ON inventario_produccion.IDUnidadMedida = inventario_produccion_medidas.IDUnidadMedida
  INNER JOIN despachos_resumen ON despachos_detalles.IDDespachoResumen = despachos_resumen.IDDespachoResumen
  INNER JOIN despachos_tipos ON despachos_resumen.IDTipoDesp = despachos_tipos.IDTipoDespacho
  INNER JOIN clientes ON despachos_resumen.IDCliente = clientes.IDCliente
WHERE
  despachos_resumen.IDDespachoResumen = ?');
  $sql->execute($datos);
  return $sql;
}

//* CONSULTA DESPACHO RESUMIDO
function despachosConsultarDespachoResumidoXID($datos)
{
  $sql = conexion()->prepare('SELECT
  despachos_resumen.IDDespachoResumen,
  despachos_resumen.NroNota,
  despachos_tipos.DescripcionTipoDesp,
  despachos_resumen.Chofer,
  despachos_resumen.ChoferCedula,
  despachos_resumen.ObservacionDesp,
  despachos_resumen.ResponsableDesp,
  despachos_resumen.FechaDesp,
  clientes.RifCedulaCliente,
  clientes.RazonSocialCliente,
  clientes.DomicilioFiscalCliente,
  inventario.DescripcionProducto,
  inventario_tipos_presentacion.DescripcionPresentacion,
  despachos_detalles.ValorAlicuotaDesp,
  despachos_detalles.CantDesp,
  despachos_detalles.PrecioVentaDespUSD,
  despachos_detalles.PrecioVentaDespBS
FROM
  despachos_detalles
  LEFT JOIN inventario ON despachos_detalles.IDInvPlanta = inventario.IDInvProducto
  LEFT JOIN inventario_tipos_presentacion ON inventario.IDPresentacion = inventario_tipos_presentacion.IDPresentacion
  INNER JOIN despachos_resumen ON despachos_detalles.IDDespachoResumen = despachos_resumen.IDDespachoResumen
  INNER JOIN despachos_tipos ON despachos_resumen.IDTipoDesp = despachos_tipos.IDTipoDespacho
  INNER JOIN clientes ON despachos_resumen.IDCliente = clientes.IDCliente
WHERE
  despachos_resumen.IDDespachoResumen = ?
GROUP BY inventario.IDInvProducto');
  $sql->execute($datos);
  return $sql;
}

//* REPORTE DE DESPACHOS REALIZADOS
function reportDespahosRealizados($datos, $IDTipoDesp)
{
  $sql = conexion()->prepare('SELECT
  despachos_resumen.IDDespachoResumen,
  despachos_resumen.FechaDesp,
  despachos_resumen.NroNota,
  despachos_resumen.ResponsableDesp,
  despachos_resumen.ObservacionDesp,
  despachos_resumen.Chofer,
  despachos_resumen.ChoferCedula,
  despachos_resumen.EstadoDesp,
  despachos_resumen.FacturaSerie,
  despachos_resumen.FacturaNro,
  despachos_resumen.FacturaNroControl,
  despachos_tipos.DescripcionTipoDesp,
  despachos_tipos.IDTipoDespacho,
  clientes.RifCedulaCliente,
  clientes.RazonSocialCliente,
  ROUND(SUM(CantDesp), 2) AS Cantidad,
  ROUND(SUM(despachos_detalles.CantDesp * despachos_detalles.PrecioVentaDespUSD), 2) AS MontoTotalUSD,
  ROUND(SUM(CASE WHEN despachos_detalles.ValorAlicuotaDesp = 0 THEN despachos_detalles.CantDesp * despachos_detalles.PrecioVentaDespUSD ELSE 0 END), 2) AS MontoExento,
  ROUND(SUM(CASE WHEN despachos_detalles.ValorAlicuotaDesp > 0 THEN despachos_detalles.CantDesp * despachos_detalles.PrecioVentaDespUSD ELSE 0 END), 2) AS MontoBaseImponible,
  ROUND(SUM(CASE WHEN despachos_detalles.ValorAlicuotaDesp > 0 THEN (despachos_detalles.CantDesp * despachos_detalles.PrecioVentaDespUSD) * despachos_detalles.ValorAlicuotaDesp ELSE 0 END), 2) AS MontoIva
FROM
  despachos_detalles
LEFT JOIN inventario ON despachos_detalles.IDInvPlanta = inventario.IDInvProducto
  LEFT JOIN inventario_tipos_presentacion ON inventario.IDPresentacion = inventario_tipos_presentacion.IDPresentacion
  INNER JOIN despachos_resumen ON despachos_detalles.IDDespachoResumen = despachos_resumen.IDDespachoResumen
  INNER JOIN despachos_tipos ON despachos_resumen.IDTipoDesp = despachos_tipos.IDTipoDespacho
  INNER JOIN clientes ON despachos_resumen.IDCliente = clientes.IDCliente
WHERE
  despachos_resumen.FechaDesp BETWEEN ? AND ? ' . ($IDTipoDesp != '' ? 'AND despachos_resumen.IDTipoDesp = ' . $IDTipoDesp : '') . '
GROUP BY
  despachos_detalles.IDDespachoResumen');
  $sql->execute($datos);
  return $sql;
}

//* REPORTE DE DISTRIBUCION POR PRODUCTO Y TIPO DE DESPACHO
function reportDespachoDistribucionXClienteYArticulo($datos)
{
  $sql = conexion()->prepare('SELECT
        clientes.RifCedulaCliente,
        clientes.RazonSocialCliente,
        inventario.DescripcionProducto,
        inventario_tipos_presentacion.DescripcionPresentacion,
        SUM(despachos_detalles.CantDesp) AS CantUnd
    FROM
        despachos_detalles
        INNER JOIN inventario ON despachos_detalles.IDInvPlanta = inventario.IDInvProducto
        LEFT JOIN inventario_tipos_presentacion ON inventario.IDPresentacion = inventario_tipos_presentacion.IDPresentacion
        INNER JOIN despachos_resumen ON despachos_detalles.IDDespachoResumen = despachos_resumen.IDDespachoResumen
        INNER JOIN clientes ON despachos_resumen.IDCliente = clientes.IDCliente
    WHERE
        despachos_resumen.EstadoDesp = 1
        AND despachos_resumen.FechaDesp BETWEEN ? AND ? 
        AND despachos_resumen.IDTipoDesp = ?
    GROUP BY
        clientes.IDCliente,
        inventario.IDInvProducto');

  $sql->execute($datos);
  return $sql;
}
//* REPORTE DE DESPACHO POR ARTICULOS
function reportDespachoDistribucionXArticulos($datos, $IDMedida)
{
  $sql = conexion()->prepare('SELECT
  articulos.DescripcionArticulo,
  inventario_produccion.DescripcionProducto,
  SUM(despachos_detalles.CantDesp) AS CantUnd,
  SUM(despachos_detalles.CantDesp * inventario_produccion.CapacidadEmpaque) AS CantKg
FROM
  despachos_detalles
  INNER JOIN inventario_planta ON despachos_detalles.IDInvPlanta = inventario_planta.IDInvPlanta
  INNER JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  INNER JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  INNER JOIN articulos_alicuotas ON articulos.IDAlicuota = articulos_alicuotas.IDAlicuota
  INNER JOIN inventario_produccion ON produccion_resumen.IDEmpaque = inventario_produccion.IDInvProduccion
  INNER JOIN despachos_resumen ON despachos_detalles.IDDespachoResumen = despachos_resumen.IDDespachoResumen
  INNER JOIN clientes ON despachos_resumen.IDCliente = clientes.IDCliente
WHERE
  despachos_resumen.EstadoDesp = 1
  AND despachos_resumen.FechaDesp BETWEEN ? AND ?
  AND despachos_resumen.IDTipoDesp = ?
GROUP BY
  articulos.IDArticulo' . ($IDMedida == 'und' ? ', produccion_resumen.IDEmpaque' : ''));
  $sql->execute($datos);
  return $sql;
}

//* REPORTE DE DESPACHO POR CLIENTE
function reportDespachoDistribucionXCliente($datos)
{
  $sql = conexion()->prepare('SELECT
  despachos_resumen.IDDespachoResumen,
  despachos_resumen.NroNota,
  despachos_tipos.DescripcionTipoDesp,
  despachos_resumen.Chofer,
  despachos_resumen.ChoferCedula,
  despachos_resumen.ObservacionDesp,
  despachos_resumen.ResponsableDesp,
  despachos_resumen.FechaDesp,
  clientes.IDCliente,
  clientes.RifCedula,
  clientes.RazonSocial,
  clientes.DomicilioFiscal,
  (CASE WHEN despachos_detalles.IDInvPlanta IS NULL THEN inventario_produccion.DescripcionProducto ELSE CONCAT(articulos.DescripcionArticulo, " - ", empaques.DescripcionProducto) END) AS DescripcionProducto,
  despachos_detalles.ValorAlicuotaDesp,
  SUM(despachos_detalles.CantDesp) AS CantDesp,
  despachos_detalles.PrecioVentaDespUSD,
  despachos_detalles.PrecioVentaDespBS
FROM
  despachos_detalles
  LEFT JOIN inventario_planta ON despachos_detalles.IDInvPlanta = inventario_planta.IDInvPlanta
  LEFT JOIN produccion_resumen ON inventario_planta.IDProduccionResumen = produccion_resumen.IDProduccionResumen
  LEFT JOIN inventario_produccion AS empaques ON produccion_resumen.IDEmpaque = empaques.IDInvProduccion
  LEFT JOIN articulos ON produccion_resumen.IDArticulo = articulos.IDArticulo
  LEFT JOIN inventario_produccion ON despachos_detalles.IDInvProduccion = inventario_produccion.IDInvProduccion
  INNER JOIN despachos_resumen ON despachos_detalles.IDDespachoResumen = despachos_resumen.IDDespachoResumen
  INNER JOIN despachos_tipos ON despachos_resumen.IDTipoDesp = despachos_tipos.IDTipoDespacho
  INNER JOIN clientes ON despachos_resumen.IDCliente = clientes.IDCliente
WHERE
  despachos_resumen.EstadoDesp = 1
  AND despachos_resumen.FechaDesp BETWEEN ? AND ?
  AND despachos_resumen.IDTipoDesp = ?
GROUP BY despachos_resumen.IDCliente,
          (CASE WHEN despachos_detalles.IDInvPlanta IS NULL THEN inventario_produccion.DescripcionProducto ELSE CONCAT(articulos.DescripcionArticulo, " - ", empaques.DescripcionProducto) END)');
  $sql->execute($datos);
  return $sql;
}

//* REPORTE DE DESPACHO POR DESPACHOS
function reportDespachoDistribucionXDespacho($datos)
{
  $sql = conexion()->prepare('SELECT
  despachos_resumen.IDDespachoResumen,
  despachos_resumen.NroNota,
  despachos_tipos.DescripcionTipoDesp,
  despachos_resumen.Chofer,
  despachos_resumen.ChoferCedula,
  despachos_resumen.ObservacionDesp,
  despachos_resumen.ResponsableDesp,
  despachos_resumen.FechaDesp,
  clientes.IDCliente,
  clientes.RifCedulaCliente,
  clientes.RazonSocialCliente,
  clientes.DomicilioFiscalCliente,
  inventario.IDInvProducto,
  inventario.DescripcionProducto,
  inventario.CodigoProducto,
  inventario_tipos_presentacion.DescripcionPresentacion,
  despachos_detalles.ValorAlicuotaDesp,
  despachos_detalles.CantDesp,
  despachos_detalles.PrecioVentaDespUSD,
  despachos_detalles.PrecioVentaDespBS
FROM
  despachos_detalles
  LEFT JOIN inventario ON despachos_detalles.IDInvPlanta = inventario.IDInvProducto
  LEFT JOIN inventario_tipos_presentacion ON inventario.IDPresentacion = inventario_tipos_presentacion.IDPresentacion
  INNER JOIN despachos_resumen ON despachos_detalles.IDDespachoResumen = despachos_resumen.IDDespachoResumen
  INNER JOIN despachos_tipos ON despachos_resumen.IDTipoDesp = despachos_tipos.IDTipoDespacho
  INNER JOIN clientes ON despachos_resumen.IDCliente = clientes.IDCliente
WHERE
  despachos_resumen.EstadoDesp = 1
  AND despachos_resumen.FechaDesp BETWEEN ? AND ?
  AND despachos_resumen.IDTipoDesp = ?
GROUP BY despachos_resumen.IDDespachoResumen,
          inventario.IDInvProducto');
  $sql->execute($datos);
  return $sql;
}
