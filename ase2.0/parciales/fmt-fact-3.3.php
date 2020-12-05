<?php

// ------ Tipo de CFDi, folio y serie
$factura = $Comprobante->getAttribute("Serie") . $Comprobante->getAttribute("Folio");
if($comprobante_tipoc == 'E') { $nomcomprobante = 'Nota de Crédito'; $tipo = 'EGRESO';}
else { $nomcomprobante = 'Factura'; $tipo = 'INGRESO';}

// -- datos del emisor ---
$adresse = "RFC Emisor: " . $emisor_rfc . "\n" . utf8_decode('Régimen Fiscal: ') . $emisor_regimen . ' ' . utf8_decode($nomregimen[$emisor_regimen]) . "\n" . 'UUID: ' . $Timbre->getAttribute("UUID") . "\n" . 'No. Certificado: ' . $comprobante_nocert . "\n" . utf8_decode('Lugar de Expedición: ') . $comprobante_lexp . "\n" . utf8_decode('Fecha: ') . $comprobante_fecha . "\n" . utf8_decode('Tipo de Comprobante: ') . $tipo . "\n" . utf8_decode('Versión CFDi: 3.3');

$pdf->addSociete(utf8_decode('Emisor: ' . $emisor_nombre . "\n") , $adresse);
$pdf->fact_dev(utf8_decode($nomcomprobante), $factura );

// ------ Tipo de comprobante ---
// $pdf->addPageNumber(utf8_decode($tipo));

// ------ Fecha de expedición
// $pdf->addDate($comprobante_fecha);

// --- datos del receptor --- 
$receptor = "Receptor: " . $receptor_nombre . "\n" . "RFC Receptor: " . $receptor_rfc . "\n" . "UsoCFDI: " . $receptor_usocfdi . ' ' . utf8_decode($usosdecfdi[$receptor_usocfdi]);

$pdf->addClientAdresse($receptor);

// --- QR e info fiscal --- 
$qrnom = explode('.', $axml); // -- pendiente QR ---
$pdf->Image(DIR_DOCS . $qrnom[0] . '.png', 175, 50, 26, 26, 'PNG');

$pdf->Image('particular/logo-agencia.png', 155, 16, 'PNG');

// $pdf->addReglement($comprobante_lexp);
// $pdf->addEcheance($comprobante_nocert);
// --- timbre- pendiente 
// $pdf->addNumTVA($Timbre->getAttribute("UUID"));


// $pdf->addReference("Devis ... du ....");
$cols=array( "Cant"    => 18,
             "ClaveProdServ" => 20,
             "ClaveUnidad" => 20,
             utf8_decode("Descripción")  => 50,
             "Precio Unitario"      => 22,
             "Descuento" => 20,
             "Impuesto" => 20,
             "Importe" => 22 );
