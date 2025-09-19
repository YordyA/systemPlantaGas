<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once '../sessionStart.php';
require_once 'despachosMain.php';



foreach (reportDespahosRealizados(['2025-01-01', '2025-12-31'], '') as $row) {
    if ($row['IDTipoDespacho'] == 1 && $row['EstadoDesp'] == 1) {
        despachosRegistrarAdministrativo([2, $row['IDDespachoResumen'], $row['IDCliente']]);
    }
}
