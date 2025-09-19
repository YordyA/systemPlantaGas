<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once '../facturacion/FacturacionMain.php';
require_once './vendor/autoload.php';

$consulta = conexion()->prepare('SELECT
  *
FROM
  facturasresumen
  INNER JOIN clientes ON facturasresumen.IDCliente = clientes.IDCliente
  INNER JOIN facturasdetalle ON facturasresumen.NVenta = facturasdetalle.NVenta
  INNER JOIN articulosdeinventario ON facturasdetalle.IDProducto = articulosdeinventario.IDArticulo
  INNER JOIN facturasmediopago ON facturasresumen.NVenta = facturasmediopago.NVenta
  INNER JOIN tasadolarhistorial ON facturasresumen.Fecha = tasadolarhistorial.Fecha
WHERE
  facturasresumen.NVenta = ?
  AND facturasresumen.NFacturaFiscal = 0
  AND facturasresumen.IDSucursal = ?
  AND facturasdetalle.IDSucursal = ?
  AND facturasmediopago.IDSucursal = ?');
$consulta->execute(
  [
    Desencriptar($_GET['id']),
    $_SESSION['PlantaGas']['IDPlanta'],
    $_SESSION['PlantaGas']['IDPlanta'],
    $_SESSION['PlantaGas']['IDPlanta']
  ]
);
$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$excel = new SpreadSheet;
$hoja_activa = $excel->getActiveSheet();
$hoja_activa->setTitle($consulta[0]['NVenta']);

$fila = 1;
$hoja_activa->mergeCells('B' . $fila . ':G' . $fila);
$hoja_activa->setCellValue('B' . $fila++, $_SESSION['PlantaGas']['Planta']);

$hoja_activa->getColumnDimension('A')->setWidth(2);
$hoja_activa->getColumnDimension('B')->setWidth(30);
$hoja_activa->getColumnDimension('C')->setWidth(15);
$hoja_activa->getColumnDimension('D')->setWidth(15);
$hoja_activa->getColumnDimension('E')->setWidth(15);
$hoja_activa->getColumnDimension('F')->setWidth(15);
$hoja_activa->getColumnDimension('G')->setWidth(15);

$hoja_activa->mergeCells('B' . $fila . ':D' . $fila);
$hoja_activa->setCellValue('B' . $fila++, 'Fecha: ' . $consulta[0]['Fecha']);
$hoja_activa->mergeCells('B' . $fila . ':D' . $fila);
$hoja_activa->setCellValue('B' . $fila++, 'Nro Nota Entrega: ' . $consulta[0]['NVenta']);
$hoja_activa->mergeCells('B' . $fila . ':D' . $fila);
$hoja_activa->setCellValue('B' . $fila++, 'TASA REFERENCIAL: ' . $consulta[0]['TasaCambiariaHistorial']);
$fila++;
$hoja_activa->mergeCells('B' . $fila . ':D' . $fila);
$hoja_activa->setCellValue('B' . $fila++, 'RIF / CEDULA: ' . $consulta[0]['RifCliente']);
$hoja_activa->mergeCells('B' . $fila . ':D' . $fila);
$hoja_activa->setCellValue('B' . $fila++, 'RAZON SOCIAL: ' . $consulta[0]['NombreCliente']);
$fila += 1;

//$hoja_activa->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($styleArrayy);
$hoja_activa->setCellValue('B' . $fila, 'DESCRIPCION');
$hoja_activa->setCellValue('C' . $fila, 'CANTIDAD');
$hoja_activa->setCellValue('D' . $fila, 'PRECIO BS');
$hoja_activa->setCellValue('E' . $fila, 'PRECIO USD');
$hoja_activa->setCellValue('F' . $fila, 'SUBTOTAL BS');
$hoja_activa->setCellValue('G' . $fila++, 'SUBTOTAL USD');

foreach ($consulta as $row) {
$hoja_activa->setCellValue('B' . $fila, $row['DescripcionArticulo']);
$hoja_activa->setCellValue('C' . $fila, $row['Cantidad']);
$hoja_activa->setCellValue('D' . $fila, $row['Precio']);
$hoja_activa->setCellValue('E' . $fila, round($row['Precio'] / $row['TasaCambiariaHistorial'], 2));
$hoja_activa->setCellValue('F' . $fila, '=C' . $fila . '*D' . $fila);
$hoja_activa->setCellValue('G' . $fila, '=C' . $fila . '*E' . $fila);
  $fila++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $consulta[0]['NombreCliente'] . ' - NOTA ENTREGA NRO ' . $consulta[0]['NVenta'] . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;