<?php
foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';   
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';

include('parciales/funciones.php');
if (!isset($_SESSION['usuario']) || $_SESSION['codigo'] >= '2000') {
	redirigir('usuarios.php');
}

/*  ----------------  Nombres de aseguradoras   ------------------- */

		$consulta = "SELECT aseguradora_id, aseguradora_logo, autosurtido, aseguradora_nic, prov_def, prov_dde FROM " . $dbpfx . "aseguradoras ORDER BY aseguradora_id";
		$arreglo = mysql_query($consulta) or die("ERROR: Fallo aseguradoras!");
		while ($aseg = mysql_fetch_array($arreglo)) {
			define('ASEGURADORA_' . $aseg['aseguradora_id'], $aseg['aseguradora_logo']);
			define('ASEGURADORA_NIC_' . $aseg['aseguradora_id'], $aseg['aseguradora_nic']);
			$autosurt[$aseg['aseguradora_id']] = $aseg['autosurtido'];
			$prov_def[$aseg['aseguradora_id']] = $aseg['prov_def'];
			$prov_dde[$aseg['aseguradora_id']] = $aseg['prov_dde'];
		}
		define('ASEGURADORA_0', 'particular/logo-particular.png');
		define('ASEGURADORA_NIC_0', 'Particular');

//  ---------------- Nombres de Usuarios ------------------- 

	$pregus = "SELECT usuario, nombre, apellidos, activo FROM " . $dbpfx . "usuarios WHERE (rol09 ='1' || rol10='1') AND activo ='1'";
	$matrus = mysql_query($pregus) or die("ERROR: Fallo selección de usuarios!");
	while($usu = mysql_fetch_array($matrus)) {
		$usur[$usu['usuario']] = $usu['nombre'] . ' ' . $usu['apellidos'];
	}


if (($accion==='refacciones') || ($accion==='registrar') || ($accion==='imprimir') || ($accion==='asignar') || ($accion==='asigna') || ($accion==='reasignar') || ($accion==='surtir') || ($accion==='procesar') || ($accion==='presupuesto') || ($accion==='diagnosticar')) {
	/* no cargar encabezado */
} else {
	include('idiomas/' . $idioma . '/proceso.php');
	include('parciales/encabezado.php');
	echo '	<div id="body">'."\n";
	include('parciales/menu_inicio.php');
	echo '		<div id="principal">'."\n";
}

if ($accion==="enviar") {

	$funnum = 1070000;

//	echo 'Estamos en la sección enviar';
	$preg0 = "SELECT sub_reporte, sub_aseguradora, orden_id FROM " . $dbpfx . "subordenes WHERE sub_orden_id = '$sub_orden_id'";
   $mat0 = mysql_query($preg0) or die("ERROR: Fallo seleccion!");
	$seg = mysql_fetch_array($mat0);
	$orden_id = $seg['orden_id'];

	echo '			<form action="proceso.php?accion=procesar" method="post" enctype="multipart/form-data">
			<table cellpadding="0" cellspacing="0" border="0" class="agrega">
				<tr><td colspan="2"><span class="alerta">' . $mensaje . '</span></td></tr>
				<tr class="cabeza_tabla"><td colspan="2">&nbsp;<input type="hidden" name="reporte" value="' . $seg['sub_reporte'] . '" /><input type="hidden" name="aseguradora" value="' . $seg['sub_aseguradora'] . '" /></td></tr>'."\n";
	echo '				<tr><td>Agregar archivo o imagen escaneada del presupuesto autorizado</td><td style="text-align:left;"><input type="file" name="pres_auto" size="30" /></td></tr>'."\n";

	$pregunta = "SELECT orden_fecha_promesa_de_entrega FROM " . $dbpfx . "ordenes WHERE orden_id = '$orden_id'";
	$matriz = mysql_query($pregunta) or die("ERROR: Fallo seleccion!");
	$dato = mysql_fetch_array($matriz);

	require_once("calendar/tc_calendar.php");

	if($valor['mantenerfpe'][0] != 1 ) {
		echo '		<tr><td>Fecha estimada para su entrega: </td><td style="text-align:left;">'."\n";
		//instantiate class and set properties
			$myCalendar = new tc_calendar("promesa", true);
			$myCalendar->setPath("calendar/");
			$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		if ($dato['orden_fecha_promesa_de_entrega']!=NULL) {
			$f_promesa = strtotime($dato['orden_fecha_promesa_de_entrega']);
			$myCalendar->setDate(date("d", $f_promesa), date("m", $f_promesa), date("Y", $f_promesa));
		} else {
			$myCalendar->setDate(date("d"), date("m"), date("Y"));
		}
			$myCalendar->dateAllow(date("Y-m-d"), "2020-12-31");
			$myCalendar->disabledDay("sun");
			$myCalendar->setYearInterval(2011, 2020);
			$myCalendar->setAutoHide(true, 5000);

			//output the calendar
			$myCalendar->writeScript();
		echo '					</td></tr>'."\n";
	} else {
		echo '		<tr><td>Fecha estimada para su entrega: </td><td style="text-align:left;"><input type="hidden" name="promesa" value="' . $dato['orden_fecha_promesa_de_entrega'] . '" />' . $dato['orden_fecha_promesa_de_entrega'] . '</td></tr>'."\n";
	}

	if($valautcap == 1) {
		echo '		<tr><td>Fecha en que fue autorizada la valuación: </td><td style="text-align:left;">'."\n";
		//instantiate class and set properties
		$myCalendar = new tc_calendar("valaut", true);
		$myCalendar->setPath("calendar/");
		$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		$myCalendar->setDate(date("d"), date("m"), date("Y"));
		$myCalendar->dateAllow("2015-01-31", "2020-12-31");
		$myCalendar->disabledDay("sun");
		$myCalendar->setYearInterval(2015, 2020);
		$myCalendar->setAutoHide(true, 5000);

		//output the calendar
		$myCalendar->writeScript();

		echo '					</td></tr>'."\n";
	}

	echo '		<tr class="cabeza_tabla"><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2"><input type="hidden" name="orden_id" value="' . $orden_id . '" /><input type="hidden" name="sub_orden_id" value="' . $sub_orden_id . '" />' . $orden_id . '</td></tr>
		<tr><td colspan="2" style="text-align:left;"><input type="submit" value="Enviar" /></td></tr>
		</tr>
	</table>
	</form>'."\n";
}

