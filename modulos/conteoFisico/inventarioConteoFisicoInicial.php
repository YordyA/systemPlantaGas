<?php
require_once '../main.php';
require_once '../sessionStart.php';
require_once 'inventarioConteo.php';

$_SESSION['conteo'] = array();

$verificar = VerificarConteoInicial([$_SESSION['PlantaGas']['IDPlanta'], date('Y-m-d')]);
if ($verificar->rowCount() > 0) {
    $alerta = array(
        "alerta" => "simple",
        "titulo" => "¡Ocurrio un error inesperado!",
        "texto" => "Ya Existe Pesaje Inicial Del Dia",
        "tipo" => "error"
    );
    echo json_encode($alerta);
    exit();
} else {
    foreach (ConsultarArticulosConteoFisico([$_SESSION['PlantaGas']['IDPlanta']]) as $row) {
        $_SESSION['conteo'][] = [
            'id_producto' => $row['IDInventario'],
            'medida'      => 'GALONES',
            'descripcion' => $row['DescripcionAlmacen'],
            'existencia'  => floatval($row['Cantidad']),
            'cantidad'    => floatval(0.000),
            'diferencia'  => floatval(0.000)
        ];
    }
    echo json_encode(true);
}
