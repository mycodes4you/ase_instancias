<?php

foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';    
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
include('parciales/funciones.php');
include('parciales/metodos-de-pago-3.3.php');

// ------ Creación de PDF para el Recibo de Pago Electrónico ------  
$cfdi = file_get_contents(DIR_DOCS.$axml);
$xml = new DOMDocument();
$xml->loadXML($cfdi) or die("\n\n\nXML no valido");

$Comprobante = $xml->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0);
$Emisor = $xml->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Emisor')->item(0);
$Receptor = $xml->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Receptor')->item(0);
$Pago = $xml->getElementsByTagNameNS('http://www.sat.gob.mx/Pagos', 'Pago')->item(0);
$Timbre = $xml->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital', 'TimbreFiscalDigital')->item(0);

// --- extracción de valores ---
// --- nodo emisor ---
$emisor_nombre = utf8_decode($Emisor->getAttribute("Nombre"));
$emisor_rfc = utf8_decode($Emisor->getAttribute("Rfc"));
$emisor_regimen = utf8_decode($Emisor->getAttribute("RegimenFiscal"));

// -- nodo receptor --- 
$receptor_nombre = utf8_decode($Receptor->getAttribute("Nombre"));
$receptor_usocfdi = utf8_decode($Receptor->getAttribute("UsoCFDI"));
$receptor_rfc = utf8_decode($Receptor->getAttribute("Rfc"));

// --- nodo comprobante ---
$comprobante_serie = utf8_decode($Comprobante->getAttribute("Serie"));
$comprobante_folio = utf8_decode($Comprobante->getAttribute("Folio"));
$comprobante_fecha = utf8_decode($Comprobante->getAttribute("Fecha"));
$comprobante_f_pago = utf8_decode($Comprobante->getAttribute("FormaPago"));
$comprobante_moneda = utf8_decode($Comprobante->getAttribute("Moneda"));
$comprobante_subtotal = utf8_decode($Comprobante->getAttribute("SubTotal"));
$comprobante_total = utf8_decode($Comprobante->getAttribute("Total"));
$comprobante_tipoc = utf8_decode($Comprobante->getAttribute("TipoDeComprobante"));
$comprobante_metodo = utf8_decode($Comprobante->getAttribute("MetodoPago"));
$comprobante_lexp = utf8_decode($Comprobante->getAttribute("LugarExpedicion"));
$comprobante_desc = utf8_decode($Comprobante->getAttribute("Descuento"));
$comprobante_condpago = utf8_decode($Comprobante->getAttribute("CondicionesDePago"));
$comprobante_nocert = utf8_decode($Comprobante->getAttribute("NoCertificado"));

if(file_exists('particular/pdf-rpe-3.3.php')) {
	require('particular/pdf-rpe-3.3.php');
} else {
	require('parciales/pdf-rpe-3.3.php');
}

$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AliasNbPages();
$pdf->AddPage();

if(file_exists('particular/fmt-rpe-3.3.php')) {
	require('particular/fmt-rpe-3.3.php');
} else {
	require('parciales/fmt-rpe-3.3.php');
}

$pdf->Output(DIR_DOCS . $qrnom[0] . '.pdf', 'F');


if($enviacdfi == 1) {
	include('parciales/envia-cfdi.php');
} else {
	$pdf->Output();
}

?>