elseif($accion==='procesar') {

	if (validaAcceso('1070005', $dbpfx) == '1' || $_SESSION['rol05']=='1' || (($_SESSION['rol04']=='1' || $_SESSION['rol06']=='1' || $_SESSION['rol07']=='1') && $aseguradora == '0')) {
		$error = 'no';
	} else {
		redirigir('usuarios.php?mensaje=Acceso sólo para Asesores o Valuadores: ingresar Usuario y Clave correcta');
	}

  	$parametros='orden_id = ' . $orden_id;
	$nombre_archivo = basename($_FILES['pres_auto']['name']);
  	$nombre_archivo = limpiarstring($nombre_archivo);
	$nombre_archivo = $orden_id . '-' . time() . '-' . $nombre_archivo;
	if (move_uploaded_file($_FILES['pres_auto']['tmp_name'], DIR_DOCS . $nombre_archivo)) {
		$sql_data_array = array('orden_id' => $orden_id,
			'doc_nombre' => 'Presupuesto Autorizado',
			'doc_clasificado' => '1',
			'doc_archivo' => $nombre_archivo);
		ejecutar_db($dbpfx . 'documentos', $sql_data_array, 'insertar');
		sube_archivo();
	} elseif($preaut == 1) {
		bitacora($orden_id, "Preautorizado - no se agregó el comprobante de autorización", $dbpfx);
	} else {
		$mensaje = "Ocurrió algún error al subir el archivo. No pudo guardarse.<br>";
		$error = 'si';
	}
   if ($error === 'no') {
		$pregunta2 = "SELECT * FROM " . $dbpfx . "subordenes WHERE sub_reporte = '$reporte' AND sub_aseguradora = '$aseguradora' AND sub_estatus < '190' AND orden_id = '$orden_id' ";
		$matriz2 = mysql_query($pregunta2) or die("ERROR: Fallo seleccion!");
		$dedu = 0;
		while ($sub = mysql_fetch_array($matriz2)) {
			if((($sub['sub_estatus'] > '112' && $sub['sub_estatus'] < '130') && $sub['sub_estatus'] != '121') || $sub['sub_estatus'] < '104') {
				$area = $sub['sub_area'];
				$parametros= " sub_orden_id = '" . $sub['sub_orden_id'] . "'";
				$sql_data = array();
				$preg3 = "SELECT seg_tipo FROM " . $dbpfx . "seguimiento WHERE sub_orden_id = '" . $sub['sub_orden_id'] . "' AND seg_opr_apoyo IS NULL ORDER BY seg_id DESC LIMIT 1";
				$matr3 = mysql_query($preg3) or die("ERROR: Fallo selección de seguimientos! " . $preg3);
				$fila3 = mysql_num_rows($matr3);

				if($fila3 == '1') {
					$seg = mysql_fetch_array($matr3);
					if($seg['seg_tipo'] == '1') { $sql_data['sub_estatus'] = '109'; }
					elseif($seg['seg_tipo'] == '2') { $sql_data['sub_estatus'] = '108'; }
					elseif($seg['seg_tipo'] == '5') { $sql_data['sub_estatus'] = '110'; }
					else { $sql_data['sub_estatus'] = '111'; }
				} else {
					if($metodo=='c') {
						$sql_data['sub_estatus'] = '104';
					} else {
						$sql_data['sub_estatus'] = '103';
					}
					if($valautcap == 1) {
						$sql_data['sub_fecha_valaut'] = $valaut;
					}
				}
				if(count($sql_data) > 0) {
					ejecutar_db($dbpfx . 'subordenes', $sql_data, 'actualizar', $parametros);
					actualiza_suborden ($orden_id, $area, $dbpfx);
				}
				if($sub['sub_deducible'] > $dedu) { $dedu = $sub['sub_deducible']; }
			}
		}
		$sql_data_array = array('orden_fecha_ultimo_movimiento' => date('Y-m-d H:i:s'),
			'orden_fecha_presupuesto' => date('Y-m-d H:i:s'),
			'orden_fecha_promesa_de_entrega' => $promesa);
		$parametros='orden_id = ' . $orden_id;
		ejecutar_db($dbpfx . 'ordenes', $sql_data_array, 'actualizar', $parametros);
		bitacora($orden_id, 'Ajuste de Fecha promesa de entrega al autorizar la reparación de tareas de ' . constant('ASEGURADORA_NIC_' . $aseguradora), $dbpfx);
		actualiza_orden ($orden_id, $dbpfx);
		if($notidedu == '1' && $aseguradora > 0) {
			include('particular/notifica_deducibles.php');
		}
		redirigir('ordenes.php?accion=consultar&orden_id=' . $orden_id);
	} else {
		redirigir('proceso.php?accion=enviar&sub_orden_id=' . $sub_orden_id . '&mensaje=' . $mensaje);
	}
}

