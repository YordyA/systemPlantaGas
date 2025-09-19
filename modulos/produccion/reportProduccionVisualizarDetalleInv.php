<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'produccionMain.php';

$IDProduccionResumen = desencriptar($_GET['id']);

$totalHarina = 0;
$totalAfrecho = 0;
$totalBarrido = 0;
$totalDescarte = 0;
$totalFecula = 0;
$totalImpurezas = 0;
$totalPico = 0;


$html = '';
foreach (produccionConsultarSubProductosXID([$IDProduccionResumen]) as $row) {
  $totalHarina += $row['Harina'];
  $totalAfrecho += $row['Afrecho'];
  $totalBarrido += $row['Barrido'];
  $totalDescarte += $row['Descarte'];
  $totalFecula += $row['Fecula'];
  $totalImpurezas += $row['Impurezas'];
  $totalPico += $row['Pico'];


  $html .= '<tr>';
  $html .= '<td>' . $row['Fecha'] . '</td>';
  $html .= '<td>' . number_format($row['Harina'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['Afrecho'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['Barrido'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['Descarte'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['Fecula'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['Impurezas'], 2, ',', '.') . '</td>';
  $html .= '<td>' . number_format($row['Pico'], 2, ',', '.') . '</td>';
  $html .= '<td>
  <a class="btn btn-lg" href="index.php?vista=produccionAnularSubProductos&id=' . $row['IDProduccionResumen'] . '&f=' . encriptar($row['Fecha']) . '">
    <i class="lni lni-cross-circle"></i>
  </a>
</td>';
  $html .= '</tr>';
}

$html .= '<tr>';
$html .= '<td class="text-center text-bold">T O T A L E S</td>';
$html .= '<td class="text-bold">' . number_format($totalHarina, 2) . '</td>';
$html .= '<td class="text-bold">' . number_format($totalAfrecho, 2) . '</td>';
$html .= '<td class="text-bold">' . number_format($totalBarrido, 2) . '</td>';
$html .= '<td class="text-bold">' . number_format($totalDescarte, 2) . '</td>';
$html .= '<td class="text-bold">' . number_format($totalFecula, 2) . '</td>';
$html .= '<td class="text-bold">' . number_format($totalImpurezas, 2) . '</td>';
$html .= '<td class="text-bold">' . number_format($totalPico, 2) . '</td>';
$html .= '</tr>';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