$pdf->addCols( $cols);
$cols=array( "Cant"    => "C",
             "ClaveProdServ"     => "L",
             "ClaveUnidad"     => "L",
             utf8_decode("Descripción")  => "L",
             "Precio Unitario"      => "R",
             "Descuento" => "R",
             "Impuesto" => "R",
             "Importe" => "R" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 87;

	$conceptos = $xml->getElementsByTagName('Concepto');
	$concuenta = 0;
	foreach($conceptos as $concepto)
		{
			$line = array( 
				"Cant"    => $concepto->getAttribute("Cantidad"),
				"ClaveProdServ" => utf8_decode($concepto->getAttribute("ClaveProdServ")),
				"ClaveUnidad" => utf8_decode($concepto->getAttribute("ClaveUnidad")) . " " . utf8_decode($concepto->getAttribute("Unidad")), 
				utf8_decode("Descripción")  => utf8_decode($concepto->getAttribute("Descripcion")));
			$unitario = number_format(floatval($concepto->getAttribute("ValorUnitario")), 2, '.', ',');
			$importe = number_format(floatval($concepto->getAttribute("Importe")), 2, '.', ',');
			$line['Precio Unitario'] = $unitario;
			$line['Importe'] = $importe;
			$line['Descuento'] = number_format(floatval($concepto->getAttribute("Descuento")), 2, '.', ',');
			$impconcept = $xml->getElementsByTagName('Traslado')->item($concuenta);
			$line['Impuesto'] = number_format(floatval($impconcept->getAttribute("Importe")), 6, '.', ',');
			$size = $pdf->addLine( $y, $line );
			$y += $size + 2;
			if($y > 270) { 
				$pdf->AddPage();
				$y = 6; 
			}
			$concuenta++;
		}

	if($y > 250) { 
		$pdf->AddPage();
		$y = 12; 
	}
	
$pdf->Line( 10, $y-2, 200, $y-2);
$postot = $y;

// -- 	Descuentos e Impuestos ---
$descuento = $comprobante_desc;
$subtotal = $comprobante_subtotal;
$nuevosub = (floatval($subtotal) - floatval($descuento));
$ImpArr = $xml->getElementsByTagName('Impuestos');
	foreach ($ImpArr as $campo) {
		if($campo->getAttribute("TotalImpuestosTrasladados") != '') {
			$iva = $iva + $campo->getAttribute("TotalImpuestosTrasladados");
		}
	}
$total = $Comprobante->getAttribute("Total");

$descuento = number_format(floatval($descuento), 2, '.', ',');
$subtotal = number_format(floatval($subtotal), 2, '.', ',');
$nuevosub = number_format(floatval($nuevosub), 2, '.', ',');
$iva = number_format(floatval($iva), 2, '.', ',');
$total = number_format(floatval($total), 2, '.', ',');
$pdf->addTotales($subtotal, $descuento, $nuevosub, $iva, $total, $postot);

include('parciales/numeros-a-letras.php');
$letra = strtoupper(letras2($Comprobante->getAttribute("Total")));
$pdf->addConletra($letra, $y);
$y = $y + 4;

$pdf->addFormaPago($Comprobante->getAttribute("FormaPago") . ' ' . utf8_decode($metodossat[$Comprobante->getAttribute("FormaPago")]), $y);
$y = $y + 4;

$pdf->addMetodoPago($Comprobante->getAttribute("MetodoPago") . ' ' . utf8_decode($metodosdepago[$Comprobante->getAttribute("MetodoPago")]), $y);
$y = $y + 4;

//$pdf->addCuentaPago($Comprobante->getAttribute("NumCtaPago"), $y);
//$y = $y + 4;

// --- Condisiones de pago ---
if($comprobante_condpago != ''){
	$pdf->addCondicionesPago($comprobante_condpago, $y);
}
$y = $y + 4;

if($y > 260) { 
	$pdf->AddPage();
	$y = 12;
}


$preg1 = "SELECT a.omite_datos_pdf FROM " . $dbpfx . "subordenes s, " . $dbpfx . "aseguradoras a WHERE s.orden_id = '$orden_id' AND s.sub_reporte = '$reporte' AND a.aseguradora_id = s.sub_aseguradora AND s.sub_estatus < '190'";
$matr1 = mysql_query($preg1) or die("ERROR: Fallo selección de aseguradora! " . $preg1);
$asegu = mysql_fetch_array($matr1);

if($asegu['omite_datos_pdf'] != '1') {
	if($reporte != '' && $reporte != '0') {
		$datos_veh = 'POLIZA: ' . $poliza . '. SINIESTRO: ' . $reporte . "\n" . 'CLIENTE: ' . utf8_decode($clie['cliente_nombre']) . ' ' . utf8_decode($clie['cliente_apellidos']) . "\n";
	}
	$datos_veh .= 'VEHICULO : ' . $veh['marca'] . ' ' . $veh['tipo'] . ' ' . $veh['modelo'] . ' PLACAS: ' . $veh['placas'] . '. VIN: ' . $veh['serie'] . "\n";
}


if($obsad != '') {
	$datos_veh .= 'Observaciones: ' . utf8_decode($obsad) . "\n";
}

$y = $y + 12; 
$pdf->addVehDatos($datos_veh, $y);

$y =$y + 16;
if($y > 260) { 
	$pdf->AddPage();
	$y = 12;
}

$pdf->addSello($Comprobante->getAttribute("Sello"), $y);

$y =$y + 16;
if($y > 260) { 
	$pdf->AddPage();
	$y = 12;
}

$pdf->addSelloSat($Timbre->getAttribute("SelloSAT"), $y);

$cadena_sat = '||' . $Timbre->getAttribute("version") . '|' . $Timbre->getAttribute("UUID") . '|' . $Timbre->getAttribute("FechaTimbrado") . '|' . $Comprobante->getAttribute("sello") . '|' . $Timbre->getAttribute("noCertificadoSAT") . '||';

$y =$y + 16;
if($y > 250) { 
	$pdf->AddPage();
	$y = 12;
}

$pdf->addCadenaSat($Timbre->getAttribute("Version"), $Timbre->getAttribute("UUID"), $Timbre->getAttribute("FechaTimbrado"), $Timbre->getAttribute("RfcProvCertif"), $Comprobante->getAttribute("Sello"), $Timbre->getAttribute("NoCertificadoSAT"), $y);

if($prueba != '') {
	$pdf->temporaire($prueba);
}


?>