elseif ($accion==="asignar" || $accion==="reasignar") {

	$funnum = 1070010;
	$resultado = validaAcceso($funnum, $dbpfx);
	if($resultado == '1' || $_SESSION['rol04']=='1' || $_SESSION['rol07']=='1') {
		$mensaje = '';
	} else {
		$_SESSION['msjerror'] = $lang['Acceso no autorizado'];
		redirigir('usuarios.php');
	}

	$error = 'no'; $num_cols = 0;
	if ($sub_orden_id!='') {
		$pregunta = "SELECT usuario, nombre, apellidos, inicio, fin, comida FROM " . $dbpfx . "usuarios WHERE acceso = '0' AND rol09 = '1' ";
		$matriz = mysql_query($pregunta) or die("ERROR: Fallo seleccion de agenda!");
		$num_cols = mysql_num_rows($matriz);
		$preg = "SELECT orden_id, sub_area, sub_descripcion, sub_presupuesto, sub_horas_programadas, sub_estatus FROM " . $dbpfx . "subordenes WHERE sub_orden_id = '" . $sub_orden_id . "'";
		$matr = mysql_query($preg) or die("ERROR: Fallo seleccion de suborden!");
		$sub = mysql_fetch_array($matr);
	} else {
		$error = 'si'; $mensaje .= 'No se encontró la Tarea: ' . $sub_orden_id . '<br>';
	}
	if(is_null($sub['sub_horas_programadas']) || $sub['sub_horas_programadas'] == '' || $sub['sub_horas_programadas'] == '0' || $sub['sub_horas_programadas'] == '00:00:00') {
		$error = 'si'; $mensaje .= 'No hay carga de trabajo programada para la Tarea: ' . $sub_orden_id . '<br>';
	}
//	echo $pregunta;
	if ($num_cols>0 && $error == 'no') {
		include('idiomas/' . $idioma . '/proceso.php');
		include('parciales/encabezado.php');
		echo '	<div id="body">';
		include('parciales/menu_inicio.php');
		echo '		<div id="principal">';
		echo '		<form action="proceso.php?accion=reasignar" method="post" enctype="multipart/form-data" name="reasignar">'."\n";
		echo '		<table cellspacing="0" cellpadding="0" border="0" class="izquierda">'."\n";
		echo '			<tr>
				<td>Tarea: ' . $sub_orden_id . '. </td>
				<td>Área: ' . constant('NOMBRE_AREA_' . strtoupper($sub['sub_area'])) . '. </td>
				<td>Descripción: ' . $sub['sub_descripcion'] . '. </td>
				<td style="text-align:right;" Presupuesto: >' . money_format('%n', $sub['sub_presupuesto']) . '. </td>
				<td style="text-align:right;">Duración: ' . $sub['sub_horas_programadas'] . '.</td>
				<td style="text-align:right;">' . $lang['Tiempo Extra'] . '<input type="checkbox" name="hextra" value="1" ';
		if($hextra == '1') { echo 'checked '; }
		echo 'onchange="document.reasignar.submit()" /><input type="hidden" name="sub_orden_id" value="' . $sub_orden_id . '" /></td>
			</tr>
		</table></form>'."\n";
		echo '			<form action="proceso.php?accion=asigna" method="post" enctype="multipart/form-data">'."\n";
		echo '			<table cellspacing="0" cellpadding="0" border="1" class="centrado">
				<tr><td colspan="29">Seleccionar la hora de inicio para el Operador al que desea asignar la Tarea.';
		if($accion === 'reasignar') {
			echo '<br><span style="font-weight:bold; color: red;">La asignación actual se indica en color ROJO</span><input type="hidden" name="reasignar" value="1">';
		}
		echo '</td></tr>'."\n";
		echo '				<tr><td colspan="2">Operador</td><td colspan="2">7</td><td colspan="2">8</td><td colspan="2">9</td><td colspan="2">10</td><td colspan="2">11</td><td colspan="2">12</td><td colspan="2">13</td><td colspan="2">14</td><td colspan="2">15</td><td colspan="2">16</td><td colspan="2">17</td><td colspan="2">18</td><td colspan="2">19</td><td>Ext</td></tr>'."\n";

		$hoy = date('w');
		if($hoy==6) {
			$ma = mktime(0, 0, 0, date("m")  , date("d")+2, date("Y"));
		} else {
			$ma = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
		}
		$hoy = date('Y-m-d'); $ma = date('Y-m-d', $ma);
		while($usr = mysql_fetch_array($matriz)) {
			echo '				<tr><td colspan="29" style="height:10px;"></td></tr>'."\n";
			echo '				<tr class="claro"><td rowspan="2" style="text-align:left;">' . $usr['nombre'] . ' ' . $usr['apellidos'] . '</td><td>HOY:</td>';
			$preg0 = "SELECT a.sub_orden_id, a.orden_id, a.seg_inicio, a.segmentos, s.sub_estatus FROM " . $dbpfx . "agenda a, " . $dbpfx . "subordenes s WHERE a.sub_orden_id = s.sub_orden_id AND a.operador = '" . $usr['usuario'] . "' AND a.fecha_inicio = '" . $hoy . "' AND a.segmentos > '0' ORDER BY seg_inicio";
//			echo $preg0;
			$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de agenda!");
			$num_tareas = mysql_num_rows($matr0);

//       ===================== Posibilidad de mostrar únicamente los horarios laborales de cada Operador   ==================
			if($hextra != "1") {
//				echo '<td colspan="'; echo $usr['inicio'] - 1; echo '" style="width:'; echo ($usr['inicio'] -1) * 25; echo 'px;"></td>';
				$inicio = $usr['inicio'];
				$fin = $usr['fin'];
			} else {
				$inicio = 1;
				$fin = 26;
			}
			unset($seg);
			if($num_tareas > 0) {
				while($tarea = mysql_fetch_array($matr0)) {
					$seg[$tarea['seg_inicio']] = array(
						'dura' => $tarea['segmentos'],
						'orden_id' => $tarea['orden_id'],
						'estatus' => $tarea['sub_estatus'],
						'sub_orden_id' => $tarea['sub_orden_id']
					);
				}
			}
			for($i = 1;$i <= 27; $i++) {
				if(is_array($seg[$i])) {
					if($seg[$i]['sub_orden_id'] == $sub_orden_id) {
						if($i <= $usr['comida'] && ($seg[$i]['dura'] + $i) >= $usr['comida']) {
							$dura = $seg[$i]['dura'] + 2;
						} else {
							$dura = $seg[$i]['dura'];
						}
						for($j = $i; $j < ($dura + $i); $j++ ) {
							if($j != $usr['comida'] && $j != ($usr['comida'] + 1)) {
								echo '<td class="alarma_critica"><input type="radio" name="seg_inicio" value="' . $usr['usuario'] . ':' . $hoy . ':' . $j . '" /></td>';
							} else {
								echo '<td class="alarma_critica"></td>';
							}
						}
						$i = $i + $dura - 1;
					} else {
						if($i <= $usr['comida'] && ($seg[$i]['dura'] + $i) >= $usr['comida']) {
							$dura = $seg[$i]['dura'] + 2;
						} else {
							$dura = $seg[$i]['dura'];
						}
						echo '<td colspan="' . $dura . '" class="alarma_normal">';
						$preg1 = "SELECT orden_vehiculo_placas, orden_vehiculo_tipo, orden_vehiculo_color, orden_estatus FROM " . $dbpfx . "ordenes WHERE orden_id = '" . $seg[$i]['orden_id'] . "'";
						$matr1 = mysql_query($preg1) or die("ERROR: Fallo seleccion de orden!");
						$alerta = mysql_fetch_array($matr1);
						echo '<table cellspacing="0" cellpadding="0" border="0">';
						echo '
						<tr><td style="text-align:center; font-size:9px;"><a href="proceso.php?accion=consultar&orden_id=' . $seg[$i]['orden_id'] . '#' . $seg[$i]['sub_orden_id'] . '" style=" display:block;">' . $alerta['orden_vehiculo_placas'] . '</a></td></tr>
						<tr><td style="text-align:center; font-size:9px;">' . strtoupper($alerta['orden_vehiculo_tipo']) . '</td></tr>
						<tr><td style="text-align:center; font-size:9px;">' . strtoupper($alerta['orden_vehiculo_color']) . '</td></tr>
					</table>';
						echo '</td>';
						$i = $i + $dura - 1;
					}
				} elseif($i >= $inicio && $i != $usr['comida'] && $i != ($usr['comida'] + 1) && $i <= $fin ) {
					echo '<td><input type="radio" name="seg_inicio" value="' . $usr['usuario'] . ':' . $hoy . ':' . $i . '" /></td>';
				} else {
					echo '<td></td>';
				}
			}
			echo '</tr>'."\n";
			echo '				<tr class="obscuro"><td>MAÑANA:</td>';
			$preg0 = "SELECT * FROM " . $dbpfx . "agenda WHERE operador = '" . $usr['usuario'] . "' AND fecha_inicio = '" . $ma . "' AND segmentos > '0' ORDER BY seg_inicio";
//			echo $preg0;
			$matr0 = mysql_query($preg0) or die("ERROR: Fallo seleccion de agenda mañana!");
			$num_tareas = mysql_num_rows($matr0);
			unset($seg);
			if($num_tareas > 0) {
				while($tarea = mysql_fetch_array($matr0)) {
					$seg[$tarea['seg_inicio']] = array(
						'dura' => $tarea['segmentos'],
						'orden_id' => $tarea['orden_id'],
						'estatus' => $tarea['sub_estatus'],
						'sub_orden_id' => $tarea['sub_orden_id']
					);
				}
			}
			for($i = 1;$i <= 27; $i++) {
				if(is_array($seg[$i])) {
					if($seg[$i]['sub_orden_id'] == $sub_orden_id) {
						if($i <= $usr['comida'] && ($seg[$i]['dura'] + $i) >= $usr['comida']) {
							$dura = $seg[$i]['dura'] + 2;
						} else {
							$dura = $seg[$i]['dura'];
						}
						for($j = $i; $j < ($dura + $i); $j++ ) {
							if($j != $usr['comida'] && $j != ($usr['comida'] + 1)) {
								echo '<td class="alarma_critica"><input type="radio" name="seg_inicio" value="' . $usr['usuario'] . ':' . $ma . ':' . $j . '" /></td>';
							} else {
								echo '<td class="alarma_critica"></td>';
							}
						}
						$i = $i + $dura - 1;
					} else {
						echo '<td colspan="' . $seg[$i]['dura'] . '" class="alarma_normal">';
						$preg1 = "SELECT orden_vehiculo_placas, orden_vehiculo_tipo, orden_vehiculo_color, orden_estatus FROM " . $dbpfx . "ordenes WHERE orden_id = '" . $seg[$i]['orden_id'] . "'";
						$matr1 = mysql_query($preg1) or die("ERROR: Fallo seleccion de orden!");
						$alerta = mysql_fetch_array($matr1);
						echo '<table cellspacing="0" cellpadding="0" border="0">';
						echo '
						<tr><td style="text-align:center; font-size:9px;"><a href="proceso.php?accion=consultar&orden_id=' . $seg[$i]['orden_id'] . '#' . $seg[$i]['sub_orden_id'] . '" style=" display:block;">' . $alerta['orden_vehiculo_placas'] . '</a></td></tr>
						<tr><td style="text-align:center; font-size:9px;">' . strtoupper($alerta['orden_vehiculo_tipo']) . '</td></tr>
						<tr><td style="text-align:center; font-size:9px;">' . strtoupper($alerta['orden_vehiculo_color']) . '</td></tr>
					</table>';
						echo '</td>';
						$i = $i + $seg[$i]['dura'];
					}
				} elseif($i >= $inicio && $i != $usr['comida'] && $i != ($usr['comida'] + 1) && $i <= $fin ) {
					echo '<td><input type="radio" name="seg_inicio" value="' . $usr['usuario'] . ':' . $ma . ':' . $i . '" /></td>';
				} else {
					echo '<td></td>';
				}
			}
			echo '</tr>'."\n";
			$seg_exc = '';
			echo '<input type="hidden" name="usu_ini[' . $usr['usuario'] . ']" value="' . $usr['inicio'] . '">';
			echo '<input type="hidden" name="usu_com[' . $usr['usuario'] . ']" value="' . $usr['comida'] . '">';
		}
/*		echo '				<tr><td colspan="29" style="text-align:left;"><select name="grado" size="1">
						<option value="" > Seleccione... </option>
						<option value="1" > ' . GRADO_DIFICULTAD_1 . ' </option>
						<option value="2" > ' . GRADO_DIFICULTAD_2 . ' </option>
						<option value="3" > ' . GRADO_DIFICULTAD_3 . ' </option>
					</select></td></tr>'."\n";
*/		if($sub['sub_horas_programadas'] != '') {
			$tiempo = explode(":", $sub['sub_horas_programadas']);
			$minutos = $tiempo[0] * 60;
			$segmentos = round(($minutos + $tiempo[1]) / 30);
		} else {
			$segmentos = 1;
		}
		echo '				<tr><td colspan="29" style="text-align:left;">
		<input type="hidden" name="sub_orden_id" value="' . $sub_orden_id . '">
		<input type="hidden" name="sub_estatus" value="' . $sub['sub_estatus'] . '">
		<input type="hidden" name="orden_id" value="' . $sub['orden_id'] . '">
		<input type="hidden" name="area" value="' . $sub['sub_area'] . '">
		<input type="hidden" name="segmentos" value="' . $segmentos . '">
		<input type="hidden" name="reasignar" value="1">
		<input type="submit" value="Enviar" /></td></tr></table></form>';
	} else {
		$_SESSION['msjerror'] = $mensaje;
		redirigir('proceso.php?accion=consultar&orden_id=' . $sub['orden_id']);
	}
}

