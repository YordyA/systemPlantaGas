<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../cobrar/CobrarMain.php';
require_once '../reportes/reportes_main.php';
require_once '../inventario/inventario_main.php';
require_once '../dependencias.php';
$IDSucursal = $_GET['id'] ?? $_SESSION['PlantaGas']['IDPlanta'];
$NroVenta = Desencriptar(LimpiarCadena($_GET['n']));

//! OJO
$consulta = conexion()->prepare('SELECT
  *
FROM
  facturasresumen
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
  INNER JOIN facturasdetalle ON facturasresumen.IDResumenVenta = facturasdetalle.NVenta
  INNER JOIN productos ON facturasdetalle.IDProducto = productos.IDProducto
  INNER JOIN tipo_productos ON productos.IDTipoProducto = tipo_productos.IDTipo
  INNER JOIN facturasmediopago ON facturasresumen.IDResumenVenta = facturasmediopago.NVenta
WHERE
  facturasresumen.IDResumenVenta = ?
  AND facturasresumen.IDSucursal = ?');
$consulta->execute(
  [
    $NroVenta,
    $IDSucursal
  ]
);

$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

anularResumenVenta([$NroVenta, $IDSucursal]);
$i = 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FACTURA</title>
</head>

<body>
  <form id="formulario" action="http://localhost/maquinaFiscal/notaDeCredito.php" method="post">
    <input type="hidden" name="NroVenta" value="<?php echo $consulta[0]['NVenta']; ?>">
    <input type="hidden" name="NroFacturaFiscal" value="<?php echo $consulta[0]['NFacturaFiscal']; ?>">
    <input type="hidden" name="IDCliente" value="<?php echo $consulta[0]['IDCliente']; ?>">
    <input type="hidden" name="fecha" value="<?php echo $consulta[0]['Fecha']; ?>">
    <input type="hidden" name="rifCedula" value="<?php echo $consulta[0]['RifCliente']; ?>">
    <input type="hidden" name="razonSocial" value="<?php echo $consulta[0]['NombreCliente']; ?>">

    <input type="hidden" name="efectivo" value="<?php echo $consulta[0]['Efectivo']; ?>">
    <input type="hidden" name="tarjeta" value="<?php echo $consulta[0]['Tarjeta']; ?>">
    <input type="hidden" name="biopago" value="<?php echo $consulta[0]['BioPago']; ?>">
    <input type="hidden" name="transferencia" value="<?php echo $consulta[0]['Transferencia']; ?>">
    <?php foreach ($consulta as $row) :
      $i++;
    ?>
      <input type="hidden" name="descripcion[<?php $i ?>]" value="<?php echo $row['DescripcionTipo'] . ' ' .  $row['DescripcionProducto'] ?>">
      <input type="hidden" name="cantidad[<?php $i ?>]" value="<?php echo $row['Cantidad']; ?>">
      <input type="hidden" name="precio[<?php $i ?>]" value="<?php echo $row['Precio']; ?>">
      <input type="hidden" name="alicuota[<?php $i ?>]" value="<?php echo $row['Alicuota']; ?>">
    <?php endforeach; ?>
  </form>

  <script>
    window.onload = function() {
      formulario.submit()
    }
  </script>
</body>

</html>
</html>