<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../dependencias.php';
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
  facturasresumen.IDResumenVenta = ?');
$consulta->execute([$NroVenta]);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
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
  <form id="formulario" action="http://localhost/maquinaFiscal/ticket.php" method="post">
    <input type="hidden" name="NroVenta" value="<?php echo $consulta[0]['IDResumenVenta']; ?>">
    <input type="hidden" name="IDCliente" value="<?php echo $consulta[0]['IDCliente']; ?>">
    <input type="hidden" name="rifCedula" value="<?php echo $consulta[0]['RifCliente']; ?>">
    <input type="hidden" name="razonSocial" value="<?php echo $consulta[0]['NombreCliente']; ?>">

    <input type="hidden" name="efectivo" value="<?php echo $consulta[0]['Efectivo']; ?>">
    <input type="hidden" name="tarjeta" value="<?php echo $consulta[0]['Tarjeta']; ?>">
    <input type="hidden" name="biopago" value="<?php echo $consulta[0]['BioPago']; ?>">
    <input type="hidden" name="transferencia" value="<?php echo $consulta[0]['Transferencia']; ?>">
    <?php foreach ($consulta as $row) :
      $i++;
    ?>
      <input type="hidden" name="descripcion[<?php echo $i ?>]" value="<?php echo $row['DescripcionTipo'] . ' ' .  $row['DescripcionProducto'] ?>">
      <input type="hidden" name="cantidad[<?php echo $i ?>]" value="<?php echo $row['Cantidad']; ?>">
      <input type="hidden" name="precio[<?php echo $i ?>]" value="<?php echo $row['Precio']; ?>">
      <input type="hidden" name="alicuota[<?php echo $i ?>]" value="<?php echo $row['Alicuota']; ?>">
    <?php endforeach; ?>
  </form>

  <script>
    window.onload = function() {
      formulario.submit()
    }
  </script>
</body>

</html>