elseif($accion==='asigna') {

	if(validaAcceso('1070010', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol04']=='1' || $_SESSION['rol07']=='1'))) {
		// --- Acceso autorizado
	} else {
		$_SESSION['msjerror'] = $lang['Acceso no autorizado'];
		redirigir('proceso.php?accion=consultar&orden_id=' . $orden_id . '#' . $sub_orden_id);
	}

	unset($_SESSION['proceso']);
	$_SESSION['proceso'] = array();

	$error = 'no';
	$mensaje = '';
	if (!isset($seg_inicio) || $seg_inicio == '') { $error = 'si'; $mensaje .= 'Seleccione la hora de inicio para el Operador que desea asignar.<br>'; }
	print_r($seg_inicio);
	$usuario = explode(":", $seg_inicio);
	$preg1 = "SELECT seg_inicio, segmentos FROM " . $dbpfx . "agenda WHERE fecha_inicio = '" . $usuario[1] . "' AND operador = '" . $usuario[0] . "'";
	$matr1 = mysql_query($preg1) or die("ERROR: Falló selección de agenda previa! ".$preg1);
	while($agen = mysql_fetch_array($matr1)) {
		if(($agen['seg_inicio'] >= $usuario[2] && $agen['seg_inicio'] <= ($usuario[2] + $segmentos -1)) || (($agen['seg_inicio'] + $agen['segmentos'] - 1) >= $usuario[2] && ($agen['seg_inicio'] + $agen['segmentos'] - 1) <= ($usuario[2] + $segmentos -1))) {
			$error = 'si';
			$mensaje .= 'El horario ya está ocupado por otra actividad, seleciona uno diferente.<br>';
		}
	}


   if ($error === 'no') {
		$hoy = date('w');
		if($hoy==6) {
			$ma = mktime(0, 0, 0, date("m")  , date("d")+2, date("Y"));
			$pma = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
		} elseif($hoy==5) {
			$ma = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
			$pma = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
		} else {
			$ma = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
			$pma = mktime(0, 0, 0, date("m")  , date("d")+2, date("Y"));
		}
		if($reasignar==1) {
			$parme = " sub_orden_id = '" . $sub_orden_id . "' ";
			ejecutar_db($dbpfx . 'agenda', '', 'eliminar', $parme);
		}

		$hoy = date('Y-m-d'); $ma = date('Y-m-d', $ma); $pma = date('Y-m-d', $pma);
		$preg1 = "SELECT inicio, fin, comida FROM " . $dbpfx . "usuarios WHERE usuario = '" . $usuario[0] . "'";
		$matr1 = mysql_query($preg1) or die("ERROR: Falló seleccion de horarios de operario! ".$preg1);
		$hoper = mysql_fetch_array($matr1);
		if($hextra == 1) { $hinicio = 1; $limite = 26; } else { $hinicio = $hoper['inicio']; $limite = $hoper['fin']; }
		if($segmentos=='0') { $segmentos = 1; }
		if(($usuario[2] + ($segmentos - 1)) <= $limite) {
			$sql_data_array = array('sub_orden_id' => $sub_orden_id,
				'orden_id' => $orden_id,
				'operador' => $usuario[0],
				'fecha_inicio' => $usuario[1],
				'seg_inicio' => $usuario[2],
				'segmentos' => $segmentos);
			ejecutar_db($dbpfx . 'agenda', $sql_data_array, 'insertar');
		} elseif(($usuario[2] + $segmentos) > $limite) {
			$seg = $limite - ($usuario[2] - 1);
			$sql_data_array = array('sub_orden_id' => $sub_orden_id,
				'orden_id' => $orden_id,
				'operador' => $usuario[0],
				'fecha_inicio' => $usuario[1],
				'seg_inicio' => $usuario[2],
				'segmentos' => $seg);
			ejecutar_db($dbpfx . 'agenda', $sql_data_array, 'insertar');
			if($segmentos > ($seg + $limite)) {
				if($usuario[1] == $hoy) {
					$sql_data_array = array('sub_orden_id' => $sub_orden_id,
						'orden_id' => $orden_id,
						'operador' => $usuario[0],
						'fecha_inicio' => $ma,
						'seg_inicio' => $hinicio,
						'segmentos' => '26');
					ejecutar_db($dbpfx . 'agenda', $sql_data_array, 'insertar');
					$segmax = $segmentos - ($seg + $limite);
					if($segmax > 24) { $segmax=24;}
					$sql_data_array = array('sub_orden_id' => $sub_orden_id,
						'orden_id' => $orden_id,
						'operador' => $usuario[0],
						'fecha_inicio' => $pma,
						'seg_inicio' => $hinicio,
						'segmentos' => $segmax);
					ejecutar_db($dbpfx . 'agenda', $sql_data_array, 'insertar');
				} else {
					$sql_data_array = array('sub_orden_id' => $sub_orden_id,
						'orden_id' => $orden_id,
						'operador' => $usuario[0],
						'fecha_inicio' => $pma,
						'seg_inicio' => $hinicio,
						'segmentos' => '26');
					ejecutar_db($dbpfx . 'agenda', $sql_data_array, 'insertar');
				}
			} else {
				$segmax = $segmentos - $seg;
				if($usuario[1] == $hoy) {
					$sql_data_array = array('sub_orden_id' => $sub_orden_id,
						'orden_id' => $orden_id,
						'operador' => $usuario[0],
						'fecha_inicio' => $ma,
						'seg_inicio' => $hinicio,
						'segmentos' => $segmax);
					ejecutar_db($dbpfx . 'agenda', $sql_data_array, 'insertar');
				} else {
					$sql_data_array = array('sub_orden_id' => $sub_orden_id,
						'orden_id' => $orden_id,
						'operador' => $usuario[0],
						'fecha_inicio' => $pma,
						'seg_inicio' => $hinicio,
						'segmentos' => $segmax);
					ejecutar_db($dbpfx . 'agenda', $sql_data_array, 'insertar');
				}
			}
		}
		unset($sql_data_array);
		$parametros = 'sub_orden_id = ' . $sub_orden_id;
		$sql_data = array();
/*		if($sub_estatus == '103') {
			$sql_data['sub_estatus'] = 104;
//			$sql_data['sub_fecha_asignacion'] = date('Y-m-d H:i:s'); //  Campo reutilizado para registro de fecha de presupusto
		} elseif($sub_estatus == '101') {

// ------------------------- se cambió estatus de 122 a 127 para obviar diagnóstico

			$sql_data['sub_estatus'] = 127;
		}
*/
//		$sql_data['sub_controlista'] = $_SESSION['usuario']; // Campo reutilizado para registro de usuario de presupuesto
		$sql_data['sub_grado_dificultad'] = $grado;
		$sql_data['sub_operador'] = $usuario[0];
//			print_r($sql_data);
		ejecutar_db($dbpfx . 'subordenes', $sql_data, 'actualizar', $parametros);
		unset($sql_data);
		actualiza_orden($orden_id, $dbpfx);
		redirigir('proceso.php?accion=consultar&orden_id=' . $orden_id . '#' . $sub_orden_id);
   } else {
		$_SESSION['msjerror'] = $mensaje;
		if($reasignar == 1) {
			redirigir('proceso.php?accion=reasignar&sub_orden_id=' . $sub_orden_id);
		} else {
			redirigir('proceso.php?accion=asignar&sub_orden_id=' . $sub_orden_id);
		}
   }
}

elseif ($accion==="consultar") {
	
	$funnum = 1070025;

	$infomon = validaAcceso('1070065', $dbpfx);  // Valida acceso a mostrar información monetaria.
	$partmon = validaAcceso('1045075', $dbpfx);  // Valida acceso a mostrar información monetaria de tareas particulares.
	$asignoper = validaAcceso('1070010', $dbpfx); // Valida permiso para asignar Operarios a la Tarea.

	include("parciales/consulta-tareas.php");
}

elseif ($accion==="imprimir") {

	$funnum = 1070030;

	if (($_SESSION['rol04']=='1') || ($_SESSION['rol07']=='1') || ($_SESSION['rol05']=='1') || ($_SESSION['rol06']=='1')) {
		 $mensaje = 'Acceso autorizado';
	} else {
		redirigir('usuarios.php?mensaje=Acceso sólo para Jefe de Taller o Supervisor, ingresar Usuario y Clave correcta');
	}

//	echo 'Estamos en la sección imprimir';
	include('idiomas/' . $idioma . '/proceso.php');
	include('parciales/encabezado.php');
	echo '	<div id="body">';
	include('parciales/menu_inicio.php');
	echo '		<div id="principal">';
	include('particular/hoja_viajera.php');

}

elseif ($accion==="refacciones") {

	if(validaAcceso('1070035', $dbpfx) == 1) {
		// Acceso permitido por permiso de ususario
	} elseif($solovalacc != '1' && $_SESSION['rol08']=='1') {
		// Acceso permitido por rol
	} else {
		 redirigir('usuarios.php?mensaje=Acceso sólo para Almacén, ingresar Usuario y Clave correcta');
	}

//	echo 'Estamos en la sección refacciones';
	$mensaje = '';
	$error = 'si'; $num_cols = 0;
	if ($sub_orden_id != '') {
		$pregunta = "SELECT op_id, op_tangible, prod_id, op_cantidad, op_costo, op_nombre, op_surtidos, op_recibidos, sub_orden_id, op_pedido FROM " . $dbpfx . "orden_productos WHERE sub_orden_id = '$sub_orden_id' AND op_tangible > '0' AND op_tangible < '3' AND op_ok = '1'";
		$matriz = mysql_query($pregunta) or die("ERROR: Fallo selección de productos!");
		$num_cols = mysql_num_rows($matriz);
		$preg2 = "SELECT orden_id, sub_area FROM " . $dbpfx . "subordenes WHERE sub_orden_id = '" . $sub_orden_id . "'";
		$matr2 = mysql_query($preg2) or die("ERROR: Fallo selección de ordenes! " . $preg2);
		$ord = mysql_fetch_array($matr2);
		$orden_id = $ord['orden_id'];
		$veh = datosVehiculo($orden_id, $dbpfx);
		$error = 'no';
	} elseif ($pedido_id > '0') {
		$preg3 = "SELECT sub_orden_id FROM " . $dbpfx . "orden_productos WHERE op_pedido = '$pedido_id'";
		$matr3 = mysql_query($preg3) or die("ERROR: Fallo selección de productos! ". $preg3);
		$sub = mysql_fetch_array($matr3);
		$sub_orden_id = $sub['sub_orden_id'];
		$pregunta = "SELECT op_id, op_tangible, prod_id, op_cantidad, op_costo, op_nombre, op_surtidos, op_recibidos, sub_orden_id, op_pedido FROM " . $dbpfx . "orden_productos WHERE sub_orden_id = '$sub_orden_id' AND op_tangible > '0' AND op_tangible < '3' AND op_pedido > '0'";
		$matriz = mysql_query($pregunta) or die("ERROR: Fallo selección de productos! ". $pregunta);
		$num_cols = mysql_num_rows($matriz);
		$preg2 = "SELECT orden_id, sub_area FROM " . $dbpfx . "subordenes WHERE sub_orden_id = '" . $sub_orden_id . "'";
		$matr2 = mysql_query($preg2) or die("ERROR: Fallo selección de ordenes! " . $preg2);
		$ord = mysql_fetch_array($matr2);
		$orden_id = $ord['orden_id'];
		$veh = datosVehiculo($orden_id, $dbpfx);
		$error = 'no';
	} else {
		$_SESSION['msjerror'] = 'No se especificó tarea para entrega de refacciones.';
		redirigir('index.php');
	}

	include('idiomas/' . $idioma . '/proceso.php');
	include('parciales/encabezado.php');
	echo '	<div id="body">';
	include('parciales/menu_inicio.php');
	echo '		<div id="principal">';

//	echo $pregunta;
	if ($num_cols>0) {
		$j=0;
		echo '		<br><form action="proceso.php?accion=surtir" method="post" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" border="0" class="agrega">
			<tr class="cabeza_tabla"><td colspan="2" style="text-align:left;">Entregar los productos requeridos para ejecutar la Tarea ' . $sub_orden_id . ' de ' . constant('NOMBRE_AREA_'.$ord['sub_area']) . '<br>
			de la OT ' . $orden_id . ' para el vehículo: ' . $veh['completo'] . '</td></tr>
			<tr><td style="text-align:left;">'."\n";
		echo '			<table border="1" width="100%" class="izquierda">
				<tr><td>Nombre</td><td align="center">Requeridos</td><td>Disponible</td><td>Entregar</td><td>Vale</td></tr>'."\n";
		$j=0;
      while ($op = mysql_fetch_array($matriz)) {
			$entregar = $op['op_cantidad'] - $op['op_surtidos'];
     		echo '				<tr>
					<td><input type="hidden" name="nombre[' . $j . ']" value="' . $op['op_nombre'] . '" />' . $op['op_nombre'] . '</td>
					<td style="text-align:center;"><input type="hidden" name="op_id[' . $j . ']" value="' . $op['op_id'] . '" /><input type="hidden" name="pend[' . $j . ']" value="' . $entregar . '" />' . $op['op_cantidad'] . '<input type="hidden" name="tangible[' . $j . ']" value="' . $op['op_tangible'] . '" /></td>'."\n";
			if($op['prod_id'] > 0) {
				if($entregar > 0) {
					$preg0 = "SELECT prod_cantidad_disponible, prod_costo FROM " . $dbpfx . "productos WHERE prod_id = '".$op['prod_id']."'";
					$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de productos!");
					$prod = mysql_fetch_array($matr0);
					if($prod['prod_cantidad_disponible'] > '0') {
						echo '					<td style="text-align:center;">' . $prod['prod_cantidad_disponible'] . '</td>'."\n";
						if( $entregar >= $prod['prod_cantidad_disponible'] ) {
							$entregar = $prod['prod_cantidad_disponible'];
						}
						echo '					<td style="text-align:center;"><input type="text" name="surtir[' . $j . ']" value="' . $entregar . '" size="4" style="text-align:right;" /><input type="hidden" name="prod_id[' . $j . ']" value="' . $op['prod_id'] . '" /><input type="hidden" name="costo[' . $j . ']" value="' . $prod['prod_costo'] . '" /></td>'."\n";
					} else {
						echo '					<td style="text-align:center;">0</td>'."\n";
						echo '					<td style="text-align:center;">0</td>'."\n";
					}
				} else {
					echo '					<td style="text-align:center;">0</td>'."\n";
					echo '					<td style="text-align:center;">0</td>'."\n";
				}
			} else {
				if($op['op_recibidos'] > $op['op_surtidos']) {
					$dispo = $op['op_recibidos'] - $op['op_surtidos'];
					echo '					<td style="text-align:center;">' . $dispo . '</td><td style="text-align:center;">'."\n";
					if( $entregar >= $dispo ) {
						$entregar = $dispo;
					}
					echo '					<input type="text" name="surtir[' . $j . ']" value="' . $entregar . '" size="4" style="text-align:right;" /><input type="hidden" name="costo[' . $j . ']" value="' . $op['op_costo'] . '" />';
					echo '</td>'."\n";
				} else {
					echo '					<td style="text-align:center;">0</td>'."\n";
					echo '					<td style="text-align:center;">0</td>'."\n";
				}
			}
			$preg1 = "SELECT ent_id FROM " . $dbpfx . "entregas_productos WHERE op_id = '".$op['op_id']."'";
			$matr1 = mysql_query($preg1) or die("ERROR: Fallo selección de entregas! ".$preg1);
			$fila1 = mysql_num_rows($matr1);
			echo '					<td style="text-align:center;">';
			if($fila1 > 0) {
				$ent = mysql_fetch_array($matr1);
				echo '<a href="proceso.php?accion=impentrega&entrega=' . $ent['ent_id'] . '">' . $ent['ent_id'] . '</a>';
			}
			echo '				</td></tr>'."\n";
			$j++;
		}
		echo '			</table></td></tr>'."\n";
		echo '			<tr><td colspan="2" style="text-align:left;">Entregar a: '."\n";
		echo '				<select name="operador" size="1">
						<option value="" > Seleccione... </option>'."\n";
		$pregunta2 = "SELECT usuario, nombre, apellidos FROM " . $dbpfx . "usuarios WHERE acceso = '0' AND rol09 = '1' AND activo = '1' ORDER BY nombre";
		$matriz2 = mysql_query($pregunta2) or die("ERROR: Fallo seleccion!");
		while ($usuario = mysql_fetch_array($matriz2)) {
			$usunom = $usuario['nombre'] . ' ' . $usuario['apellidos'];
			echo '						<option value="' . $usuario['usuario'] . '|' . $usunom . '">' . $usunom . '</option>'."\n";
		}
		echo '					</select></td>'."\n";
		echo '				</tr>'."\n";

		echo '		<tr class="cabeza_tabla"><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
			<input type="hidden" name="sub_orden_id" value="' . $sub_orden_id . '" />
		</td></tr>
		<tr><td colspan="2" style="text-align:left;"><input type="submit" value="Enviar" /></td></tr>'."\n";
		echo '		</table></form>'."\n";

	} else {
		echo '<p>No hay productos pendientes por surtir para la Tarea: ' . $sub_orden_id . '</p>';
	}
}

elseif ($accion==="surtir") {

	if(validaAcceso('1070035', $dbpfx) == 1) {
		// Acceso permitido por permiso de ususario
	} elseif($solovalacc != '1' && $_SESSION['rol08']=='1') {
		// Acceso permitido por rol
	} else {
		 redirigir('usuarios.php?mensaje=Acceso sólo para Almacén, ingresar Usuario y Clave correcta');
	}

	if($operador == '') {
		$_SESSION['msjerror'] = 'Por favor seleccione el Operador al que entrega las refacciones.<br>';
		redirigir('proceso.php?accion=refacciones&sub_orden_id=' . $sub_orden_id);
	}

	$pregunta = "SELECT orden_id, sub_aseguradora FROM " . $dbpfx . "subordenes WHERE sub_orden_id = '$sub_orden_id'";
	$matriz = mysql_query($pregunta) or die("ERROR: Fallo selección! ". $pregunta);
	$sub = mysql_fetch_array($matriz);
	$mensaje = '';
	$pedch = '';
	$error = 'si'; $num_cols = 0;
	$oper = explode('|', $operador);

	$sql_data = array('orden_id' => $sub['orden_id'],
		'sub_orden_id' => $sub_orden_id,
		'ent_operador' => $oper[0],
		'ent_usuario' => $_SESSION['usuario']);
	$ent_id = ejecutar_db($dbpfx . 'entregas', $sql_data, 'insertar');
	bitacora($sub['orden_id'], 'Entrega de partes y consumibles ' . $ent_id, $dbpfx);

	foreach($surtir as $i => $v) {
		if($pend[$i] < $v) { $v = $pend[$i]; }
		if($v > 0) {
			$pregup = "SELECT op_surtidos FROM " . $dbpfx . "orden_productos WHERE op_id = '" . $op_id[$i] . "'";
			$matrup = mysql_query($pregup);
			$up = mysql_fetch_array($matrup);
			$disp = $up['op_surtidos'] + $regr['op_cantidad'];
			$parme = " op_id = '" . $op_id[$i] . "' ";
			$sqdat = ['op_surtidos' => $disp];
			ejecutar_db($dbpfx . 'orden_productos', $sqdat, 'actualizar', $parme);
			unset($sqdat);

			$preg0 = "UPDATE " . $dbpfx . "orden_productos SET op_surtidos = op_surtidos + '$v'";
			if($v == $pend[$i]) { $preg0 .= ", op_fecha_surtida = '" . date('Y-m-d H:i:s') . "'"; }
			$preg0 .= " WHERE op_id = '" . $op_id[$i] . "'";
//			echo 'Actualiza OP '.$preg0.'<br>';
			$resultado = mysql_query($preg0) or die("ERROR: Fallo actualización de op productos!");
			$archivo = '../logs/' . time() . '-base.ase';
			$myfile = file_put_contents($archivo, $query . ';'.PHP_EOL , FILE_APPEND | LOCK_EX);
			if($prod_id[$i] > 0) {
				$preg1 = "UPDATE " . $dbpfx . "productos SET prod_cantidad_existente = prod_cantidad_existente - '" . $v . "', prod_cantidad_disponible = prod_cantidad_disponible - '" . $v . "' WHERE prod_id = '" . $prod_id[$i] . "'";
				$resultado = mysql_query($preg1) or die("ERROR: Fallo actualización de productos!");
				$archivo = '../logs/' . time() . '-base.ase';
				$myfile = file_put_contents($archivo, $query . ';'.PHP_EOL , FILE_APPEND | LOCK_EX);
				$sql_data_array = array('prod_id' => $prod_id[$i],
					'tipo' => 2, // Comentario de Entrega normal de pedidos.
					'evento' => 'Entrega de ' . $v . ' items al Operador ' . $oper[1] . ' para la Tarea ' . $sub_orden_id . ' de la OT ' . $sub['orden_id'],
					'usuario' => $_SESSION['usuario']);
				ejecutar_db($dbpfx . 'prod_bitacora', $sql_data_array, 'insertar');
			}
			$sql_data = array('ent_id' => $ent_id,
				'op_id' => $op_id[$i],
				'prod_id' => $prod_id[$i],
				'op_tangible' => $tangible[$i],
				'cantidad' => $v,
				'costo' => 0);
			ejecutar_db($dbpfx . 'entregas_productos', $sql_data, 'insertar');

// ----------- Asientos contables ----------------->
			$asiento = $costo[$i] * $v;
			if($asientos == 1) {
/*				$poliza = regPoliza('1', 'Entrega de refacción ' . $op_id[$i] . ' al Operador ' . $oper[0]);

				$resultado = regAsiento('0', '0', '1', $poliza['ciclo'], $poliza['polnum'], '1070040', 'Entrega de refacción ' . $op_id[$i] . ' al Operador ' . $oper[0], $asiento, $sub['orden_id']);
				$resultado = regAsiento('0', '1', '1', $poliza['ciclo'], $poliza['polnum'], '1050020', 'Entrega de refacción ' . $op_id[$i] . ' al Operador ' . $oper[0], $asiento, $sub['orden_id']);
*/			}

			// ----------- Verificar si el op_prducto pertenece a un paquete -----------
			$preg_info = "SELECT prod_id FROM " . $dbpfx . "orden_productos WHERE op_id = '" . $op_id[$i] . "' AND prod_id > 0";
			$matr_info = mysql_query($preg_info) or die ("Falló! " . $preg_info);
			$prod_id = mysql_num_rows($matr_info);

			if($prod_id == 1){ // ---- Se debe de consultar el registro de pendientes ---
				$preg_pendientes = "SELECT * FROM " . $dbpfx . "prods_pendientes WHERE op_id = '" . $op_id[$i] . "'";
				$matr_pendientes = mysql_query($preg_pendientes) or die("ERROR: Fallo selección de prods_pendientes! " . $preg_pendientes);
				$info_pendientes = mysql_fetch_assoc($matr_pendientes);
				// --- Aumentar los entregados ---
				$entregados = $info_pendientes['prods_pendiente_entregados'] + $v;
				//echo $entregados . '<br>';
				unset($sql_data);
				$sql_data = [
					'prods_pendiente_entregados' => $entregados,
				];
				$parametros = " op_id = '" . $op_id[$i] . "' ";
				ejecutar_db($dbpfx . 'prods_pendientes', $sql_data, 'actualizar', $parametros);
			}

// ----------- Crear registro en Almacén de Chatarra y Pedido a Operario -------------------------
			if($chatarra_alm != '' && $tangible[$i] == '1') {
				$sql_data = array('prod_marca' => constant('ASEGURADORA_NIC_' . $sub['sub_aseguradora']),
					'prod_nombre' => $nombre[$i],
					'prod_tangible' => 3,
					'prod_cantidad_pedida' => $v,
					'prod_almacen' => $chatarra_alm,
					'prod_precio' => ($costo[$i] * $prechat));
				$chat_id = ejecutar_db($dbpfx . 'productos', $sql_data, 'insertar');
				$param = "prod_id = '$chat_id'";
				$sql_data = array('prod_codigo' => $sub['orden_id'] . 'X' . $chat_id);
				ejecutar_db($dbpfx . 'productos', $sql_data, 'actualizar', $param);

/* ------------ Crear pedidos de Chatarra --------------------*/

				if($pedch == '') {
					$fpromped = dia_habil('2');
					$sql_array = array('prov_id' => $oper[0],
						'orden_id' => $sub['orden_id'],
						'pedido_tipo' => 9,
						'fecha_promesa' => $fpromped,
						'pedido_estatus' => 5,
						'fecha_pedido' => date('Y-m-d H:i:s'),
						'usuario_pide' => $_SESSION['usuario']);
					$pedch = ejecutar_db($dbpfx . 'pedidos', $sql_array, 'insertar');
				}
				$sql_data = array('prod_id' => $chat_id,
					'op_codigo' => $sub['orden_id'] . 'X' . $chat_id,
					'op_nombre' => $nombre[$i],
					'sub_orden_id' => $sub_orden_id,
					'op_pedido' => $pedch,
					'op_cantidad' => $v,
					'op_costo' => $costo[$i],
					'op_tangible' => 3,
					'op_fecha_promesa' => $fpromped,
					'op_autosurtido' => 0);
				ejecutar_db($dbpfx . 'orden_productos', $sql_data, 'insertar');
			}
		}
	}
	redirigir('proceso.php?accion=impentrega&entrega=' . $ent_id);
}

elseif ($accion==="diagnosticar") {

	if(validaAcceso('1070050', $dbpfx) == 1 || ($solovalacc != '1' && $_SESSION['rol04']=='1')) {
		// Acceso permitido por rol
	} else {
		 redirigir('usuarios.php?mensaje=Acceso no autorizado.');
	}

	$error = 'si'; $num_cols = 0;
	if ($sub_orden_id!='') {
		$pregunta = "SELECT * FROM " . $dbpfx . "subordenes WHERE sub_orden_id = '$sub_orden_id' AND sub_estatus <= '130'";
		$matriz = mysql_query($pregunta) or die("ERROR: Fallo seleccion! " . $pregunta);
		$num_cols = mysql_num_rows($matriz);
		$sub = mysql_fetch_array($matriz);
		$error = 'no';
	}

	if($sub['recibo_id'] > 0) { $error = 'sí'; $mensaje .= $lang['YaHayRD'] . '<br>'; }
	if($sub['sub_descuento'] > 0) { $error = 'sí'; $mensaje .= $lang['SubDesc'] . '<br>'; }
	if($sub['sub_estatus'] == 109 || $sub['sub_estatus'] == 110) { $error = 'sí'; $mensaje .= $lang['OprTrab'] . '<br>'; }

//	echo $pregunta;
	if ($num_cols > 0 && $error == 'no') {
		include('idiomas/' . $idioma . '/proceso.php');
		include('parciales/encabezado.php');
		echo '	<div id="body">';
		include('parciales/menu_inicio.php');
		echo '		<div id="principal">';
		echo '	<br><form action="proceso.php?accion=diagnostica" method="post" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" border="0" class="agrega">';
		echo '		<tr class="cabeza_tabla"><td colspan="2">Tareas por Asignar</td></tr>
		<tr><td colspan="2">
			<table cellpadding="0" cellspacing="0" border="0" class="izquierda">
				<tr><td>Sub Orden</td><td>Area</td><td>Descripción</td><td>Asignar a:</td></tr>'."\n";
		mysql_data_seek($matriz, 0);
		while ($sub = mysql_fetch_array($matriz)) {
			$orden_id = $sub['orden_id'];
			$sub_estatus = $sub['sub_estatus'];
			echo '				<tr>
					<td>' . $sub['sub_orden_id'] . '</td>
					<td>' . constant('NOMBRE_AREA_' . strtoupper($sub['sub_area'])) . '</td>
					<td>' . $sub['sub_descripcion'] . '</td>';
			echo '					<td><select name="operador[]" size="1">
						<option value="" > Seleccione... </option>'."\n";
			$pregunta2 = "SELECT usuario, nombre, apellidos, horas_programadas, activo FROM " . $dbpfx . "usuarios WHERE acceso = '0' AND rol09 = '1' AND activo = '1' ORDER BY nombre";
			$matriz2 = mysql_query($pregunta2) or die("ERROR: Fallo seleccion!");
			while ($usuario = mysql_fetch_array($matriz2)) {
				$horas=0;
				$carga= explode (":", $usuario['horas_programadas']);
				$progra = explode (":", $sub['sub_horas_programadas']);
				$minutos = $carga[1] + $progra[1];
				if($minutos>59) {
					$horas=1; $minutos=$minutos-60;
				}
				if($minutos==0) {$minutos='00';}
				$nueva_carga = ($carga[0] + $progra[0] + $horas) . ':' . $minutos . ':00'; 
				echo '						<option value="' . $usuario['usuario'] . '-' . $nueva_carga . '"';
				if($sub['sub_operador'] == $usuario['usuario']) {
					echo ' selected ';
				}
				echo '>' . $usuario['nombre'] . ' ' . $usuario['apellidos'] . ' - Carga: ' . $usuario['horas_programadas'] . '</option>'."\n";
			}
			echo '					</select>'."\n";
			echo '					<input type="hidden" name="sub_orden_id[]" value="' . $sub['sub_orden_id'] . '">
					<input type="hidden" name="estatus[]" value="' . $sub['sub_estatus'] . '">
					<input type="hidden" name="operprev[]" value="' . $sub['sub_operador'] . '">
					<input type="hidden" name="area[]" value="' . $sub['sub_area'] . '">'."\n";
			echo '				</td></tr>'."\n";
		}
		echo '<tr><td colspan="4">&nbsp;</td></tr>
				<tr class="cabeza_tabla"><td colspan="4">&nbsp;</td></tr>
			</table>
			</td></tr>
		<tr><td colspan="2" style="text-align:left;">
		<input type="hidden" name="orden_id" value="' . $orden_id . '">
		<input type="submit" value="Enviar" />&nbsp;<input type="reset" name="limpiar" value="Quitar selección" /></td></tr></table></form>'."\n";
	} else {
		$sub = mysql_fetch_array($matriz);
		$_SESSION['msjerror'] = $mensaje;
		redirigir('ordenes.php?accion=consultar&orden_id=' . $sub['orden_id']);
	}
}

elseif ($accion==='diagnostica') {

	if(validaAcceso('1070050', $dbpfx) == 1 || ($solovalacc != '1' && $_SESSION['rol04']=='1')) {
		// Acceso permitido por rol
	} else {
		 redirigir('usuarios.php?mensaje=Acceso no autorizado.');
	}

	unset($_SESSION['proceso']);
	$_SESSION['proceso'] = array();
	$error = 'no';
	$mensaje = '';
	$j=0;
	foreach ($operador as $i => $v) {
		if ($v!='') { $j++; $oper=$v; $sub_orden_id = $sub_orden_id[$i]; $area = $area[$i]; $sub_estatus = $estatus[$i]; $oprev = $operprev[$i]; }
	}
	if ($j==0 || $j>1) { $error = 'si'; $mensaje .= 'Seleccione un Operador para ejecutar la Tarea. <br>'; }
   if ($error === 'no') {
// ------ Si hay un operador previamente asignado, remueve de su carga de trabajo la Tarea actual
		if($oprev > '0') {
			$preg1 = "SELECT sub_horas_programadas, sub_operador FROM " . $dbpfx . "subordenes WHERE sub_orden_id = '$sub_orden_id'";
			$matr1 = mysql_query($preg1) or die("ERROR: Fallo seleccion!");
			$sub = mysql_fetch_array($matr1);
			$pregunta2 = "SELECT usuario, horas_programadas FROM " . $dbpfx . "usuarios WHERE usuario = '$oprev'";
			$matriz2 = mysql_query($pregunta2) or die("ERROR: Fallo seleccion!");
			$usr = mysql_fetch_array($matriz2);
			$horas=0;
			$carga= explode (":", $usr['horas_programadas']);
			$progra = explode (":", $sub['sub_horas_programadas']);
			if ($carga[1] < $progra[1]) { $carga[1] = $carga[1] + 60; $carga[0] = $carga[0] -1; }
			$minutos_c = $carga[1] - $progra[1];
			if($minutos_c < 1) {$minutos_c='00';}
			$horas_c = $carga[0] - $progra[0];
			if($horas_c <= 0) {$horas_c='00';}
			$nueva_carga= array('horas_programadas' => $horas_c . ':' . $minutos_c . ':00');
			$parametros = 'usuario = ' . $usr['usuario'];
			ejecutar_db($dbpfx . 'usuarios', $nueva_carga, 'actualizar', $parametros);
		}
// ------ Agrega al operador seleccionado la nueva carga de trabajo
  		$parametros = 'sub_orden_id = ' . $sub_orden_id;
  		$usuario = explode("-", $oper);
		$sql_data_array = array('sub_operador' => $usuario[0]);
//		$sql_data_array['sub_controlista'] = $_SESSION['usuario']; // Campo reutilizado para registro de usuario de presupuesto
		if($sub_estatus == '103') { $sql_data_array['sub_estatus'] = '104'; }
		ejecutar_db($dbpfx . 'subordenes', $sql_data_array, 'actualizar', $parametros);
		unset($sql_data_array);
		$parametros = 'usuario = ' . $usuario[0];
		$sql_data_array = array('horas_programadas' => $usuario[1]);
		ejecutar_db($dbpfx . 'usuarios', $sql_data_array, 'actualizar', $parametros);
		unset($sql_data_array);
		ajusta_orden($orden_id, $dbpfx);
		redirigir('ordenes.php?accion=consultar&orden_id=' . $orden_id);
	} else {
		$_SESSION['msjerror'] = $mensaje;
		redirigir('ordenes.php?accion=consultar&orden_id=' . $orden_id);
	}
}

elseif ($accion==="impentrega") {

	$funnum = 1070035;

	if ($_SESSION['rol08']!='1') {
		redirigir('usuarios.php?mensaje=Acceso sólo para Almacén, ingresar Usuario y Clave correcta');
	}
	$preg0 = "SELECT * FROM " . $dbpfx . "entregas WHERE ent_id = '$entrega'";
	$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de entrega! ". $preg0);
	$ent = mysql_fetch_array($matr0);
	$mensaje = '';

	echo '			<div><h2>Vale de entrega de productos: ' . $entrega . '</h2></div>'."\n";
	echo '			<table cellpadding="0" cellspacing="0" border="0" class="agrega" width="800">'."\n";
	echo '				<tr class="cabeza_tabla"><td colspan="2">Productos entregados para ejecutar la Tarea ' . $ent['sub_orden_id'] . ' de la OT ' . $ent['orden_id'] . '</td></tr>
				<tr><td style="text-align:left;" colspan="2"><br>Entregado por ' . $usur[$ent['ent_usuario']] . ' en la fecha: ' . $ent['ent_fecha'] . '</td></tr>'."\n";
	$veh = datosVehiculo($ent['orden_id'], $dbpfx);
	echo '				<tr><td style="text-align:left;" colspan="2">' . $veh['completo'] . '</td></tr>'."\n";
	echo '				<tr><td style="text-align:left;" colspan="2">'."\n";
	echo '					<table border="1" width="800" class="izquierda">
						<tr><td style="text-align:center; width:80px;">Surtidos</td><td>Nombre</td><td>IDU</td></tr>'."\n";
	$preg2 = "SELECT o.op_nombre, o.op_codigo, e.cantidad, e.op_id FROM " . $dbpfx . "orden_productos o, " . $dbpfx . "entregas_productos e WHERE e.ent_id = '$entrega' AND o.op_id = e.op_id";
	$matr2 = mysql_query($preg2) or die("ERROR: Fallo selección de entrega! ". $preg2);
	while ($op = mysql_fetch_array($matr2)) {
//	print_r($op);
		echo '						<tr><td style="text-align:center; width:80px;">' . $op['cantidad'] . '</td><td>' . $op['op_nombre'] . ' ' . $op['op_codigo'] . '</td><td>' . $op['op_id'] . '</td></tr>'."\n";
	}
	echo '					</table></td></tr>'."\n";
	echo '				<tr><td style="text-align:left;"><div class="control"><a href="ordenes.php?accion=consultar&orden_id=' . $ent['orden_id'] . '"><img src="idiomas/' . $idioma . '/imagenes/regresar.png" alt="Regresar a la Orden de Trabajo" title="Regresar a la Orden de Trabajo"></a>&nbsp;<a href="javascript:window.print()"><img src="idiomas/' . $idioma . '/imagenes/imprimir.png" alt="Imprimir Recibo de Almacén" title="Imprimir Recibo de Almacén"></a></div></td></tr>'."\n";
	echo '				<tr><td style="text-align:left;"><div>
					<table cellpadding="0" cellspacing="0" border="1" width="800">
						<tr><td style="text-align:center; width:50%;" valign="top">Almacén: ' . $usur[$ent['ent_usuario']] . '<br><br><br><br><br>Fecha, nombre y firma del encargado.</td>
						<td style="text-align:center; width:50%;" valign="top">Operador: ' . $usur[$ent['ent_operador']] . '<br><br><br><br><br>Fecha, nombre y firma.</td></tr>
					</table>
				</div></td></tr>'."\n";
	echo '			</table><p>NOTAS:</p>'."\n";
}


?>
		</div>
	</div>
<?php include('parciales/pie.php'); ?>
