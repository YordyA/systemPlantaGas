<?php
require_once '../main.php';
require_once '../dependencias.php';
require_once 'tasaReferencialMain.php';

// --- URL Y PETICIÓN ---
$url = "https://www.bcv.org.ve/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignorar SSL
$html = curl_exec($ch);
curl_close($ch);

if (!$html) {
    die("Error al obtener la página");
}

// --- PARSEO DEL HTML ---
$dom = new DOMDocument();
libxml_use_internal_errors(true); // evitar warnings de HTML mal formado
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

// 1. Ubicar el DIV principal con id="dolar"
$dolarDiv = $xpath->query('//div[@id="dolar"]');

if ($dolarDiv->length == 0) {
  exit();
}

    // 2. Dentro de ese DIV buscar el <div class="col-sm-6 col-xs-6 centrado"><strong>
    $strongNode = $xpath->query('.//div[contains(@class, "col-sm-6") and contains(@class, "centrado")]/strong', $dolarDiv[0]);

    if ($strongNode->length == 0) {
		
    }
        $tasa = str_replace(',', '.', trim($strongNode[0]->nodeValue));
        $tasa = round(floatval($tasa), 4);
        echo "Tasa oficial USD: " . number_format($tasa, 4) . "\n";

tasaReferenciaRegistrar(
	[
		date('Y-m-d'),
		$tasa
	]
);
