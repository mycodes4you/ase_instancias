<?php
$instancia = $_GET['instancia'];
include('../../' . $instancia . '.autoshop-easy.com/private_html/particular/config.php');
// echo $instancia . ': ' . $servidor . ' -> '.$dbusuario .' -> ' . $dbclave;
mysql_connect($servidor,$dbusuario,$dbclave)  or die('Falló la conexion a la DB');
mysql_select_db($dbnombre) or die('Falló la seleccion la DB');
include('../../' . $instancia . '.autoshop-easy.com/private_html/particular/estatus.php');
$pregunta = "SELECT a.al_preventivo, a.al_critico, o.orden_id, o.orden_estatus, o.orden_alerta, o.orden_fecha_acordada, o.orden_fecha_recepcion, o.orden_ref_pendientes, o.orden_fecha_ultimo_movimiento FROM " . $dbpfx . "alertas a, " . $dbpfx . "ordenes o WHERE o.orden_estatus = a.al_estatus AND o.orden_estatus < '90' AND a.al_categoria = o.orden_categoria";
$matriz = mysql_query($pregunta) or die("ERROR: Fallo seleccion! ".$pregunta);
$lunes = date('w');
while($alerta = mysql_fetch_array($matriz)) {
	$ahora = time();
	$cambio_estatus = '';
	$alerta['al_preventivo'] = $alerta['al_preventivo'] * 3600;
	$alerta['al_critico'] = $alerta['al_critico'] * 3600;
//	$recepcion = strtotime($alerta['orden_fecha_recepcion']);
	if($lunes == 1 && $ahora > (strtotime($alerta['orden_fecha_ultimo_movimiento']) + 86400)) {
		$alerta['al_preventivo'] = $alerta['al_preventivo'] + 86400;
		$alerta['al_critico'] = $alerta['al_critico'] + 86400;
	}
	if($alerta['orden_estatus'] >= 30 && $alerta['orden_estatus'] <= 40) {
		$alarma = 4;
	} else {
		if($alerta['orden_ref_pendientes']=='0') {
			$inicio = strtotime($alerta['orden_fecha_ultimo_movimiento']);
			$tt= $ahora - $inicio;
			if ($tt < $alerta['al_preventivo']) {
				$alarma = 0;
			} elseif($tt < $alerta['al_critico']) {
				$alarma = 1;
			} else {
				$alarma = 2;
			}
		} elseif($alerta['orden_ref_pendientes']== '1') {
			$alarma = 5;
		} else {
			$alarma = 3;
		}
	}
	if ($alarma != $alerta['orden_alerta']) {
		$actualiza = "UPDATE " . $dbpfx . "ordenes SET orden_alerta = '$alarma'" . $cambio_estatus . " WHERE orden_id ='" . $alerta['orden_id'] . "'";
		$resultado = mysql_query($actualiza) or die($actualiza);
	}
}
?>