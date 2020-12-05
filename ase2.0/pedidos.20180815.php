<?php
foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';

include('parciales/funciones.php');

if (!isset($_SESSION['usuario'])) {
	redirigir('usuarios.php');
}

	include('idiomas/' . $idioma . '/pedidos.php');

/*  ----------------  obtener nombres de proveedores   ------------------- */

		$consulta = "SELECT prov_id, prov_nic, prov_dde, prov_iva FROM " . $dbpfx . "proveedores ORDER BY prov_nic";
		$arreglo = mysql_query($consulta) or die("ERROR: Fallo proveedores!");
		$num_provs = mysql_num_rows($arreglo);
		$provs = array();
//		$provs[0] = 'Sin Proveedor';
		while ($prov = mysql_fetch_array($arreglo)) {
			$provs[$prov['prov_id']] = array('nic' => $prov['prov_nic'], 'dde' => $prov['prov_dde'], 'iva' => $prov['prov_iva']);
		}
//		print_r($provs);

/*  ----------------  nombres de aseguradoras   ------------------- */
		$consulta = "SELECT aseguradora_id, aseguradora_logo, autosurtido, aseguradora_nic, prov_def FROM " . $dbpfx . "aseguradoras ORDER BY aseguradora_id";
		$arreglo = mysql_query($consulta) or die("ERROR: Fallo aseguradoras!");
		while ($aseg = mysql_fetch_array($arreglo)) {
			define('ASEGURADORA_' . $aseg['aseguradora_id'], $aseg['aseguradora_logo']);
			define('ASEGURADORA_NIC_' . $aseg['aseguradora_id'], $aseg['aseguradora_nic']);
			$autosurt[$aseg['aseguradora_id']] = $aseg['autosurtido'];
			$prov_def[$aseg['aseguradora_id']] = $aseg['prov_def'];
		}
//  ----------------  obtener nombres de usuarios	-------------------

		$pregusu = "SELECT usuario, nombre, apellidos, email, rol09 FROM " . $dbpfx . "usuarios ORDER BY nombre";
		$matrusu = mysql_query($pregusu) or die("ERROR: Fallo selección de usuarios!");
		while ($usr = mysql_fetch_array($matrusu)) {
			$usu[$usr['usuario']] = array('nombre' => $usr['nombre'], 'apellidos' => $usr['apellidos'], 'email' => $usr['email'], 'rol09' => $usr['rol09']);
		}


if (($accion==='factura') || ($accion==='regfact') || ($accion==='recibo') || ($accion==='asociarpf') || ($accion==='cancelafact') || ($accion==='actpcpaq') || $accion==='consalm' || $accion === 'exportarpedidos') {
	/* no cargar encabezado */
} else {

	include('parciales/encabezado.php');
	echo '	<div id="body">';
	include('parciales/menu_inicio.php');
	echo '		<div id="principal">';
}

if($accion==='listar') {

	if(validaAcceso('1050000', $dbpfx) == '1') {
		// Acceso autorizado
	} elseif($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1' || $_SESSION['rol08']=='1')) {
		// Acceso autorizado
	} else {
		redirigir('usuarios.php?mensaje=Acceso NO autorizado, ingresar Usuario y Clave correcta.');
	}

	if($Limpiar == 'Limpiar') {
		$proveedor = ''; $tpedido = ''; $pestatus = '';
	}
	
	if($proveedor != '') { $prega = " AND prov_id = '" . $proveedor . "'"; }
	if($tpedido != '') { $prega .= " AND pedido_tipo = '" . $tpedido . "'"; }
	if($pestatus != '') { $prega .= " AND pedido_estatus = '" . $pestatus . "'"; }
	if($orden_id != '') { $prega .= " AND orden_id = '" . $orden_id . "'"; }

	$preg0 = "SELECT pedido_id FROM " . $dbpfx . "pedidos WHERE pedido_estatus > '0' " . $prega;
//	echo $preg0;
  	$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de pedidos!");
	$filas = mysql_num_rows($matr0);

	$renglones = 50;
	$paginas = (round(($filas / $renglones) + 0.49999999) - 1);
  	if(!isset($pagina)) { $pagina = 0;}
	$inicial = $pagina * $renglones;

//	echo $paginas;
	$preg1 = "SELECT * FROM " . $dbpfx . "pedidos WHERE pedido_estatus > '0' " . $prega;
	$preg1 .= "ORDER BY pedido_id LIMIT " . $inicial . ", " . $renglones;
//	echo $preg1;

  	$matr1 = mysql_query($preg1) or die("ERROR: Fallo selección de productos!");
	
	echo '
	<div class="page-content">
		<div class="row"> <!-box header del título. -->
			<div class="col-md-12">
	  			<div class="content-box-header">
					<div class="panel-title">
		  				<h2>
							LISTADO DE PEDIDOS 
						</h2>
					</div>
			  	</div>
			</div>
		</div>
		<br>
		
		<div class="row">
			<div class="col-sm-12">
				<form action="pedidos.php?accion=listar" method="post" enctype="multipart/form-data">
				<div class="col-sm-3">
					<select name="proveedor" class="form-control">
						<option value="">' . $lang['FiltXProv'] . '</option>'."\n";

	foreach($provs as $k => $v) {
		echo '							<option value="' . $k . '"'; 
		if($k == $proveedor){ echo ' selected '; }
		echo '>' . $v['nic'] . '</option>'."\n";
	}

	foreach($usu as $k => $v) {
		if($v['rol09'] == '1') {
			echo '						<option value="' . $k . '"'; 
			if($k == $proveedor){ echo ' selected ';}
			echo '>' . $v['nombre'] . ' ' . $v['apellidos'] . '</option>'."\n";
		}
	}
	echo '					</select>
				</div>
				<div class="col-sm-4">
					<select name="pestatus" class="form-control">
						<option value="">' . $lang['FiltXEsta'] . '</option>'."\n";
	foreach($PedidoEstatus as $pk => $pv) {
		echo '						<option value="' . $pk . '"'; if($pestatus == $pk) { echo ' selected '; }  echo '>' . $pv . '</option>'."\n";
	}
	echo '					</select>
				</div>
				<div class="col-sm-3">
					<select name="tpedido" class="form-control">
						<option value="">Tipo de pedido</option>
						<option value="1"'; if($tpedido == '1') { echo ' selected '; }  echo '>' . TIPO_PEDIDO_1 . '</option>
						<option value="2"'; if($tpedido == '2') { echo ' selected '; }  echo '>' . TIPO_PEDIDO_2 . '</option>
						<option value="3"'; if($tpedido == '3') { echo ' selected '; }  echo '>' . TIPO_PEDIDO_3 . '</option>
						<option value="9"'; if($tpedido == '9') { echo ' selected '; }  echo '>' . TIPO_PEDIDO_9 . '</option>
					</select>
				</div>
				<div class="col-sm-1">
					<input class="form-control" placeholder="Orden ID" type="text" name="orden_id" size="4"/>
				</div>'."\n";
	// ------ Exportar lista de pedidos a hoja de cálculo ------
	if(validaAcceso('1050055', $dbpfx) || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1' || $_SESSION['rol08']=='1'))) {
		echo '				<div class="col-sm-1">
					<a href="pedidos.php?accion=exportarpedidos&proveedor=' . $proveedor . '&tpedido=' . $tpedido . '&orden_id=' . $orden_id . '&pestatus=' . $pestatus . '"><img src="idiomas/' . $idioma . '/imagenes/exportar-pedidos.png" alt="'. $lang['exportar pedidos'].'" title="'. $lang['Cuentas por Cobrar'].'" border="0"></a>
				</div>'."\n";
	}
	echo '
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-sm-1 padding">
					<input class="btn btn-danger" type="submit" name="Limpiar" value="Limpiar">
				</div>
				<div class="col-sm-6 padding">
					<input class="btn btn-success" type="submit" name="Flitrar" value="Filtrar">
				</div>
				<div class="col-sm-4 padding">
					<a href="pedidos.php?accion=listar&pagina=0&proveedor=' . $proveedor . '&tpedido=' . $tpedido . '&pestatus=' . $pestatus . '">
						Inicio
					</a>&nbsp'."\n";

	if($pagina > 0) {
		$url = $pagina - 1;
		echo '
					<a href="pedidos.php?accion=listar&pagina=' . $url . '&proveedor=' . $proveedor . '&tpedido=' . $tpedido . '&pestatus=' . $pestatus . '">
						Anterior
					</a>&nbsp;'."\n";
	}
	if($pagina < $paginas) {
		$url = $pagina + 1;
		echo '
					<a href="pedidos.php?accion=listar&pagina=' . $url . '&proveedor=' . $proveedor . '&tpedido=' . $tpedido . '&pestatus=' . $pestatus . '">
						Siguiente
					</a>&nbsp;'."\n";
	}
	echo '
					<a href="pedidos.php?accion=listar&pagina=' . $paginas . '&proveedor=' . $proveedor . '&tpedido=' . $tpedido . '&pestatus=' . $pestatus . '">
						Última
					</a>
				</div>
			</div>
		</div>
		<!-- Pintar encabezado de la tabla -->
		<div class="row">
			<div class="col-md-12">
				<div id="content-tabla">
					<table cellspacing="0" class="table-new">
						<tr>
							<th><big><b>' . $lang['Pedido'] . '</b></big></th>
							<th><big><b>' . $lang['Proveedor'] . '</b></big></th>
							<th><big><b>' . $lang['FPedido'] . '</b></big></th>
							<th><big><b>' . $lang['FRecibido'] . '</b></big></th>
							<th><big><b>' . $lang['TipoPedido'] . '</b></big></th>
							<th><big><b>' . $lang['Estatus'] . '</b></big></th>'."\n";
	
	if(validaAcceso('1050015', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1'))) {
		echo '
							<th><big><b>' . $lang['MontoPedido'] . '</b></big></th>
							<th><big><b>' . $lang['Utilidad'] . '</b></big></th>
							<th><big><b>' . $lang['MontoPagado'] . '</b></big></th>'."\n";
	}
		echo '
							<th><big><b>' . $lang['OrdenID'] . '</b></big></th>
							<th><big><b>' . $lang['Alerta'] . '</b></big></th>
						</tr>'."\n";

	$cue = 0;
	$fondo = "claro";
	while($ped = mysql_fetch_array($matr1)) {
		echo '
						<tr class="' . $fondo . '">
							<td><a href="pedidos.php?accion=consultar&pedido=' . $ped['pedido_id'] . '" target="_blank">' . $ped['pedido_id'] . '</a></td>
							<td style="text-align:left;">';
		if($ped['pedido_tipo'] == '9') {
			echo $usu[$ped['prov_id']]['nombre'] . ' ' . $usu[$ped['prov_id']]['apellidos'];
		} else {
			echo $provs[$ped['prov_id']]['nic'];
		}
		if(strtotime($ped['fecha_recibido']) >= strtotime($ped['fecha_pedido'])) {
			$fpr = date('Y-m-d', strtotime($ped['fecha_recibido']));
		} else {
			$fpr = '';
		}
		echo '</td>
							<td>' . date('Y-m-d', strtotime($ped['fecha_pedido'])) . '</td>
							<td>' . $fpr . '</td>
							<td style="text-align:left;">' . constant('TIPO_PEDIDO_'.$ped['pedido_tipo']) . '</td>
							<td style="text-align:left;">' . constant('PEDIDO_ESTATUS_'.$ped['pedido_estatus']) . '
							</td>'."\n";

		if(validaAcceso('1050015', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1'))) {
			if($ped['pedido_estatus'] >= 90 && $ped['pedido_estatus'] <= 98) {
				echo '
							<td style="text-align:right;">0</td>
							<td style="text-align:right;"></td>
							<td style="text-align:right;">'."\n";
			} else {
				echo '							<td style="text-align:right;">' . number_format(($ped['subtotal'] + $ped['impuesto']),2) . '</td>'."\n";
				$fondo_utilidad = 'rojo_tenue';
				$utilidad = number_format($ped['utilidad'],2) . '%';
				if(is_null($ped['utilidad']) || $ped['utilidad'] == '') {
					$utilidad = $lang['Incompleto'];
				} elseif($ped['utilidad'] >= $utilcompras) {
					$fondo_utilidad = '';
				}
				echo '							<td style="text-align:right;" class="' . $fondo_utilidad . '">' . $utilidad . '</td>
							<td style="text-align:right;">';
			}
			
			$preg2 = "SELECT monto FROM " . $dbpfx . "pagos_facturas WHERE pedido_id = '" . $ped['pedido_id'] . "'";
			$matr2 = mysql_query($preg2) or die("Error: falló selección de pedidos! " . $preg2);
			$montopag = 0;
			while($fact = mysql_fetch_array($matr2)) {
				$montopag = $montopag + $fact['monto'];
			}
			echo number_format($montopag,2);
			echo '</td>'."\n";
		}
		echo '
							<td><a href="ordenes.php?accion=consultar&orden_id=' . $ped['orden_id'] . '">' . $ped['orden_id'] . '</a></td>
							<td><a href="pedidos.php?accion=consultar&pedido=' . $ped['pedido_id'] . '" target="_blank">' . constant('ALARMA_'.$ped['pedido_alerta']) . '</a></td>
						</tr>'."\n";
		$cue++;
		if($fondo == 'obscuro') { $fondo = 'claro'; } else { $fondo = 'obscuro';}
	}
	echo '				
					</table>
				</div>
			</div>
		</div>
	</div>'."\n";

	
}

elseif($accion === 'exportarpedidos') {

	if($proveedor != '') { $prega = " AND prov_id = '" . $proveedor . "'"; }
	if($tpedido != '') { $prega .= " AND pedido_tipo = '" . $tpedido . "'"; }
	if($pestatus != '') { $prega .= " AND pedido_estatus = '" . $pestatus . "'"; }
	if($orden_id != '') { $prega .= " AND orden_id = '" . $orden_id . "'"; }

	$preg = "SELECT * FROM " . $dbpfx . "pedidos WHERE pedido_estatus > '0' " . $prega;
	$matr = mysql_query($preg) or die("ERROR: Fallo selección de pedidos!");
	$titulo = 'Pedidos-' . $nombre_agencia . '-' . date('Ymd', time()) . '.csv';

// -------------------   Creación de Archivo CSV   ----------------------------------

	$columna = array($lang['Pedido'], $lang['Proveedor'], $lang['FPedido'], $lang['FRecibido'], $lang['TipoPedido'], $lang['Estatus'], $lang['MontoPedido'], $lang['Utilidad'], $lang['MontoPagado'], $lang['OrdenID']);
	$fp = fopen('php://output', 'w');
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $titulo . '"');
	header('Pragma: no-cache');
	header('Expires: 0');
	fputcsv($fp, $columna);

	while($ped = mysql_fetch_array($matr)) {
		// --- Proveedor ---
		if($ped['pedido_tipo'] == '9') {
			$prov_name = $usu[$ped['prov_id']]['nombre'] . ' ' . $usu[$ped['prov_id']]['apellidos'];
		} else {
			$prov_name = $provs[$ped['prov_id']]['nic'];
		}
// ------ Pagos del pedido ------
		$preg2 = "SELECT monto FROM " . $dbpfx . "pagos_facturas WHERE pedido_id = '" . $ped['pedido_id'] . "'";
		$matr2 = mysql_query($preg2) or die("Error: falló selección de pedidos! " . $preg2);
		$montopag = 0;
		while($fact = mysql_fetch_array($matr2)) {
			$montopag = $montopag + $fact['monto'];
		}

		if($ped['pedido_estatus'] >= 90 && $ped['pedido_estatus'] <= 98){
			$total_ped = 0;
		} else{
			$total_ped = $ped['subtotal'] + $ped['impuesto'];
		}

		// --- Fecha Recibido ---
		if(strtotime($ped['fecha_recibido']) >= strtotime($ped['fecha_pedido'])) {
			$fpr = $ped['fecha_recibido'];
		} else {
			$fpr = '';
		}

		// --- Utilidad del Pedido ---
		if($ped['pedido_estatus'] >= 90 && $ped['pedido_estatus'] <= 98) {
			$utilidad = '--';
		} elseif(is_null($ped['utilidad']) || $ped['utilidad'] == '') {
			$utilidad = $lang['Incompleto'];
		} else {
			$utilidad = round($ped['utilidad'],2);
		}

		$campos = array($ped['pedido_id'], $prov_name, $ped['fecha_pedido'], $fpr, constant('TIPO_PEDIDO_'.$ped['pedido_tipo']), constant('PEDIDO_ESTATUS_'.$ped['pedido_estatus']), $total_ped, $utilidad, $montopag, $ped['orden_id']);
		fputcsv($fp, array_values($campos));
	}
	exit;
}

elseif($accion==='consultar') {
	if (validaAcceso('1050005', $dbpfx) == '1' || validaAcceso('1050022', $dbpfx) == '1') {
		$mensage='Acceso autorizado';
	} elseif($solovalacc != 1 && ($_SESSION['rol08']=='1' || $_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')) {
		$mensage='Acceso autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir('index.php');
	}

	if($pedido!='') {
		$trans = explode('|', $pedido);
		$pedido = $trans[0];
		if(!is_numeric($pedido)) {
			$trans = explode(',', $pedido);
			$pedido = $trans[0];
		}
		$preg0 = "SELECT * FROM " . $dbpfx . "pedidos WHERE pedido_id='" . $pedido . "'";
//	echo $preg0 . '<br>';
		$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de pedidos!");
		$fila0 = mysql_num_rows($matr0);
		$ped = mysql_fetch_array($matr0);
	}

	if($fila0 > 0) {
		$preg2 = "SELECT v.vehiculo_marca, v.vehiculo_tipo, v.vehiculo_modelo, v.vehiculo_placas FROM " . $dbpfx . "vehiculos v, " . $dbpfx . "ordenes o WHERE o.orden_id='" . $ped['orden_id'] . "' AND o.orden_vehiculo_id = v.vehiculo_id";
		$matr2 = mysql_query($preg2) or die("ERROR: Fallo selección de vehiculo!");
		$veh = mysql_fetch_array($matr2);
		echo '		<table cellpadding="0" cellspacing="0" border="0" class="pedidos" width="100%">
			<tr>
				<td rowspan="8" style="width:60%; vertical-align:top;"><img src="particular/logo-agencia.png" alt="' . $agencia_razon_social . '">';
		echo '					<br clear="all"><br>';
		if($ped['pedido_estatus'] == 10 && (validaAcceso('1050005', $dbpfx) == '1' || $_SESSION['rol08']=='1')) {
			echo '<img src="parciales/barcode.php?barcode=' . $pedido . '&width=300&height=60">';
		}
		echo '				</td><td style="width:40%; vertical-align:top;">
					<strong>' . $agencia_razon_social . '</strong><br>
			' . $agencia_direccion . '.<br>
			' . $agencia_colonia . ', '  . $agencia_municipio . '.<br>
			' . $agencia_cp . '. '  . $agencia_estado . '. México.<br>
			' . $agencia_telefonos . '.<br>
				</td></tr>'."\n";
		$orden_id = $ped['orden_id'];
		echo '			<tr><td><strong>Pedido: ' . $pedido . '</strong><br><div style="position: relative; display: inline-block;"><a onclick="muestraAbajoEstatus()" class="ayuda">ESTATUS: </a>' . constant('PEDIDO_ESTATUS_' . $ped['pedido_estatus']) . '<div id="AyudaEstatus" class="muestra-contenido">' . $ayuda['AyudaEstatus'] . '</div></div></td></tr>'."\n";
		echo '					<script>
						function muestraAbajoEstatus() {
							document.getElementById("AyudaEstatus").classList.toggle("mostrar");
						}
					</script>'."\n";
		if($orden_id != '999999997') {
			echo '			<tr><td><strong>Orden de Trabajo: ' . $ped['orden_id'] . '</strong></td></tr>'."\n";
			echo '			<tr><td><strong>Vehículo: ' . $veh['vehiculo_marca'] . ' ' . $veh['vehiculo_tipo'] . ' ' . $veh['vehiculo_modelo'] . $lang['Placas'] . $veh['vehiculo_placas'] . '</strong></td></tr>'."\n";
		} else {
			echo '			<tr><td><strong>Pedido Directo de Almacén.</strong></td></tr>'."\n";
		}
		$fondo_utilidad = 'rojo_tenue';
		$utilidad = number_format($ped['utilidad'],2) . '%';
		if($ped['utilidad'] >= $utilcompras) {
			$fondo_utilidad = '';
		} elseif(is_null($ped['utilidad'])) {
			$utilidad = $lang['Incompleto'];
		}
		echo '			<tr><td class="' . $fondo_utilidad . '">Utilidad: ' . $utilidad . '</td></tr>'."\n";		
		if($ped['pedido_tipo'] == '9') {
			echo '			<tr><td>Operario: ' . $usu[$ped['prov_id']]['nombre'] . ' ' . $usu[$ped['prov_id']]['apellidos'] . '</td></tr>
			<tr><td>Tipo de Pedido: ' . $lang['Regresa Chatarra'] . '</td></tr>'."\n";
		} else {
			echo '			<tr><td>Proveedor: ' . $provs[$ped['prov_id']]['nic'] . '</td></tr>
			<tr><td>Tipo de Pedido: ' . constant('TIPO_PEDIDO_' . $ped['pedido_tipo']) . '</td></tr>
			<tr><td><img src="idiomas/' . $idioma . '/imagenes/tipopedido-' . $ped['pedido_tipo'] . '.png" width="48" alt="' . constant('TIPO_PEDIDO_' . $ped['pedido_tipo']) . '" title="' . constant('TIPO_PEDIDO_' . $ped['pedido_tipo']) . '"></td></tr>'."\n";
		}
		echo '		</table>'."\n";
		echo '		<div class="control"><table cellpadding="3" cellspacing="0" border="0" class="izquierda">'."\n";
//		echo '<input type="hidden" name="prov_id" value="' . $ped['prov_id'] . '" />'."\n";
		echo '			<tr><td>Fecha de Pedido: ' . $ped['fecha_pedido'] . '</td><td>Pedido por: ' . $usu[$ped['usuario_pide']]['nombre'] . ' ' . $usu[$ped['usuario_pide']]['apellidos'] . '</td></tr>'."\n";
		echo '		</table></div>';
		echo '<hr>';
		$preg1 = "SELECT op_id, op_cantidad, op_recibidos, op_costo, op_nombre, op_codigo, op_tangible, op_pedido, op_precio, op_fecha_promesa, op_pres, op_item_seg FROM " . $dbpfx . "orden_productos WHERE op_pedido='" . $pedido . "'";
//	echo $preg1 . '<br>';
  		$matr1 = mysql_query($preg1) or die("ERROR: Fallo selección de productos! " . $preg1);

// ------ Inicio de la tabla de productos del pedido ------
		echo '		<table cellpadding="0" cellspacing="0" border="0" ><tr><td style="vertical-align:top;">'."\n";
		$fondo = 'claro';
		echo '		<form action=';
		if($ped['pedido_estatus']==0 && (validaAcceso('1050020', $dbpfx) == '1' || $_SESSION['rol08']=='1')) {
			echo '"pedidos.php?accion=recibido';
		} elseif($ped['pedido_estatus'] < 90 && (validaAcceso('1050020', $dbpfx) == '1' || validaAcceso('1050022', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol08']=='1' || $_SESSION['rol13']=='1')))) {
			echo '"pedidos.php?accion=recibir';
		} else {
			echo '"pedidos.php?accion=consultar';
		}
		echo '" method="post" enctype="multipart/form-data">'."\n";
		echo '		<h3 align="center" style="background-color: white; color: #151553;">DETALLE DEL PEDIDO</h3>
		<table cellpadding="2" cellspacing="0" border="1" class="izquierda chica">'."\n";
		echo '			<tr><td>Cantidad<input type="hidden" name="prov_id" value="' . $ped['prov_id'] . '" /></td>'."\n";
		echo '				<td><div style="position: relative; display: inline-block;"><a onclick="NombreFPE()" class="ayuda">' . $lang['Nombre'] . '</a><div id="AyudaNombreFPE" class="muestra-contenido">' . $ayuda['NombreFPE'] . '</div></div></td>'."\n";
		echo '							<script>
								function NombreFPE() {
								document.getElementById("AyudaNombreFPE").classList.toggle("mostrar");
								}
							</script>'."\n";
		echo '				<td>Código</td>
				<td>Recibidas</td>
				<td>Por Recibir</td>
				<td>Costo Unitario</td>'."\n";
		echo '				<td><div style="position: relative; display: inline-block;"><span class="control"><a onclick="Cancelar()" class="ayuda">' . $lang['Cancelar'] . '</a></span><div id="Cancelar" class="muestra-contenido">' . $ayuda['Cancelar'] . '</div></div></td>'."\n";
		echo '							<script>
								function Cancelar() {
								document.getElementById("Cancelar").classList.toggle("mostrar");
								}
							</script>'."\n";
		echo '				<td style="width:120px;">Sub Total</td>'."\n";
		if(validaAcceso('102000', $dbpfx) == '1') {
			echo '<td style="text-align:right;">Precio de<br>Venta</td>'."\n";
		}
		echo '</tr>'."\n";
// ------ Obtener datos de facturas registradas para calcular montos y habilitar cancelación de pedidos.
		$preg4 = "SELECT * FROM " . $dbpfx . "facturas_por_pagar WHERE tipo = 1 AND doc_int_id = '" . $pedido . "' AND pagada < '2'";
		$matr4 = mysql_query($preg4) or die("ERROR: Fallo selección de facturas por pagar! " . $preg4);
		$fila4 = mysql_num_rows($matr4);
		$monto_facturas = 0;
		while($fact2 = mysql_fetch_array($matr4)) {
			$monto_facturas = $monto_facturas + $fact2['f_monto'];
		}

// ------ Obtener los pagos recibidos para determinar el máximo posible Por Pagar! y si se autoriza cancelar recibidos ------
		$preg5 = "SELECT monto, pedido_id FROM " . $dbpfx . "pagos_facturas WHERE proveedor_id = '" . $ped['prov_id'] . "' AND (pedido_id = '" . $pedido . "' OR pedido_id IS NULL)";
		$matr5 = mysql_query($preg5) or die("ERROR: Fallo selección de pagos! " . $preg5);
//		$pagos = mysql_num_rows($matr5);
		$pagado = 0; $paghuer = 0;
		while ($pagprov = mysql_fetch_array($matr5)) {
			if(!is_null($pagprov['pedido_id'])) {
				$pagado = $pagado + $pagprov['monto'];
				$pagdelped++;
			} else {
				$paghuer = $paghuer + $pagprov['monto'];
				$paghuerprov++;
			}
		}
//		echo $pagado;
		$costo = 0;
		$j=0;
		while ($prod = mysql_fetch_array($matr1)) {
// ------ Aquí se determina si se muestra o no el renglón al momento de imprimir --------
			if(($prod['op_tangible']=='8' || $prod['op_pedido']=='' || $prod['op_recibidos'] == 0 || $ped['pedido_estatus'] > 89) && $ped['pedido_tipo'] != '3') { $fondo = 'control'; }
// ------
			// --- Revisar si el Item pertenece a presupuesto ---
			$fondo_asociado = '';
			if($prod['op_pres'] == 1 && $ped['pedido_tipo'] > 1 && $prod['op_tangible'] <='2') {
				// --- consultar su asociación con los autorizados ---
				$preg_seg = "SELECT op_id, op_costo, op_precio, op_cantidad FROM " . $dbpfx . "orden_productos WHERE op_item_seg = '" . $prod['op_id'] . "'";
				$matr_seg = mysql_query($preg_seg) or die("ERROR: Fallo selección de refacciones!" . $preg_seg);
				$asociado = mysql_num_rows($matr_seg);
				if($asociado < 1){
					$fondo_asociado = 'rojo_tenue';		
				}
			}
			echo '			
				<tr class="' . $fondo . '">
					<td style="text-align:center;">
						<input type="hidden" name="op_id[' . $j . ']" value="' . $prod['op_id'] . '" />
						' . $prod['op_cantidad'] . '
					</td>
					<td class="' . $fondo_asociado . '">
						' . $prod['op_nombre'] . '
						<br>
						' . date('Y-m-d', strtotime($prod['op_fecha_promesa'])) . '
					</td>
					<td>
						' . $prod['op_codigo'] . '
					</td>
					<td style="text-align:center;">
						' . $prod['op_recibidos'] . '
					</td>'."\n";
			if($prod['op_pedido']=='') {
				echo '				
					<td style="text-align:center;" colspan="3">
						Cancelado
					</td>'."\n";
			} elseif($ped['pedido_estatus']==5 || $ped['pedido_estatus']==7) {
				echo '				
					<td style="text-align:center;">';
				if((validaAcceso('1050020', $dbpfx) == '1' || ($solovalacc != 1 && $_SESSION['rol08']=='1')) && ($prod['op_recibidos'] < $prod['op_cantidad'])) {
					echo '
						<input type="text" name="recibir[' . $j . ']" size="4"  style="text-align:right;">';
				} else {
					echo $prod['op_cantidad'] - $prod['op_recibidos'];
				}
				echo '
					</td>
					<td style="text-align:right;">';
				if(validaAcceso('1050020', $dbpfx) == '1' || ($solovalacc != 1 && $_SESSION['rol08']=='1')) {
					if($cotizar == 1) {
						echo '
						<input type="hidden" name="costo[' . $j . ']" value="' . $prod['op_costo'] . '" />' . number_format($prod['op_costo'],2);
					} else {
						echo '
						<input type="text" name="costo[' . $j . ']" size="6" value="' . number_format($prod['op_costo'],2) . '" style="text-align:right;" />';
					}
				} else {
					echo number_format($prod['op_costo'],2);
				}
				echo '
					</td>
					<td>
						<span class="control">';
/*				echo 'Recibidos: ' . $prod['op_recibidos'] . '<br>';
				echo 'Facturas: ' . $fila4 . '<br>';
				echo 'Pagos: ' . $pagdelped . '<br>'; */

				if((validaAcceso('1050020', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol08']=='1'))) && $prod['op_recibidos'] == 0 && $fila4 < 1 && $pagdelped <= 0) {
					echo $lang['Cancelar'] . '<input type="checkbox" name="quitar[' . $j . ']" value="1" />'; 

				} elseif((validaAcceso('1050020', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol08']=='1'))) && $prod['op_recibidos'] == 0 && ($fila4 > 0 || $pagdelped > 0)) {
					echo $lang['Cancelar'] . '<input type="checkbox" name="quitar[' . $j . ']" value="2" />'; 

				} elseif($prod['op_tangible'] == 8) {
					echo $lang['PorDevolver'];
				} elseif(validaAcceso('1050022', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol08']=='1' || $_SESSION['rol13']=='1')) && $prod['op_recibidos'] > 0) {
					echo $lang['Devolver'] . '<input type="checkbox" name="quitar[' . $j . ']" value="2" />';

				} else {
					echo $lang['CanNoAuth'];
				}

				echo '
						</span>
					</td>
					<td style="text-align:right;">';
				
				if($prod['op_tangible'] == 8) {
					$sbt = 0; 
				} else {
					$sbt = $prod['op_costo'] * $prod['op_cantidad'];
				}
				if(validaAcceso('1050020', $dbpfx) == '1' || $_SESSION['rol08']=='1') {
					if($cotizar == 1) {
						echo number_format($sbt,2);
					} else {
						echo '<input type="text" name="sbt[' . $j . ']" size="6" value="' . number_format($sbt,2) . '" style="text-align:right;" />';
					}
				}
				echo '</td>';
				if(validaAcceso('102000', $dbpfx) == '1') {
					echo '
					<td style="text-align:right;">
						' . number_format($prod['op_precio'],2) . '
					</td>';
				}
			} else {
				echo '
					<td style="text-align:center;">0</td>
					<td style="text-align:right;">
						' . number_format($prod['op_costo'],2) . '
					</td>
					<td>
						<span class="control">';
				if($prod['op_tangible'] == 8) {
					$pregdev = "SELECT dev_id FROM " . $dbpfx . "cambdevol_elementos WHERE op_id = '" . $prod['op_id'] . "'";
					$matrdev = mysql_query($pregdev) or die("ERROR: Fallo selección de Elementos de devoluciones!" . $pregdev);
					$elemdev = mysql_fetch_array($matrdev);
					if($elemdev['dev_id'] > 0) {
						echo '<a href="cambdevol.php?accion=contrarec&dev_id=' . $elemdev['dev_id'] . '" target="_blank">' . $lang['Devuelto'] . ' ' . $elemdev['dev_id'] . '</a>';
					} else {
						echo $lang['PorDevolver'];
					}
				} elseif($ped['pedido_estatus'] < 90 && (validaAcceso('1050022', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol08']=='1' || $_SESSION['rol13']=='1')))) {
					echo $lang['Devolver'] . '<input type="checkbox" name="quitar[' . $j . ']" value="2" />';
				} else { 
					echo $lang['CanNoAuth'];

				}
				echo '
						</span>
					</td>
					<td style="text-align:right;">';
				if($prod['op_tangible'] <= 2) { echo number_format(($prod['op_costo'] * $prod['op_cantidad']),2); }
				echo '
					</td>';
				if(validaAcceso('102000', $dbpfx) == '1') {
					echo '
					<td style="text-align:right;">
						' . number_format(($prod['op_precio'] * $prod['op_cantidad']),2) . '
					</td>';
				}
			}
			echo '
				</tr>'."\n";
			if($prod['op_tangible'] <= 2) { 
				$costo = $costo + round(($prod['op_costo'] * $prod['op_cantidad']),2);
			}
			if($fondo == 'claro') {$fondo = 'obscuro';} else {$fondo = 'claro';}
			$j++;
		}
		if(validaAcceso('1050005', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol08']=='1' || $_SESSION['rol02']=='1' || $_SESSION['rol13']=='1'))) {
			if($ped['pedido_estatus'] > 10) { $fondo = 'control'; } else { $fondo = '';}
			echo '		<tr class="' . $fondo . '"><td colspan="7" style="text-align:right;">Total antes de impuestos:</td><td style="text-align:right;">' . number_format($costo,2) . '</td></tr>';
			$iva = round(($costo * $provs[$ped['prov_id']]['iva']), 2);
			echo '		<tr class="' . $fondo . '"><td colspan="7" style="text-align:right;">IVA al ' . ($provs[$ped['prov_id']]['iva'] * 100) . '%:</td><td style="text-align:right;">' . number_format($iva,2) . '</td></tr>';
			$total = $costo + $iva;
			echo '		<tr class="' . $fondo . '"><td colspan="7" style="text-align:right;">Total:</td><td style="text-align:right;">' . number_format($total,2) . '</td></tr>';
		}

		echo '			<tr class="control"><td colspan="';
		if(validaAcceso('102000', $dbpfx) == '1') {	echo '9'; } else { echo '8'; }
		echo '" style="text-align:left;">'."\n";
		echo '					<input type="hidden" name="pedido_id" value="' . $pedido . '" />'."\n";
		echo '					<input type="hidden" name="pedido_tipo" value="' . $ped['pedido_tipo'] . '" />'."\n";
		echo '					<input type="hidden" name="orden_id" value="' . $ped['orden_id'] . '" />'."\n";
		if((validaAcceso('1050020', $dbpfx) == '1' || $_SESSION['rol08']=='1') && $ped['pedido_estatus']==0) {
			echo '					<input type="submit" value="Marcar como Recibido por Proveedor" />'."\n";
		} elseif(validaAcceso('1050020', $dbpfx) == '1' || validaAcceso('10500220', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol08']=='1' || $_SESSION['rol13']=='1'))) {
			echo '					<input type="submit" value="Aplicar" />'."\n";
		}

		if(($ped['subtotal'] + $ped['impuesto']) > $monto_facturas) { $factdoc = 0; } else { $factdoc = 1;}
		// --- reg factura ---
		if($ped['pedido_estatus'] >= 5 && $ped['pedido_estatus'] < 10 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1') && $factdoc == '0' && $ped['pedido_tipo'] == '3') {
			echo '					<input type="hidden" name="prov_id" value="' .  $ped['prov_id'] . '" /> <button type="submit" name="ppago" value="3" />Pago Adelantado</button> <input type="hidden" name="por_pagar" value="' . (($ped['subtotal'] + $ped['impuesto']) - $pagado) . '" /> <input type="hidden" name="adelanto" value="1" />'."\n";
		}

		if(validaAcceso('1070035', $dbpfx) == 1 || ($solovalacc != '1' && $_SESSION['rol08']=='1')) {
			echo '					<a href="proceso.php?accion=refacciones&pedido_id=' . $pedido . '"><button type="button">Entregar Refacciones</button></a> '."\n";
		}
		echo '				</td></tr>
			</table></form>
		</td>'."\n";

// ------ Gestionar pagos a Proveedores

		if(validaAcceso('1050015', $dbpfx) == '1' || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol08']=='1' || $_SESSION['rol13']=='1'))) {
			echo '	<td style="width:20px;"></td><td style="vertical-align:top;">'."\n";
			echo '		<div class="control">'."\n";

// --------------- Tabla para Administración de Pagos a Proveedores ----------------------
			$total_pagado = 0;
			$pag_huer = "SELECT pp.pago_tipo, pp.pago_banco, pp.pago_cuenta, pp.pago_referencia, pp.pago_documento, pp.pago_fecha, pf.pf_id, pf.pago_id, pf.fact_id, pf.monto, pf.pedido_id, pf.adelanto FROM " . $dbpfx . "pedidos_pagos pp, " . $dbpfx . "pagos_facturas pf WHERE pf.proveedor_id = '" . $ped['prov_id'] . "' AND (pf.fact_id IS NULL OR pf.fact_id < 1) AND pf.pago_id = pp.pago_id";
//			$pag_huer = "SELECT * FROM " . $dbpfx . "pagos_facturas WHERE proveedor_id = '" . $ped['prov_id'] . "' AND (fact_id IS NULL OR fact_id < 1)";
//			echo '<br>' . $pag_huer;
			$matr_pag_huer = mysql_query($pag_huer) or die("ERROR: Fallo selección de pagos no asociados! " . $pag_huer);
			$filahuer = mysql_num_rows($matr_pag_huer);
// ------ Cuenta los pago del pedido para habilitar el registro de nuevos pagos sólo despúes de aplicar los existentes.
			$pgsdlpe = 0;
			while($bschuer = mysql_fetch_array($matr_pag_huer)) {
				if($bschuer['pedido_id'] == $pedido) {
					$pgsdlpe++;
				}
			}

			echo '			<h3 align="center" style="background-color: white; color: #151553;">' . $lang['PagosProv'] . '</h3>'."\n";
			mysql_data_seek($matr4, 0);
			while($fact = mysql_fetch_array($matr4)) {
// ------ Pagos asocidos a la factura ----
				echo '			<form action="pedidos.php?accion=pagar" method="post" enctype="multipart/form-data">'."\n";
				echo '			<table cellpadding="3" cellspacing="0" border="1" class="izquierda">'."\n";
				echo '				<tr class="cabeza_tabla"><td colspan="9">Registro de Pagos de Factura: ' . $fact['fact_num'] . ' del Proveedor ' . $provs[$fact['tercero_id']]['nic'] . ' programada para el ';
				$prog_fecha = strtotime($fact['f_prog']);
				$prog_fecha = date('Y-m-d', $prog_fecha);
				echo $prog_fecha . ' por un total de ' . number_format($fact['f_monto'],2) . ' <b>FID=' . $fact['fact_id'] . '</b></td></tr>'."\n";
				$fondo = 'claro';
				echo '			<tr><td><div style="position: relative; display: inline-block;"><a onclick="QuitarPago()" class="ayuda">' . $lang['Quitar'] . '</a><div id="QuitarPago" class="muestra-contenido">' . $ayuda['QuitarPago'] . '</div></div></td>'."\n";
				echo '				<script>
								function QuitarPago() {
								document.getElementById("QuitarPago").classList.toggle("mostrar");
								}
				</script>'."\n";
				echo '				<td>Pago</td><td>Fecha</td><td>Forma de pago</td><td>Banco</td><td>Cuenta</td><td>Referencia</td><td>Documento</td><td>Monto</td></tr>'."\n";
				$pagado_fact = 0;
				$pagos_fact = 0;
				$preg3 = "SELECT pp.*, pf.monto, pf.fact_id FROM " . $dbpfx . "pedidos_pagos pp, " . $dbpfx . "pagos_facturas pf WHERE pf.pago_id = pp.pago_id";
				$matr3 = mysql_query($preg3) or die("ERROR: Fallo selección de pagos! " . $preg3);
//				echo $preg3 . '<br>';
	  			while($pag = mysql_fetch_array($matr3)) {
	  				if($fact['fact_id'] == $pag['fact_id']) {
	  					$pagado_fact = $pagado_fact + $pag['monto'];
			  			echo '			<tr><td><a href="pedidos.php?accion=desasoc_pagos&pago_id=' . $pag['pago_id'] . '&pedido_id=' . $pedido . '&fact=' . $fact['fact_id'] . '&fact_num=' . $fact['fact_num'] . '"><img src="idiomas/' . $idioma . '/imagenes/go-bottom-4.png" alt="Desasociar" title="Desasociar"></a></td><td>' . $pag['pago_id'] . '</td><td>' . date('d/m/Y', strtotime($pag['pago_fecha'])) . '</td><td>' . constant('TIPO_PAGO_' . $pag['pago_tipo']) . '</td><td>' . $pag['pago_banco'] . '</td><td>' . $pag['pago_cuenta'] . '</td><td>' . $pag['pago_referencia'] . '</td><td>';
						if($pag['pago_documento'] != '') {
							echo '<a href="' . DIR_DOCS . $pag['pago_documento'] . '" target="_blank"><img src="' . DIR_DOCS . 'documento.png" width="48" border="0"></a>';
						}
						echo '</td><td style="text-align: right;">' . number_format($pag['monto'],2) . '</td></tr>'."\n";
						$pagos_fact++;
			  		}
				}
		  		$por_pagar = $fact['f_monto'] - $pagado_fact;
				echo '				<tr><td colspan="7">';
				if($por_pagar > 0 && (validaAcceso('1050030', $dbpfx) == 1 || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')))) {
					$facturas[] = $fact['fact_id'];
					echo '					<input type="submit" name="pagar" value="Registrar pago" />&nbsp;'."\n";
				}
				if($pagos_fact == 0 && (validaAcceso('1050030', $dbpfx) == 1 || ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')))) {
					echo '					<input type="submit" name="cancelar" value="' . $lang['Cancelar Factura'] . '" />'."\n";
				}
				echo '
					<input type="hidden" name="provid" value="' . $ped['prov_id'] . '" />
					<input type="hidden" name="fact_id" value="' . $fact['fact_id'] . '" />
					<input type="hidden" name="numero" value="' . $fact['fact_num'] . '" />
					<input type="hidden" name="pedido_id" value="' . $pedido . '" />
					<input type="hidden" name="orden_id" value="' . $orden_id . '" />
					<input type="hidden" name="por_pagar" value="' . $por_pagar . '" />'."\n";
				echo '</td><td>Pagado:</td><td style="text-align:right;"><STRONG>' . number_format($pagado_fact,2) . '</STRONG></td></tr>'."\n";
				echo '</tr><td colspan="7"></td><td>Por pagar:</td><td style="text-align:right;"><STRONG>' . number_format($por_pagar,2) . '</STRONG></td></tr>'."\n";
				echo '			</table></form><hr>'."\n";

				$total_pagado = $total_pagado + $pagado_fact;
			}

// ------- Pagado del pedido (suma de pagos por factura) y pendiente por pagar --------

			$pendiente = $total - $pagado;
			$pend_x_fact = $total - $monto_facturas;
			echo '
			<table cellpadding="2" cellspacing="0" border="0" width="100%" class="agrega">
				<tr style="font-size:1.1em; font-weight:bold">
					<td colspan="2">Total pagado del pedido:</td>
					<td>$ ' . number_format($pagado,2) . '</td>
				</tr>
				<tr style="font-size:1.1em; font-weight:bold">'."\n";
			echo '					<td style="text-align:left;">&nbsp;';
			if($ped['pedido_estatus'] >= 5 && $ped['pedido_estatus'] <= 30 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1') && $factdoc == '0' && ($ped['pedido_tipo'] == '2' || $ped['pedido_tipo'] == '3')) {
				echo '<a href="pedidos.php?accion=factura&pedido_id=' . $pedido . '&orden_id=' . $ped['orden_id'] . '&por_pagar=' . $pend_x_fact . '&prov_id=' . $ped['prov_id'] . '"><button type="button">' . $lang['RegFactDoc'] . '</button></a>';
			}
			echo '</td>'."\n";
			echo '					<td>Pendiente por pagar del pedido:</td>
					<td>$ ' . number_format($pendiente,2) . '</td>
				</tr>'."\n";
			echo '
			</table>'."\n";

//			echo 'Pagos: ' . $pagos;
// -------------------- Pagos Adelantados y Huerfanos ----------------------
			mysql_data_seek($matr_pag_huer,0);
			if($filahuer > 0 && $ped['pedido_estatus'] < '90') {
				echo '		<form action="pedidos.php?accion=asociarpf" method="post" enctype="multipart/form-data">'."\n";
				echo '			<table cellpadding="2" cellspacing="0" border="1" class="izquierda" width="100%">'."\n";
				echo '				<tr class="cabeza_tabla"><td colspan="11"><div style="position: relative; display: inline-block;"><a onclick="NoAsociados()" class="ayuda">' . $lang['PagosProv'] . '</a> ' . $lang['NoAsociados'] . '<div id="NoAsociados" class="muestra-contenido">' . $ayuda['NoAsociados'] . '</div></div></td></tr>'."\n";
				echo '				<script>
								function NoAsociados() {
								document.getElementById("NoAsociados").classList.toggle("mostrar");
								}
				</script>'."\n";
				echo '			<tr><td>Eliminar</td><td>Pago</td><td>Fecha</td><td>Tipo</td><td>Forma de pago</td><td>Banco</td><td>Cuenta</td><td>Referencia</td><td>Documento</td><td>Monto</td>'."\n";
				echo '				<td><div style="position: relative; display: inline-block;"><a onclick="AsignarFID()" class="ayuda">' . $lang['AsignarFID'] . '</a> <div id="AsignarFID" class="muestra-contenido">' . $ayuda['AsignarFID'] . '</div></div></td></tr>'."\n";
				echo '				<script>
								function AsignarFID() {
								document.getElementById("AsignarFID").classList.toggle("mostrar");
								}
				</script>'."\n";
				$pagsinfact = 0;
				while($bschuer = mysql_fetch_array($matr_pag_huer)) {
					if($bschuer['pedido_id'] == $pedido && $bschuer['fact_id'] < '1') {
						$pagsinfact = 1;
					}
				}
				$fondo = 'claro';
				mysql_data_seek($matr_pag_huer, 0);
  				while($pag = mysql_fetch_array($matr_pag_huer)) {
  					if($pag['pedido_id'] == $ped['pedido_id'] || is_null($pag['pedido_id']) || $pag['pedido_id'] < '1') {
						if($pag['pedido_id'] < '1') {
							$adelanto = $lang['Huerfano'];
						} elseif($pag['adelanto'] > '0') {
							$adelanto = $lang['Adelanto'];
						} else {
							$adelanto = $lang['Normal'];
						}
	  					echo '			<tr><td style="text-align:center;">';
	  					if(validaAcceso('1050060', $dbpfx) == 1) {
		  					echo '<a href="pedidos.php?accion=eliminar_pago&pedido_id=' . $pedido  . '&pago_id=' . $pag['pago_id'] . '&monto=' . $pag['monto'] . '&orden_id=' . $orden_id . '"><img src="idiomas/' . $idioma . '/imagenes/edit-delete-6.png" alt="Eliminar pago" title="Eliminar pago"></a>';
	  					}
	  					echo '</td><td>' . $pag['pago_id'] . '</td><td>' . date('Y-m-d', strtotime($pag['pago_fecha'])) . '</td><td align="center">' . $adelanto . ' ' . $pag['adelanto'] . '</td><td>' . constant('TIPO_PAGO_' . $pag['pago_tipo']) . '</td><td>' . $pag['pago_banco'] . '</td><td>' . $pag['pago_cuenta'] . '</td><td>' . $pag['pago_referencia'] . '</td><td>';
						if($pag['pago_documento'] != '') {
							echo '<a href="' . DIR_DOCS . $pag['pago_documento'] . '" target="_blank"><img src="' . DIR_DOCS . 'documento.png" width="48" border="0"></a>';
						}
						echo '</td><td style="text-align: right;">' . number_format($pag['monto'],2) . '</td><td>'."\n";
						if($adelanto != $lang['Huerfano'] || $pagsinfact == 0) {
							echo '				<select name="fid[]" /><option value="0">Selecciona FID</option>'."\n";
							foreach($facturas as $k => $v) {
								echo '					<option value="' . $v . '">' . $v . '</option>'."\n";
							}
							echo '				</select>'."\n";
							echo '				<input type="hidden" name="pago_id[]" value="' . $pag['pf_id'] . '" />'."\n";
						}
						echo '			</td></tr>'."\n";
					}
				}
				if(count($facturas) > 0) {
					echo '				<tr><td colspan="11">
					<input type="hidden" name="pedido_id" value="' . $pedido . '" />
					<input type="hidden" name="por_pagar" value="' . $por_pagar . '" />
					<input type="submit" name="pagar" value="Asociar pagos con facturas" />';
					echo '</td></tr>'."\n";
				}
				echo '			</table></form><hr>'."\n";
				echo '		</div>'."\n";
				echo '		</td>';
			}
// --------------- Fin de Tabla para Administración de Pagos a Proveedores ---------------------- //  

			echo '		</tr>'."\n";
		}
		echo '		<tr><td colspan="2" style="text-align:left;"><div class="control"><br><br><a href="';
		if($orden_id == '999999997') {
			echo 'refacciones.php?accion=listar'; $visual= 'Regresar a la lista de Almacén';
		} else {
			echo 'refacciones.php?accion=gestionar&orden_id=' . $orden_id; $visual = 'Regresar a la Orden de Trabajo';
		}
		echo '"><img src="idiomas/' . $idioma . '/imagenes/regresar.png" alt="' . $visual . '" title="' . $visual . '"></a>'."\n";
		if($_SESSION['rol08']=='1' && $ped['pedido_estatus'] < '50') {
			echo ' <a href="refacciones.php?accion=gestiona&re_pedido=' . $pedido . '&reenvia_mail=si"><img src="idiomas/' . $idioma . '/imagenes/enviar_correo.png" alt="envíar por correo" title="envíar por correo"></a>'."\n";
		}
		echo '					</div></td></tr>'."\n";
		echo '	</table>'."\n";
		echo '	<div id="encabezado"><table cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="7">&nbsp;</td></tr>
		<tr><td colspan="7" class="cen">ES INDISPENSABLE ADJUNTAR ESTA HOJA A SU FACTURA CON SELLO Y FIRMA DE AUTORIZADO<br>LOS PRECIOS SON MÁS IVA</td></tr>	</table></div>'."\n";

	} else {
		echo 'No se encontraron pedidos con los datos proporcionados.';
	}
}

elseif($accion === 'eliminar_pago') {

	if(validaAcceso('1030115', $dbpfx) == 1) {
		$mensaje = 'Acceso Autorizado';
	} elseif($solovalacc != 1 && $_SESSION['codigo'] <= '12') {
		$mensaje = 'Acceso Autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccGerente'];
		redirigir("pedidos.php?accion=consultar&pedido=" . $pedido_id);
	}

	if($eliminar == 1) {
			$bitacora = 'El usuario ' . $_SESSION['usuario'] . ' eliminó el pago ' . $pago_id . ', del pedido ' . $pedido_id . ' con un monto de ' . $monto;
			bitacora($orden_id, $bitacora, $dbpfx);
			$parametros = " pago_id ='" . $pago_id . "'";
			ejecutar_db($dbpfx . 'pedidos_pagos', '', 'eliminar', $parametros);
			ejecutar_db($dbpfx . 'pagos_facturas', '', 'eliminar', $parametros);
// -------------------- Determinar el estatus del pedido ----------------
			actualiza_pedido($pedido_id, $dbpfx);
			redirigir("pedidos.php?accion=consultar&pedido=" . $pedido_id);

	} else {

		// --- Determinar si es pago adelantado ----
		$preg_adelanto = "SELECT adelanto, pedido_id FROM " . $dbpfx . "pagos_facturas WHERE pago_id = '" . $pago_id . "' AND adelanto IS NOT NULL";
		$matr_adelanto = mysql_query($preg_adelanto) or die("ERROR: Fallo selección de cobros adelantados! " . $preg_adelanto);
		$adelanto = mysql_num_rows($matr_adelanto);
		$pedido_asociado = mysql_fetch_assoc($matr_adelanto);
		if($adelanto > 0) {
			$pedido_adelanto[] = $pedido_asociado['pedido_id'];
		}

		// --- Determinar si tiene pagos hermanos ---
		$preg_asoc = "SELECT pago_id, fact_id, orden_id FROM " . $dbpfx . "pagos_facturas WHERE pago_id = '" . $pago_id . "' AND fact_id IS NOT NULL";
		$matr_asoc = mysql_query($preg_asoc) or die("ERROR: Fallo selección de pagos asociados! " . $preg_asoc);
		$num_asoc = mysql_num_rows($matr_asoc);

		if($num_asoc > 0) {
			$mensaje =  'El pago ' . $pago_id . ' se encuentra asociado a:<br>';
			while($pagos = mysql_fetch_array($matr_asoc)){
				$preg_info = "SELECT fact_num, doc_int_id FROM " . $dbpfx . "facturas_por_pagar WHERE fact_id = '" . $pagos['fact_id'] . "'";
				$matr_info = mysql_query($preg_info) or die("ERROR: Fallo seleccion de informacion! " . $preg_info);
				$info = mysql_fetch_array($matr_info);
				$mensaje .= 'La factura ' . $info['fact_num'] . ' en el pedido ' . $info['doc_int_id'] . '<br>';
			}
			$mensaje .= $lang['DesAsocXElim'] . '<br>';
			$_SESSION['msjerror'] = $mensaje;
			redirigir("pedidos.php?accion=consultar&pedido=" . $pedido_id);
		} else {
			echo '
			<h2>¿Estás seguro que quieres eliminar el pago ' . $pago_id . '?</h2>'."\n";
			if($adelanto > 0) {
				echo '
			<h3>Este es un pago adelantado</h3>'."\n";
			}
			echo '
			<table>
				<tr>
					<td>'."\n";
					if($adelanto > 0) {
						echo '
						<a href="pedidos.php?accion=eliminar_pago&eliminar=1&pedido_id=' . $pedido_id . '&pago_id=' . $pago_id . '&adelanto=1&monto=' . $monto . '&orden_id=' . $orden_id . '"><button type="button" class="btn btn-success">SI, eliminar pago</button></a>'."\n";
					} else {
						echo '
						<a href="pedidos.php?accion=eliminar_pago&eliminar=1&pedido_id=' . $pedido_id . '&pago_id=' . $pago_id . '&monto=' . $monto . '&orden_id=' . $orden_id . '"><button type="button" class="btn btn-success">SI, eliminar pago</button></a>'."\n";
					}
					echo '
					</td>
					<td><a href="pedidos.php?accion=consultar&pedido=' . $pedido_id . '"><button type="button" class="btn btn-danger">NO, regresar</button></a>
					</td>
				</tr>
			</table>'."\n";
		}
	}
}

elseif($accion === 'desasoc_pagos') {

	if(validaAcceso('1030115', $dbpfx) == 1 || ($solovalacc != 1 && ($_SESSION['rol02'] == '1' || $_SESSION['rol13'] == 1))) {
		$mensaje = 'Acceso Autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccGerente'];
		redirigir("pedidos.php?accion=consultar&pedido=" . $pedido_id);
	}

	if($eliminar == 1) {
			// --- desasociar cobros de "cobros y cobros facturas" ------
			$sql_data_array = [
				'fact_id' => 'null'
			];
			$parametros = " pago_id ='" . $pago_id . "' AND fact_id = '" . $fact . "'";
			ejecutar_db($dbpfx . 'pagos_facturas', $sql_data_array, 'actualizar', $parametros);
			// --- marcar factura como no pagada "facturas por cobrar" ------
			unset($sql_data_array);
			$sql_data_array = [
				'pagada' => 0,
				'f_pago' => 'null',
			];
			$parametros = " fact_id ='" . $fact . "'";
			ejecutar_db($dbpfx . 'facturas_por_pagar', $sql_data_array, 'actualizar', $parametros);
//			echo 'Pedido: ' . $pedido_id . '<br>';

// -------------------- Determinar el estatus del pedido -----------------------------
			actualiza_pedido($pedido_id, $dbpfx);
			redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	} else {
		echo '
		<h2>¿Está seguro que quiere desasociar el pago ' . $pago_id . ' de la factura ' . $fact_num . '?</h2>
		<table>
			<tr>
				<td><a href="pedidos.php?accion=desasoc_pagos&pago_id=' . $pago_id . '&eliminar=1&fact=' . $fact . '&pedido_id=' . $pedido_id . '"><button type="button" class="btn btn-success">SI, desacociar pago</button></a></td>
				<td><a href="pedidos.php?accion=consultar&pedido=' . $pedido_id . '"><button type="button" class="btn btn-danger">NO, regresar</button></a></td>
			</tr>
		</table>'."\n";
	}
}

elseif ($accion==="reenvio") {
//------ consulta información del pedido ---------------------------------
	$preg_pedido_re = "SELECT pedido_id, prov_id, orden_id, usuario_pide, pedido_estatus, pedido_tipo FROM " . $dbpfx . "pedidos WHERE pedido_id ='" . $re_pedido . "'";
	$matr_pedido_re = mysql_query($preg_pedido_re) or die("ERROR: Fallo selección de pedido! " . $preg_pedido_re);
	$consulta_pedido = mysql_fetch_array($matr_pedido_re);

//------ consulta información del proveedor ---------------------------------
	$preg_prov_re = "SELECT prov_email, prov_nic FROM " . $dbpfx . "proveedores WHERE prov_id ='" . $consulta_pedido['prov_id'] . "'";
	$matr_prov_re = mysql_query($preg_prov_re) or die("ERROR: Fallo selección de proveedor! " . $preg_prov_re);
	$consulta_prov = mysql_fetch_array($matr_prov_re);

//------ consulta información usuario que levantó pedido --------------------
	$preg_usuario_re = "SELECT email, nombre FROM " . $dbpfx . "usuarios WHERE usuario ='" . $consulta_pedido['usuario_pide'] . "'";

	$matr_usuario_re = mysql_query($preg_usuario_re) or die("ERROR: Fallo selección de usuario!");

	$consulta_usuario_re = mysql_fetch_array($matr_usuario_re);

//------ consulta refacciones de pedido --------------------
	$preg_refac_re = "SELECT op_cantidad, op_nombre, op_costo, op_codigo, op_fecha_promesa, sub_orden_id, op_id, op_doc_id FROM " . $dbpfx . "orden_productos WHERE op_pedido ='" . $consulta_pedido['pedido_id'] . "'";
	$matr1 = mysql_query($preg_refac_re) or die("ERROR: Fallo selección de refacciones! " . $preg_refac_re);

	$consulta_refa_re = mysql_fetch_array($matr1);

//------ consulta información de suborden --------------------
	$preg_suborden = "SELECT sub_siniestro, sub_reporte, sub_aseguradora, sub_poliza FROM " . $dbpfx . "subordenes WHERE sub_orden_id ='" . $consulta_refa_re['sub_orden_id'] . "'";
	$matr_suborden = mysql_query($preg_suborden) or die("ERROR: Fallo selección de suborden!");
	$consulta_suborden = mysql_fetch_array($matr_suborden);

	mysql_data_seek($matr1, 0);

//------ asignamos el tipo de pedido ----------------------

	if($consulta_pedido['pedido_tipo'] == 1) {
		$acargo = 'Pedido a Cargo de aseguradora. Siniestro: ' . $consulta_suborden['sub_siniestro'] . ', Poliza: ' . $consulta_suborden['sub_poliza'] . ', Reporte: ' . $consulta_suborden['sub_reporte'];
	} elseif($consulta_pedido['pedido_tipo'] == 2) {
		$acargo = 'Pedido compra directa a crédito ';
	} elseif($consulta_pedido['pedido_tipo'] == 3) {
		$acargo = 'Pedido compra directa contado ';
	}

	$vehiculo = datosVehiculo($consulta_pedido['orden_id'], $dbpfx);

//------ variables ---
	$para = $consulta_prov['prov_email'];
	$respondera = $consulta_usuario_re['email'];
	$texto_t_solicitud = 'Reenvío de pedido ';
	$tipo_pedido = $consulta_pedido['pedido_tipo'];
	$orden_id = $consulta_pedido['orden_id'];
	$i = $consulta_refa_re['sub_orden_id'];
}

elseif ($accion==="recibido") {

	$funnum = 1050010;

	if ($_SESSION['rol08']!='1') {
		 redirigir('usuarios.php?mensaje=Acceso sólo para Rol Almacén, ingresar Usuario y Clave correcta.');
	}
	$fpromped = dia_habil( $provs[$prov_id]['dde']);
	$parametros = "pedido_id = '" . $pedido_id . "'";
	$sql_data_array = array('pedido_estatus' => '5', 'fecha_pedido' => date('Y-m-d H:i:s'), 'fecha_promesa' => $fpromped);
	ejecutar_db($dbpfx . 'pedidos', $sql_data_array, 'actualizar', $parametros);
	bitacora($orden_id, 'Pedido ' . $pedido_id . ' aceptado por Proveedor', $dbpfx);
//	redirigir('pedidos.php?accion=consultar&pedido='.$pedido_id);
}

elseif ($accion==="recibir") {

	if($ppago == '3') {
		redirigir('pedidos.php?accion=pagar&pedido_id=' . $pedido_id . '&orden_id=' . $orden_id . '&por_pagar=' . $por_pagar .'&adelanto=1&provid=' . $prov_id);
	}


	if (validaAcceso('1050020', $dbpfx) == '1' || validaAcceso('1050022', $dbpfx) == '1') {
		$mensaje = 'Acceso autorizado';
	} elseif ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol08']=='1' || $_SESSION['rol13']=='1')) {
		$mensaje = 'Acceso autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	}

	unset($_SESSION['ped']);
	$_SESSION['ref'] = array();
	$_SESSION['ref']['mensaje']='';
	$mensaje = '';
	$error = 'no';

	$preg4 = "SELECT * FROM " . $dbpfx . "facturas_por_pagar WHERE doc_int_id = '" . $pedido_id . "' AND orden_id = '" . $orden_id . "' AND pagada < '2'";
	$matr4 = mysql_query($preg4) or die("ERROR: Fallo selección de facturas de proveedor! " . $preg4);
	$fila4 = mysql_num_rows($matr4);

	foreach($op_id as $i => $j) {
		$costo[$i] = limpiarNumero($costo[$i]);
		$sbt[$i] = limpiarNumero($sbt[$i]);
		$recibir[$i] = limpiarNumero($recibir[$i]);
		$preg0 = "SELECT op_costo, op_nombre, op_cantidad, op_recibidos, op_autosurtido, prod_id, sub_orden_id FROM " . $dbpfx . "orden_productos WHERE op_id='" . $op_id[$i] . "'";
		$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de orden_productos! " . $preg0);
		$comcost = mysql_fetch_array($matr0);
		if($sbt[$i] != ($comcost['op_cantidad'] * $costo[$i]) && $sbt[$i] > 0) {
			$costo[$i] = round(($sbt[$i] / $comcost['op_cantidad']), 6);
		}
		if($comcost['op_autosurtido'] > '1' && ($costo[$i] == '' || $costo[$i] == '0') && $recibir[$i] > '0' && $sincosto < '1') {
			$_SESSION['msjerror'] = 'El Item ' . $comcost['op_nombre'] . ' es compra directa al Proveedor por lo que el Costo debe ser MAYOR a cero.';
			redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
		}
		if(($comcost['op_recibidos'] + $recibir[$i]) > $comcost['op_cantidad']) {
			$_SESSION['msjerror'] = 'La cantidad recibida del Item ' . $comcost['op_nombre'] . ' es mayor a la esperada, favor de ajustar.';
			redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
		}
		if($quitar[$i] == '1' && $fila4 > 0) {
			$_SESSION['msjerror'] = $lang['QuitarFacturas'];
			redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
		}
	}

	if($error === 'no') {
		foreach($quitar as $i => $j) {
			$sql_data_array = array();
			$parametros = "op_id = '" . $op_id[$i] ."'";
//			echo $i . ' -> ' . $parametros . ' ' . $quitar[$i] . '<br>';
			if ($quitar[$i] == '1') {
				if($orden_id == '999999997' || $pedido_tipo == '9' ) {
					$pregup = "SELECT prod_cantidad_pedida FROM " . $dbpfx . "productos WHERE prod_id = '" . $prod_id[$i] ."'";
					$matrup = mysql_query($pregup);
					$up = mysql_fetch_array($matrup);
					$disp = $up['prod_cantidad_pedida'] - $cantidad[$i];
					$parme = " prod_id = '" . $prod_id[$i] ."' ";
					$sqdat = ['prod_cantidad_pedida' => $disp];
					ejecutar_db($dbpfx . 'productos', $sqdat, 'actualizar', $parme);
					unset($sqdat);
					$sql_data_array = array('prod_id' => $prod_id[$i],
						'tipo' => 40, // Comentario de cancelación de pedidos.
						'evento' => 'Cancelación de ' . $cantidad[$i] . ' items del pedido ' . $pedido_id,
						'usuario' => $_SESSION['usuario']);
					ejecutar_db($dbpfx . 'prod_bitacora', $sql_data_array, 'insertar');

					$parme = " op_id = '" . $op_id[$i] . "' ";
					ejecutar_db($dbpfx . 'orden_productos', '', 'eliminar', $parme);
				} else {
					$sql_data_array['op_pedido'] = '';
					$sql_data_array['op_fecha_promesa'] = 'null';
					$sql_data_array['op_recibidos'] = '0';
					$sql_data_array['op_ok'] = '0';
					$sql_data_array['op_autosurtido'] = '0';
					$sql_data_array['op_costo'] = '0';
					ejecutar_db($dbpfx . 'orden_productos', $sql_data_array, 'actualizar', $parametros);
//					echo 'paso 5 <br>';
					$parme = " op_item_seg = '" . $op_id[$i] ."' ";
					$sqdat = [
						'op_item_seg' => NULL,
						'op_fecha_promesa' => NULL,
						'op_recibidos' => 0,
						'op_ok' => 0,
						'op_autosurtido' => 0
					];
					ejecutar_db($dbpfx . 'orden_productos', $sqdat, 'actualizar', $parme);
					unset($sqdat);

				}
				bitacora($orden_id, 'Refacción ' . $op_id[$i] . ' cancelada del Pedido ' . $pedido_id, $dbpfx);
			} elseif ($quitar[$i] == '2') {
				$_SESSION['cambdevol']['op_id'][] = $op_id[$i];
				$_SESSION['cambdevol']['orden_id'] = $orden_id;
				$_SESSION['cambdevol']['pedido_id'] = $pedido_id;
				$_SESSION['cambdevol']['prov_id'] = $prov_id;
			}
		}

// ------ Si hay items para devolver, redirigir a aprobación de devolución.
		if(is_array($_SESSION['cambdevol'])) {
			redirigir('cambdevol.php?accion=registrar');
		}

		foreach($op_id as $i => $j) {
			$preg0 = "SELECT op_cantidad, op_costo, op_nombre, op_recibidos, op_autosurtido, prod_id, sub_orden_id FROM " . $dbpfx . "orden_productos WHERE op_id='" . $op_id[$i] . "'";
			$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de orden_productos!");
			$comcost = mysql_fetch_array($matr0);
			$cantidad[$i] = $comcost['op_cantidad'];
			$prod_id[$i] = $comcost['prod_id'];
			$sql_data_array = array();
			if($comcost['op_costo'] != $costo[$i] && $comcost['op_costo'] > '0') { $sql_data_array['op_precio_revisado'] = '2'; }
			$parametros = "op_id = '" . $op_id[$i] ."'";
//			echo $i . ' -> ' . $parametros . ' ' . $recibir[$i] . '<br>';
			if($quitar[$i] < '1' && $recibir[$i] > 0) {
				$sql_data_array['op_recibidos'] = $comcost['op_recibidos'] + $recibir[$i];
				$sql_data_array['op_costo'] = $costo[$i];
				ejecutar_db($dbpfx . 'orden_productos', $sql_data_array, 'actualizar', $parametros);
				bitacora($orden_id, 'Refacción ' . $op_id[$i] . ' Recibida', $dbpfx);
				if($comcost['sub_orden_id'] == '999999997' || $pedido_tipo == '9' ) {
					$pregup = "SELECT prod_cantidad_existente, prod_cantidad_disponible, prod_cantidad_pedida FROM " . $dbpfx . "productos WHERE prod_id = '" . $comcost['prod_id'] ."'";
					$matrup = mysql_query($pregup);
					$up = mysql_fetch_array($matrup);
					$disp1 = $up['prod_cantidad_existente'] + $recibir[$i];
					$disp2 = $up['prod_cantidad_disponible'] +  $recibir[$i];
					$disp3 = $up['prod_cantidad_pedida'] - $recibir[$i];
					$parme = " prod_id = '" . $comcost['prod_id'] ."' ";
					$sqdat = [
						'prod_cantidad_existente' => $disp1,
						'prod_cantidad_disponible' => $disp2,
						'prod_cantidad_pedida' => $disp3,
						'prod_costo' => $costo[$i]
					];
					ejecutar_db($dbpfx . 'productos', $sqdat, 'actualizar', $parme);
					unset($sqdat);
//			echo 'paso 2 <br>';
//					$preg5 = "ACTUALIZAR " . $dbpfx . "productos SET prod_cantidad_existente = prod_cantidad_existente + " . $recibir[$i] . ", prod_cantidad_disponible = prod_cantidad_disponible + " . $recibir[$i] . ", prod_cantidad_pedida = prod_cantidad_pedida - " . $recibir[$i] . ", prod_costo = '" . $costo[$i] . "' WHERE prod_id = '" . $comcost['prod_id'] ."'"."\n";
//					$matr5 = mysql_query($preg5) or die("ERROR: Fallo actualización de productos!");

					$sql_data_array = array('prod_id' => $comcost['prod_id'],
						'tipo' => 1, // Comentario de Recepción normal de pedidos.
						'evento' => 'Recepción de ' . $recibir[$i] . ' items del pedido ' . $pedido_id,
						'usuario' => $_SESSION['usuario']);
					ejecutar_db($dbpfx . 'prod_bitacora', $sql_data_array, 'insertar');
//					echo 'paso 3 <br>';
				}
			} elseif($quitar[$i] < '1' && $costo[$i]!='' ) {
				$sql_data_array['op_costo'] = $costo[$i];
				ejecutar_db($dbpfx . 'orden_productos', $sql_data_array, 'actualizar', $parametros);
				bitacora($orden_id, 'Costo actualizado para OP ' . $op_id[$i] . ' de ' . $comcost['op_costo'] . ' a ' . $costo[$i], $dbpfx);
			}
		}

		$preg1 = "SELECT * FROM " . $dbpfx . "orden_productos WHERE op_pedido='" . $pedido_id . "'";
		$matr1 = mysql_query($preg1) or die("ERROR: Fallo selección de orden_productos! " . $preg1);
		$fila1 = mysql_num_rows($matr1);
		$param = "pedido_id = '" . $pedido_id . "'";
		$sql_data = array();
		if($fila1 > 0 ) {
			$completo = 1;
			$subtotal = 0; $iva = 0;
			while ($prod = mysql_fetch_array($matr1)) {
				if($prod['op_recibidos'] < $prod['op_cantidad']) {
					$completo = 0;
				} else {
					$parametros = "op_id = '" . $prod['op_id'] ."'";
					$sql_data_array = array('op_ok' => '1', 'op_fecha_promesa' => date('Y-m-d H:i:s'));
					ejecutar_db($dbpfx . 'orden_productos', $sql_data_array, 'actualizar', $parametros);
					unset($sql_data_array);

// ----------- Marcar como recibidos a items asociados ------------------

					$preg6 = "UPDATE " . $dbpfx . "orden_productos SET op_recibidos = op_cantidad, op_ok = 1, op_costo = 0, op_subtotal = 0, op_fecha_promesa = '"  . date('Y-m-d H:i:s') . "', op_autosurtido = '" . $prod['op_autosurtido'] . "' WHERE op_item_seg = '" . $prod['op_id'] ."'"."\n";
					$matr6 = mysql_query($preg6) or die("ERROR: Fallo actualización de productos!");
					$archivo = '../logs/' . date('Ymd-i') . '-base.ase';
					$myfile = file_put_contents($archivo, $preg6.';'.PHP_EOL , FILE_APPEND | LOCK_EX);

// ----------------------------------------------------------------------

				}
				$subtotal = $subtotal + ($prod['op_cantidad'] * $prod['op_costo']);
			}
			$iva = round(($subtotal * $impuesto_iva), 2);
			$sql_data['subtotal'] = $subtotal;
			$sql_data['impuesto'] = $iva;
			if($completo == 1) {
				$sql_data['pedido_estatus'] = '10';
				$sql_data['fecha_recibido'] = date('Y-m-d H:i:s');
			}
		} else {
			$sql_data = [
				'pedido_estatus' => '90',
				'subtotal' => '0',
				'impuesto' => '0',
			];
// ------ Marcar como huerfanos los pagos para este pedido.
			$sql_data_pf = [
				'fact_id' => 'null',
				'pedido_id' => 'null'
			];
			$parampf = " pedido_id ='" . $pedido_id . "'";
			ejecutar_db($dbpfx . 'pagos_facturas', $sql_data_pf, 'actualizar', $parampf);
		}

		ejecutar_db($dbpfx . 'pedidos', $sql_data, 'actualizar', $param);
		unset($sql_data);

		actualiza_pedido($pedido_id, $dbpfx, '1'); // ---- Actulizar pedido ----

//		echo 'paso 6 <br>';
		$preg2 = "SELECT sub_orden_id, sub_area, sub_estatus, sub_descuento, sub_refacciones_recibidas, sub_aseguradora FROM " . $dbpfx . "subordenes WHERE orden_id = '$orden_id' AND sub_estatus < '130'";
		$matr2 = mysql_query($preg2) or die("ERROR: Fallo selección de subordenes! " . $preg2);
		$fila2 = mysql_num_rows($matr2);
		if($fila2 > 0) {
			$totref = 1; $descxremp = 0;
			while($sub = mysql_fetch_array($matr2)) {
				$preg3 = "SELECT op_id, op_ok, op_estructural, op_pedido, op_pres, op_cantidad, op_costo, op_precio, op_tangible, op_autosurtido FROM " . $dbpfx . "orden_productos WHERE sub_orden_id = '".$sub['sub_orden_id']."'";
				$matr3 = mysql_query($preg3) or die("ERROR: Fallo selección de orden_productos 3!");
				$estruc = 1; $completo = 1; $op_ref = 0; $op_cons = 0; $op_mo = 0;
				while($op = mysql_fetch_array($matr3)) {
					// --- Obtiene monto para descuento en caso de reemplazo ---
					if($sub['sub_descuento'] > 0) {
						$descxremp = $descxremp + ($op['op_cantidad'] * $op['op_costo']);
					}
					// --- Determina si están completas las refacciones relacionadas a la Tarea ---
					if($op['op_ok'] == '0' && $op['op_tangible'] == '1') {
						if(($op['op_pres'] == 1 && $op['op_pedido'] > 0) || is_null($op['op_pres'])) {
							$completo = 0;
							$totref = 0;
							if($op['op_estructural'] == '1') {
								$estruc = 0;
							}
						}
					}
					// --- Determina si debe agregar o no el precio de venta a resumen cobrable de la Tarea ---
					if($op['op_autosurtido']=='1') {
						$op_sub = 0;
					} else {
						$op_sub = $op['op_cantidad'] * $op['op_precio'];
					}
					// --- En caso de revisión de precio de venta, reconfirma el subtotal actual ---
					if($op['op_precio_revisado'] > 0) {
						$paramjup = "op_id = '" . $op['op_id'] . "'";
						$sqldajup = array('op_subtotal' => $op_sub);
						ejecutar_db($dbpfx . 'orden_productos', $sqldajup, 'actualizar', $paramjup);
					}

					// --- Actualiza los montos de venta para resumen cobrable de la Tarea ---
					if($op['op_tangible']== 1 && $op['op_pres'] < 1 && (($autosurt[$sub['sub_aseguradora']] == '1' && $op['op_autosurtido']!='1') || $sub['sub_aseguradora'] < 1 || $op['op_autosurtido']=='2'|| $op['op_autosurtido']=='3')) {
						$op_ref = $op_ref + $op_sub;
					} elseif($op['op_tangible']== 2 && $op['op_pres'] < 1) {
						$op_cons = $op_cons + $op_sub;
					} elseif($op['op_tangible']== 0 && $op['op_pres'] < 1)  {
						$op_mo = $op_mo + $op_sub;
					}
				}
				$parametros = "sub_orden_id = '" . $sub['sub_orden_id'] ."'";
				if($completo == 1) {
					$sql_data_array = array('sub_refacciones_recibidas' => '0');
					if($sub['sub_estatus'] == '105') {
						$sql_data_array['sub_estatus'] = '106';
//						bitacora($orden_id, $lang['RR para VSRP'], $dbpfx, $lang['RR para VSRP Explica'], 2, $sub['sub_orden_id']);
						if($mensjint == 1) {
							$preg7 = "SELECT orden_asesor_id FROM " . $dbpfx . "ordenes WHERE orden_id = '" . $orden_id . "'";
							$matr7 = mysql_query($preg7) or die("ERROR: Fallo selección de asesor! " . $preg7);
							$asesor = mysql_fetch_array($matr7);
							bitacora($orden_id, $lang['RR para VSRP'], $dbpfx, $lang['RR para VSRP Explica'], 3, $sub['sub_orden_id'], '', $asesor['orden_asesor_id']);
						}
					}
				} elseif($estruc == 1) {
					$sql_data_array = array('sub_refacciones_recibidas' => '1');
				} else {
					$sql_data_array = array('sub_refacciones_recibidas' => '2');
				}
				$nvo_pres = $op_ref + $op_cons + $op_mo;
				$sql_data_array['sub_presupuesto'] = $nvo_pres;
				$sql_data_array['sub_partes'] = $op_ref;
				$sql_data_array['sub_consumibles'] = $op_cons;
				$sql_data_array['sub_mo'] = $op_mo;

				ejecutar_db($dbpfx . 'subordenes', $sql_data_array, 'actualizar', $parametros);

				unset($sql_data_array);
//				echo 'PagoID: ' . $sub['sub_descuento'] . ' Monto descuento: ' . $descxremp; 
				if($sub['sub_descuento'] > 0) {
					$parametros = "pago_id = '" . $sub['sub_descuento'] ."'";
// ------ Sumar el porcentaje que determine el taller a los costos de refacciones de remplazo
					$descxremp = round(($descxremp * (1 + ($prcxremp / 100))), 2);
					$sql_data_array['pago_monto_origen'] = $descxremp;
					ejecutar_db($dbpfx . 'destajos_pagos', $sql_data_array, 'actualizar', $parametros);
					bitacora($orden_id, 'Guardando descuento de ' . $descxremp . ' en PagoID ' . $sub['sub_descuento'], $dbpfx);
				}
				unset($sql_data_array);
				actualiza_orden($orden_id, $dbpfx);
			}
			unset($sql_data_array);
			unset($_SESSION['ref']);
			redirigir('refacciones.php?accion=gestionar&orden_id=' . $orden_id);
		} else {
			redirigir('refacciones.php?accion=listar');
		}
		// ------ Si hay monto de Notas de Crédito, redirigir a registro de pago
	}
	$_SESSION['ref']['mensaje'] = $msj;
}

elseif ($accion==="pagar") {

	unset($_SESSION['microtime']);

	if($cancelar == 'Cancelar Factura') {
		$funnum = 1050025;
		echo '	<form action="pedidos.php?accion=cancelafact" method="post" enctype="multipart/form-data">
	<table cellpadding="0" cellspacing="0" border="0" class="agrega">
		<tr class="cabeza_tabla"><td colspan="2">Pedido: ' . $pedido_id . ' Factura: ' . $numero . ' OT: ' . $orden_id . '</td></tr>';
		echo '		<tr><td colspan="2"><span class="alerta">¿En realidad desea cancelar esta factura de proveedor?</span></td></tr>'."\n";
		echo '		<tr><td colspan="2" style="text-align:left;"><div class="control"><a href="pedidos.php?accion=consultar&pedido=' . $pedido_id . '"><img src="idiomas/' . $idioma . '/imagenes/regresar.png" alt="Regresar al Pedido" title="Regresar al Pedido"></a></div></td></tr>'."\n";
		echo '		<tr class="cabeza_tabla"><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
			<input type="hidden" name="pedido_id" value="' . $pedido_id . '" />
			<input type="hidden" name="orden_id" value="' . $orden_id . '" />
			<input type="hidden" name="fact_id" value="' . $fact_id . '" /></td></tr>
		<tr><td colspan="2" style="text-align:left;"><input type="submit" value="Sí, Cancelar" /></td></tr>'."\n";
		echo '	</table>
	</form>'."\n";
	} else {

		$funnum = 1050030;
		if(!isset($por_pagar) || $por_pagar == '') { $por_pagar = $_SESSION['ped']['por_pagar']; }
		if(!isset($pagar) || $pagar == '') { $pagar = $_SESSION['ped']['pagar']; }
		if(!isset($numero) || $numero == '') { $numero = $_SESSION['ped']['fact_num']; }
		if(!isset($fact_id) || $fact_id == '') { $fact_id = $_SESSION['ped']['fact_id']; }
		if(!isset($cuenta) || $cuenta == '') { $cuenta = $_SESSION['ped']['cuenta']; }

		echo '		<form action="pedidos.php?accion=';
		if($adelanto == 1) {
			echo 'procesa_adelanto';
		} else {
			echo 'procesapago';
		}
		echo '" method="post" enctype="multipart/form-data">'."\n";
		echo '		<table cellpadding="0" cellspacing="0" border="0" class="agrega">
			<tr class="cabeza_tabla"><td colspan="2">Pedido: ' . $pedido_id . ' Factura: ' . $numero . ' OT: ' . $orden_id . '</td></tr>';
		echo '			<tr><td>Monto de este pago</td><td style="text-align:left;">$<input type="text" name="pagar" value="';
		$monto_permitido = $por_pagar;
		if($pagar > 0) { echo number_format($pagar,2); }
		else { echo number_format($por_pagar,2); }
		echo '" size="10"  style="text-align:right;"/></td></tr>'."\n";
		echo '		<tr><td>Fecha del pago</td><td style="text-align:left;">';
		require_once("calendar/tc_calendar.php");

		//instantiate class and set properties
		$myCalendar = new tc_calendar("fechapago", true);
		$myCalendar->setPath("calendar/");
		$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		if($_SESSION['ped']['fechapago'] != '') {
			$myCalendar->setDate(date("d", strtotime($_SESSION['ped']['fechapago'])), date("m", strtotime($_SESSION['ped']['fechapago'])), date("Y", strtotime($_SESSION['ped']['fechapago'])));
		} else {
			$myCalendar->setDate(date("d"), date("m"), date("Y"));
		}
//		$myCalendar->dateAllow(date("Y-m-d"), "2020-12-31");
		$myCalendar->disabledDay("sun");
		$myCalendar->setYearInterval(2011, 2020);
		$myCalendar->setAutoHide(true, 5000);

		//output the calendar
		$myCalendar->writeScript();

		echo '</td></tr>';

		echo '		<tr><td>Método de Pago</td><td style="text-align:left;">'."\n";
		echo '			<select name="forma_pago" size="1">
				<option value="" > Seleccione... </option>'."\n";
		for($i=1;$i <= $opcpago;$i++) {
			echo '				<option value="' . $i . '" >' . constant("TIPO_PAGO_".$i) . '</option>'."\n";
		}
		echo '			</select>'."\n";
		echo '		</td></tr>';
//		if($asientos == 1) {
			echo '		<tr><td>Cuenta</td><td style="text-align:left;">';
			echo '			<select name="cuenta" size="1">
				<option value="" >Seleccione... </option>'."\n";
			$preg0 = "SELECT * FROM " . $dbpfx . "cont_cuentas WHERE ban_activo = '1'";
			$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de cuentas");
			while ($ban = mysql_fetch_array($matr0)) {
				echo '				<option value="' . $ban['ban_id'] . '" >' . $ban['ban_nombre'] . ' - ' . $ban['ban_cuenta'] . '</option>'."\n";
			}
			echo '			</select>';
			echo '		</td></tr>'."\n";
/*		} else {
			echo '		<tr><td>Banco</td><td style="text-align:left;"><input type="text" name="banco" value="' . REC_PROV_BANCO . '" size="30" /></td></tr>'."\n";
			echo '		<tr><td>Cuenta</td><td style="text-align:left;"><input type="text" name="cuenta" value="' . REC_PROV_CUENTA . '" size="30" /></td></tr>'."\n";
		}
*/
		echo '		<tr><td>Num cheque o transferencia</td><td style="text-align:left;"><input type="text" name="referencia"';
		if($_SESSION['ped']['referencia'] != '') { echo ' value="' . $_SESSION['ped']['referencia'] . '" ';}
		echo ' size="15" /></td></tr>'."\n";
		echo '		<tr><td>Imagen de comprobante de pago: </td><td style="text-align:left;"><input type="file" name="comprobante" size="30"';
		if($_SESSION['ped']['comprobante'] != '') { echo ' value="' . $_SESSION['ped']['comprobante'] . '" '; }
		echo ' /></td></tr>'."\n";
		echo '		<tr><td colspan="2" style="text-align:left;"><div class="control"><a href="pedidos.php?accion=consultar&pedido=' . $pedido_id . '"><img src="idiomas/' . $idioma . '/imagenes/regresar.png" alt="Regresar al Pedido" title="Regresar al Pedido"></a></div></td></tr>'."\n";
		unset($_SESSION['ped']);
		echo '		<tr class="cabeza_tabla"><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
			<input type="hidden" name="microtime" value="' . microtime() . '" />
			<input type="hidden" name="orden_id" value="' . $orden_id . '" />
			<input type="hidden" name="pedido_id" value="' . $pedido_id . '" />
			<input type="hidden" name="pedido_tipo" value="' . $pedido_tipo . '" />
			<input type="hidden" name="fact_num" value="' . $numero . '" />
			<input type="hidden" name="por_pagar" value="' . $por_pagar . '" />
			<input type="hidden" name="fact_id" value="' . $fact_id . '" />
			<input type="hidden" name="monto_permitido" value="' . $monto_permitido . '" />
			<input type="hidden" name="provid" value="' . $provid . '" />'."\n";
		if($adelanto == '1') {
			echo '
			<input type="hidden" name="adelanto" value="1" />'."\n";
		}
		echo '
			</td></tr>
		<tr><td colspan="2" style="text-align:left;"><input type="submit" value="Enviar" />&nbsp;<input type="reset" name="limpiar" value="Borrar datos" /></td></tr>
	</table>
	</form>'."\n";
	}
}

elseif ($accion==="procesapago") {

	if(validaAcceso('1050030', $dbpfx) == 1) {
		$mensaje = 'Acceso Autorizado';
	} elseif($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')) {
		$mensaje = 'Acceso Autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir("pedidos.php?accion=consultar&pedido=" . $pedido_id);
	}

	$error = 'no';
 	if($_SESSION['microtime'] == $microtime) { $error = 'si'; $_SESSION['msjerror'] .= 'La tecla enter fue presionada más de una vez, sólo se procesó el primer envío.<br>';}
 	else { $_SESSION['microtime'] = $microtime; }

	unset($_SESSION['ped']);
	$_SESSION['ped'] = array();
	$mensaje = '';
	$pagar = limpiarNumero($pagar); $_SESSION['ped']['pagar'] = $pagar;
	$_SESSION['ped']['fact_num'] = $fact_num;
	$_SESSION['ped']['por_pagar'] = $por_pagar;
	$_SESSION['ped']['fact_id'] = $fact_id;
	$_SESSION['ped']['cuenta'] = $cuenta;
	$referencia = preparar_entrada_bd($referencia); $_SESSION['ped']['referencia'] = $referencia;
	$_SESSION['ped']['fechapago'] = $fechapago;
	$_SESSION['ped']['por_pagar'] = $por_pagar;
	$_SESSION['ped']['comprobante'] = $comprobante;

//	echo $forma_pago;

	$preg0 = "SELECT * FROM " . $dbpfx . "cont_cuentas WHERE ban_id = '$cuenta'";
	$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de cuenta bancaria");
	$ban = mysql_fetch_array($matr0);

	if($pagar <= 0 || $pagar == '') {$error = 'si'; $mensaje .='El monto del pago no puede ser cero, negativo o vacío.<br>';}
	if($pagar > $monto_permitido) {$error = 'si'; $mensaje .='El monto del pago no puede ser superior al monto pendiente por pagar.<br>';}
	if($forma_pago == '' || !isset($forma_pago)) {$error = 'si'; $mensaje .='Seleccione una forma de pago.<br>';}
	if($forma_pago > 1 && $referencia == '') {$error = 'si'; $mensaje .='Indique el número de cheque o transferencia.<br>';}

	if($asientos == 1) {
		if($forma_pago > 1 && $ban['ban_cuenta'] < 1 ) {$error = 'si'; $mensaje .='La cuenta seleccionada y la forma de pago elegida no son compatibles.<br>';}
		if($forma_pago == 1 && $ban['ban_cuenta'] > 0 ) {$error = 'si'; $mensaje .='Debe seleccionar la cuenta de EFECTIVO para la forma de pago en efectivo.<br>';}
		if(!isset($fact_id) || $fact_id == '') {$error = 'si'; $mensaje .='Datos incompletos, por favor regrese al pedido.<br>';}
	}
	if($ban['ban_banco'] == '' || $ban['ban_cuenta'] == '' || $cuenta == '') {$error = 'si'; $mensaje .='Debe indicar la Cuenta para la forma de pago elegida.<br>';}


	if($error === 'no') {
		if($_FILES['comprobante']['name'] != '') {
			$subir = agrega_documento($orden_id, $_FILES['comprobante'], 'Comprobante de pago del Pedido '.$pedido_id, $dbpfx);
		}
		$subtotal = round(($pagar / 1.16), 6);
		$iva = round(($subtotal * 0.16), 6);
//		print_r($subir);
//		echo 'Resultado de subir<br>';

		$sql_data_array = [
			'pago_monto' => $pagar,
			'pago_tipo' => $forma_pago,
			'pago_banco' => $ban['ban_banco'],
			'pago_cuenta' => $ban['ban_cuenta'],
			'pago_referencia' => $referencia,
			'pago_fecha' => $fechapago,
			'pago_documento' => $subir['nombre'],
			'usuario' => $_SESSION['usuario'],
			'prov_id' => $provid,

		];
		ejecutar_db($dbpfx . 'pedidos_pagos', $sql_data_array);
		$pago_id = mysql_insert_id();
		bitacora($orden_id, 'Pago de Factura ' . $fact_id . ' del Pedido '.$pedido_id, $dbpfx);

		unset($sql_data_array);
		$sql_data_array = [
			'pago_id' => $pago_id,
			'fact_id' => $fact_id,
			'monto' => $pagar,
			'pedido_id' => $pedido_id,
			'proveedor_id' => $provid,
			'usuario' => $_SESSION['usuario'],
			'fecha' => date('Y-m-d H:i:s', time()),
		];
		if($adelanto == 1) {
			$sql_data_array['adelanto'] = $pedido_id;
		}
		ejecutar_db($dbpfx . 'pagos_facturas', $sql_data_array);

		unset($sql_data_array);
		unset($_SESSION['ped']);

		// ---- Marcamos como pagada la factura si el pago cumple con el monto por pagar
		if($pagar == $por_pagar && $fact_id > 0) {
			$param = "fact_id = '$fact_id'";
			$sql_data_array = array('pagada' => '1',
			'f_pago' => date('Y-m-d H:i:s', time()));
			ejecutar_db($dbpfx . 'facturas_por_pagar', $sql_data_array, 'actualizar', $param);
			unset($sql_data_array);
		}

/* ------ Sección que no hace nada, eliminar 20180721 ------
		$preg1 = "SELECT * FROM " . $dbpfx . "pedidos WHERE pedido_id = '$pedido_id'";
		$matr1 = mysql_query($preg1) or die("ERROR: Fallo selección de pedido");
		$ped = mysql_fetch_array($matr1);
		$montoped = round(($ped['subtotal'] + $ped['impuesto']), 8);

		$preg2 = "SELECT monto FROM " . $dbpfx . "pagos_facturas WHERE pedido_id = ' $pedido_id'";

		$matr2 = mysql_query($preg2) or die("ERROR: Fallo selección de pagos" . $preg2);
		while ($pedpag = mysql_fetch_array($matr2)) {
			$totpag = $totpag + $pedpag['monto'];
		}
		$totpag = round($totpag, 8);
//		echo 'Monto pagos: ' . $totpag . '<br>';

		$param = "pedido_id = '$pedido_id'";
		unset($sql_data_array);

		if($totpag == $montoped) {
			$sql_data_array['pedido_pagado'] = '1';
			$sql_data_array['pedido_fecha_de_pago'] = date('Y-m-d H:i:s', time());
		}
*/
		actualiza_pedido($pedido_id, $dbpfx);

		if($asientos == 1) {
		// ----------- Asientos contables ----------------->
			$poliza = regPoliza('3', 'Pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $fact_num);

			$resultado = regAsiento('1', '0', '3', $poliza['ciclo'], $poliza['polnum'], $ped['prov_id'], 'Pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $pagar, $orden_id, $fact_num);

			$resultado = regAsiento('0', '1', '3', $poliza['ciclo'], $poliza['polnum'], '2000000', 'IVA acreditable al pago de la factura ' . $fact_num . ' del pedido ' . $pedido_id, $iva, $orden_id, $fact_num);

			$resultado = regAsiento('0', '0', '3', $poliza['ciclo'], $poliza['polnum'], '2000005', 'IVA acreditable pagado de la factura ' . $fact_num . ' del proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $iva, $orden_id, $fact_num);

			$resultado = regAsiento('5', '1', '3', $poliza['ciclo'], $poliza['polnum'], $ban['ban_id'], 'Registro en cuenta ' . $ban['ban_id'] . ' del pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $pagar, $orden_id, $fact_num);
		}
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	} else {
		$_SESSION['msjerror'] = $mensaje;
		redirigir('pedidos.php?accion=pagar&pedido_id=' . $pedido_id . '&orden_id=' .$orden_id);
	}
}

elseif ($accion === "procesa_adelanto"){

	if(validaAcceso('1050030', $dbpfx) == 1) {
		$mensaje = 'Acceso Autorizado';
	} elseif($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')) {
		$mensaje = 'Acceso Autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir("pedidos.php?accion=consultar&pedido=" . $pedido_id);
	}

	$error = 'no';
 	if($_SESSION['microtime'] == $microtime) { $error = 'si'; $_SESSION['msjerror'] .= 'La tecla enter fue presionada más de una vez, sólo se procesó el primer envío.<br>';}
 	else { $_SESSION['microtime'] = $microtime; }

	unset($_SESSION['ped']);
	$_SESSION['ped'] = array();
	$mensaje = '';
	$pagar = limpiarNumero($pagar); $_SESSION['ped']['pagar'] = $pagar;
	$_SESSION['ped']['fact_num'] = $fact_num;
	$_SESSION['ped']['por_pagar'] = $por_pagar;
	$_SESSION['ped']['fact_id'] = $fact_id;
	$_SESSION['ped']['cuenta'] = $cuenta;
	$referencia = preparar_entrada_bd($referencia); $_SESSION['ped']['referencia'] = $referencia;
	$_SESSION['ped']['fechapago'] = $fechapago;
	$_SESSION['ped']['por_pagar'] = $por_pagar;
	$_SESSION['ped']['comprobante'] = $comprobante;

//	echo $forma_pago;

	$preg0 = "SELECT * FROM " . $dbpfx . "cont_cuentas WHERE ban_id = '$cuenta'";
	$matr0 = mysql_query($preg0) or die("ERROR: Fallo selección de cuenta bancaria");
	$ban = mysql_fetch_array($matr0);

	if($pagar <= 0 || $pagar == '') {$error = 'si'; $mensaje .='El monto del pago no puede ser cero, negativo o vacío.<br>';}
	if($pagar > $monto_permitido) {$error = 'si'; $mensaje .='El monto del pago no puede ser superior al monto pendiente por pagar.<br>';}
	if($forma_pago == '' || !isset($forma_pago)) {$error = 'si'; $mensaje .='Seleccione una forma de pago.<br>';}
	if($forma_pago > 1 && $referencia == '') {$error = 'si'; $mensaje .='Indique el número de cheque o transferencia.<br>';}

	if($asientos == 1) {
		if($forma_pago > 1 && $ban['ban_cuenta'] < 1 ) {$error = 'si'; $mensaje .='La cuenta seleccionada y la forma de pago elegida no son compatibles.<br>';}
		if($forma_pago == 1 && $ban['ban_cuenta'] > 0 ) {$error = 'si'; $mensaje .='Debe seleccionar la cuenta de EFECTIVO para la forma de pago en efectivo.<br>';}
		if(!isset($fact_id) || $fact_id == '') {$error = 'si'; $mensaje .='Datos incompletos, por favor regrese al pedido.<br>';}
	}
	if($ban['ban_banco'] == '' || $ban['ban_cuenta'] == '' || $cuenta == '') {$error = 'si'; $mensaje .='Debe indicar la Cuenta para la forma de pago elegida.<br>';}


	if($error === 'no') {
		if($_FILES['comprobante']['name'] != '') {
			$subir = agrega_documento($orden_id, $_FILES['comprobante'], 'Comprobante de pago del Pedido '.$pedido_id, $dbpfx);
		}
		$subtotal = round(($pagar / 1.16), 6);
		$iva = round(($subtotal * 0.16), 6);
//		print_r($subir);
//		echo 'Resultado de subir<br>';

		$sql_data_array = [
			'pago_monto' => $pagar,
			'pago_tipo' => $forma_pago,
			'pago_banco' => $ban['ban_banco'],
			'pago_cuenta' => $ban['ban_cuenta'],
			'pago_referencia' => $referencia,
			'pago_fecha' => $fechapago,
			'pago_documento' => $subir['nombre'],
			'usuario' => $_SESSION['usuario'],
			'prov_id' => $provid,

		];
		ejecutar_db($dbpfx . 'pedidos_pagos', $sql_data_array);
		$pago_id = mysql_insert_id();
		bitacora($orden_id, 'Registro de pago adelantado por  ' . $pagar . ' del Pedido '.$pedido_id, $dbpfx);
		unset($sql_data_array);
		$sql_data_array = [
			'pago_id' => $pago_id,
			'monto' => $pagar,
			'pedido_id' => $pedido_id,
			'proveedor_id' => $provid,
			'usuario' => $_SESSION['usuario'],
			'fecha' => date('Y-m-d H:i:s', time()),
		];
		if($adelanto == 1){
			$sql_data_array['adelanto'] = $pedido_id;
		}
		ejecutar_db($dbpfx . 'pagos_facturas', $sql_data_array);

		unset($sql_data_array);
		unset($_SESSION['ped']);

		$preg1 = "SELECT * FROM " . $dbpfx . "pedidos WHERE pedido_id = '$pedido_id'";
		$matr1 = mysql_query($preg1) or die("ERROR: Fallo selección de pedido");
		$ped = mysql_fetch_array($matr1);
		$montoped = round(($ped['subtotal'] + $ped['impuesto']), 8);

		$preg2 = "SELECT monto FROM " . $dbpfx . "pagos_facturas WHERE pedido_id = ' $pedido_id'";
		$matr2 = mysql_query($preg2) or die("ERROR: Fallo selección de pagos" . $preg2);
		while ($pedpag = mysql_fetch_array($matr2)) {
			$totpag = $totpag + $pedpag['monto'];
		}
		$totpag = round($totpag, 8);
		echo 'Monto pagos: ' . $totpag . '<br>';

		$param = "pedido_id = '$pedido_id'";
		unset($sql_data_array);

		if($totpag == $montoped) {
			$sql_data_array['pedido_pagado'] = '1';
			$sql_data_array['pedido_fecha_de_pago'] = date('Y-m-d H:i:s', time());
		}

// -------------------- Determinar el estatus del pedido -----------------------------

		actualiza_pedido($pedido_id, $dbpfx);


		/*
//		echo 'Total pagado: ' . $totpag . ' Total pedido: '. $montoped . '<br>';
		if($ped['pedido_estatus'] >= '10' && $totpag >= $montoped && $fact_id > 0) {
			$sql_data_array['pedido_estatus'] = '99';
			bitacora($orden_id, 'Pago Total de Pedido '.$pedido_id, $dbpfx);
		} elseif($ped['pedido_estatus'] >= '10' && $totpag >= $montoped) {
			$sql_data_array['pedido_estatus'] = '30';
			bitacora($orden_id, 'Pago Total de Pedido ' . $pedido_id .', Factura de Proveedor Pendiente', $dbpfx);
		} elseif($ped['pedido_estatus'] >= '10' && $totpag < $montoped) {
			$sql_data_array['pedido_estatus'] = '25';
			bitacora($orden_id, 'Pago Parcial de Pedido '.$pedido_id, $dbpfx);
		} elseif($ped['pedido_estatus'] < '10' && $totpag == $montoped) {
			bitacora($orden_id, 'Pago anticipado del Pedido '.$pedido_id, $dbpfx);
		}
//		print_r($sql_data_array);
		if(is_array($sql_data_array)) {
			ejecutar_db($dbpfx . 'pedidos', $sql_data_array, 'actualizar', $param);
		}
		*/
		if($asientos == 1) {
		// ----------- Asientos contables ----------------->
			$poliza = regPoliza('3', 'Pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $fact_num);

			$resultado = regAsiento('1', '0', '3', $poliza['ciclo'], $poliza['polnum'], $ped['prov_id'], 'Pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $pagar, $orden_id, $fact_num);

			$resultado = regAsiento('0', '1', '3', $poliza['ciclo'], $poliza['polnum'], '2000000', 'IVA acreditable al pago de la factura ' . $fact_num . ' del pedido ' . $pedido_id, $iva, $orden_id, $fact_num);

			$resultado = regAsiento('0', '0', '3', $poliza['ciclo'], $poliza['polnum'], '2000005', 'IVA acreditable pagado de la factura ' . $fact_num . ' del proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $iva, $orden_id, $fact_num);

			$resultado = regAsiento('5', '1', '3', $poliza['ciclo'], $poliza['polnum'], $ban['ban_id'], 'Registro en cuenta ' . $ban['ban_id'] . ' del pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $pagar, $orden_id, $fact_num);
		}
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	} else {
		$_SESSION['msjerror'] = $mensaje;
		redirigir('pedidos.php?accion=pagar&pedido_id=' . $pedido_id . '&orden_id=' .$orden_id);
	}

}

elseif ($accion==="factura") {

	if (validaAcceso('1050035', $dbpfx) == '1') {
		$mensage='Acceso autorizado';
	} elseif($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')) {
		$mensage='Acceso autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	}

	unset($_SESSION['microtime']);

	include('idiomas/' . $idioma . '/refacciones.php');
	include('parciales/encabezado.php');
	echo '	<div id="body">';
	include('parciales/menu_inicio.php');
	echo '		<div id="principal">';

	require_once("calendar/tc_calendar.php");
	echo '	<form action="pedidos.php?accion=regfact" method="post" enctype="multipart/form-data">
	<table cellpadding="0" cellspacing="0" border="0" class="agrega">
		<tr class="cabeza_tabla"><td colspan="2">Registro de factura o remisión de proveedor para el pedido: ' . $pedido_id . ' de la OT: ' . $orden_id . '</td></tr>';
//	if(!isset($por_pagar) || $por_pagar == '') { $por_pagar = limpiarNumero($_SESSION['ped']['por_pagar']); }
//	if($_SESSION['ped']['iva'] != '') { $iva = $_SESSION['ped']['iva']; } else { $iva = round(($por_pagar * $provs[$prov_id]['iva']), 6); }
	$iva = round(($por_pagar * $provs[$prov_id]['iva']), 2);
	$monto_permitido = $por_pagar;
	echo '		<tr><td>Total</td><td style="text-align:left;"><input type="text" name="fact_sub" value="' . $por_pagar . '" size="10"  style="text-align:right;"/></td></tr>'."\n";
	echo '		<tr><td>Número de factura</td><td style="text-align:left;"><input type="text" name="fact_num" /></td></tr>'."\n";
	echo '		<tr><td>Fecha de recepción</td><td style="text-align:left;">';

		//instantiate class and set properties
		$myCalendar = new tc_calendar("fecharec", true);
		$myCalendar->setPath("calendar/");
		$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		$myCalendar->setDate(date("d"), date("m"), date("Y"));
//		$myCalendar->dateAllow(date("Y-m-d"), "2020-12-31");
		$myCalendar->disabledDay("sun");
		$myCalendar->setYearInterval(2011, 2020);
		$myCalendar->setAutoHide(true, 5000);

		//output the calendar
		$myCalendar->writeScript();

	echo '</td></tr>';
	echo '		<tr><td>Fecha programada de pago</td><td style="text-align:left;">';

		//instantiate class and set properties
		$myCalendar = new tc_calendar("fechaprog", true);
		$myCalendar->setPath("calendar/");
		$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		$myCalendar->setDate(date("d"), date("m"), date("Y"));
//		$myCalendar->dateAllow(date("Y-m-d"), "2020-12-31");
		$myCalendar->disabledDay("sun");
		$myCalendar->setYearInterval(2011, 2020);
		$myCalendar->setAutoHide(true, 5000);

		//output the calendar
		$myCalendar->writeScript();

	echo '</td></tr>';
		echo '		<tr><td>Imagen de factura a pagar: </td><td style="text-align:left;"><input type="file" name="comprobante" size="30" /></td></tr>'."\n";
	echo '		<tr><td colspan="2" style="text-align:left;"><div class="control"><a href="pedidos.php?accion=consultar&pedido=' . $pedido_id . '"><img src="idiomas/' . $idioma . '/imagenes/regresar.png" alt="Regresar al Pedido" title="Regresar al Pedido"></a></div></td></tr>'."\n";
	echo '		<tr class="cabeza_tabla"><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
			<input type="hidden" name="microtime" value="' . microtime() . '" />
			<input type="hidden" name="orden_id" value="' . $orden_id . '" />
			<input type="hidden" name="pedido_id" value="' . $pedido_id . '" />
			<input type="hidden" name="por_pagar" value="' . $por_pagar . '" />
			<input type="hidden" name="monto_permitido" value="' . $monto_permitido . '" />
			<input type="hidden" name="prov_id" value="' . $prov_id . '" />
		</td></tr>
		<tr><td colspan="2" style="text-align:left;"><input type="submit" value="Enviar" />&nbsp;<input type="reset" name="limpiar" value="Borrar datos" /></td></tr>
		</tr>
	</table>
	</form>';
}

elseif($accion==="regfact") {

	if(validaAcceso('1050040', $dbpfx) == 1) {
		$mensaje = 'Acceso Autorizado';
	} elseif($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')) {
		$mensaje = 'Acceso Autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir("pedidos.php?accion=consultar&pedido=" . $pedido_id);
	}

	$error = 'no';
	$mensaje = '';

 	if($_SESSION['microtime'] == $microtime) { $error = 'si'; $_SESSION['msjerror'] .= 'La tecla enter fue presionada más de una vez, sólo se procesó el primer envío.<br>';}
 	else { $_SESSION['microtime'] = $microtime; }

	unset($_SESSION['ped']);
//	$fact_sub = limpiarNumero($fact_sub); $_SESSION['ped']['por_pagar'] = $fact_sub;
//	$fact_iva = limpiarNumero($fact_iva); $_SESSION['ped']['iva'] = $fact_iva;
	if($fact_sub > $monto_permitido){
		 $error = 'si'; $mensaje .= 'El monto de la factura no puede ser superior al monto faltante del pedido por registar.<br>';
	}
	$fact_monto = $fact_sub;
	if(!isset($fact_num) || $fact_num =='') { $error = 'si'; $mensaje .= 'Indique el número de factura.<br>';  }
//	if($fact_monto <= '0' || $fact_monto =='') { $error = 'si'; $mensaje .= 'Indique el monto de la factura.<br>';  }
	if($fechaprog == '0000-00-00') { $error = 'si'; $mensaje .= 'Indique la fecha programada de pago.<br>';  }

	if ($error === 'no') {
		if($_FILES['comprobante']['name'] != '') {
			$subir = agrega_documento($orden_id, $_FILES['comprobante'], 'Imagen de Documento de cobro del Pedido '.$pedido_id, $dbpfx);
		}
  		$tipo = '1';
		$sql_data_array = array('orden_id' => $orden_id,
			'tipo' => $tipo,
			'doc_int_id' => $pedido_id,
			'tercero_id' => $prov_id,
			'fact_num' => $fact_num,
			'f_monto' => $fact_monto,
			'f_rec' => $fecharec,
			'f_prog' => $fechaprog,
			'usuario' => $_SESSION['usuario']);
		ejecutar_db($dbpfx . 'facturas_por_pagar', $sql_data_array, 'insertar');
		bitacora($orden_id, 'Registro de factura por pagar: '.$fact_num, $dbpfx);
//		print_r($sql_data_array);
		unset($sql_data_array);

		actualiza_pedido($pedido_id, $dbpfx);

		// ----------- Asientos contables ----------------->
		if($asientos == 1) {
			$poliza = regPoliza('1', 'Recepción de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $fact_num);

			$resultado = regAsiento('0', '0', '1', $poliza['ciclo'], $poliza['polnum'], '1050040', 'Recepción de factura ' . $fact_num . ' del proveedor ' . $prov_id . ' del pedido ' . $pedido_id, $fact_sub, $orden_id, $fact_num);

			$resultado = regAsiento('0', '0', '1', $poliza['ciclo'], $poliza['polnum'], '2000000', 'IVA acreditable al pago de la factura ' . $fact_num . ' del pedido ' . $pedido_id, $fact_iva, $orden_id, $fact_num);

			$resultado = regAsiento('1', '1', '1', $poliza['ciclo'], $poliza['polnum'], $prov_id, 'Recepción de factura ' . $fact_num . ' del proveedor ' . $prov_id . ' del pedido ' . $pedido_id, $fact_monto, $orden_id, $fact_num);

			$poliza = regPoliza('1', 'Entrega de refacciones del pedido ' . $pedido_id . ' a operadores', $fact_num);

			$resultado = regAsiento('0', '0', '1', $poliza['ciclo'], $poliza['polnum'], '1070040', 'Entrega de refacciones del pedido ' . $pedido_id . ' a operadores', $fact_sub, $orden_id);

			$resultado = regAsiento('0', '1', '1', $poliza['ciclo'], $poliza['polnum'], '1050040', 'Entrega de refacciones del pedido ' . $pedido_id . ' a operadores', $fact_sub, $orden_id);
		}

		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);

	} else {
		$_SESSION['msjerror'] .= $mensaje;
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	}
}

elseif($accion==="asociarpf") {

	if (validaAcceso('1050045', $dbpfx) == 1) {
		$mensaje = 'Autorizado';
	} elseif ($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol03']=='1' || $_SESSION['rol13']=='1')) {
		$mensaje = 'Autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	}

	$error = 'no';
	$mensaje = '';

	foreach($fid as $k => $v) {
		echo $k . '<br>';
		if($v > 0) {
// ------ Verificar Que el monto del pago no exceda el pendiente por pagar ---
			$preg_monto = "SELECT monto FROM " . $dbpfx . "pagos_facturas WHERE pf_id = '" . $pago_id[$k] . "'";
			$matr_monto = mysql_query($preg_monto) or die("ERROR: Fallo selección del monto del pago a asociar! " . $preg_monto);
			$monto_pag = mysql_fetch_array($matr_monto);
//			echo '<br>pago: ' . $monto_pag['monto'];
// ------ Calcular el pendiente por pagar de la factura----
			$preg_pag_fac = "SELECT monto FROM " . $dbpfx . "pagos_facturas WHERE fact_id = '" . $v . "'";
			$matr_pag_fac = mysql_query($preg_pag_fac) or die("ERROR: Fallo selección de pagos de la factura! " . $preg_pag_fac);
			$pagos_factura = 0;
			while($pagfact = mysql_fetch_array($matr_pag_fac)) {
				$pagos_factura = $pagos_factura + $pagfact['monto'];
			}
			$preg_monto_fact = "SELECT f_monto FROM " . $dbpfx . "facturas_por_pagar WHERE fact_id = '" . $v . "'";
			$matr_monto_fact = mysql_query($preg_monto_fact) or die("ERROR: Fallo selección del monto de la factura! " . $preg_monto_fact);
			$con_monto_fact = mysql_fetch_assoc($matr_monto_fact);
			$monto_fact = $con_monto_fact['f_monto'];
			$por_pagar = $monto_fact - $pagos_factura;
//			echo '<br>a pagar: ' . $por_pagar;
// ------ Verificar si se puede asociar el pago a la factura ---
			if($monto_pag['monto'] <= $por_pagar) {
				unset($sql_data_array);
				$param = "pf_id = '" . $pago_id[$k] . "'";
				$sql_data_array = [
					'fact_id' => $v,
					'pedido_id' => $pedido_id,
				];
				ejecutar_db($dbpfx . 'pagos_facturas', $sql_data_array, 'actualizar', $param);
				bitacora($orden_id, 'Pago '.$pago_id[$k].' asociado a la factura '.$v, $dbpfx);
				if($por_pagar == $monto_pag['monto']) {
// ------ Marcar pagada la factura ---
					unset($sql_data_array);
					$sql_data_array['pagada'] = '1';
					$sql_data_array['f_pago'] = date('Y-m-d H:i:s', time());
					$param = " fact_id = '$v'";
					ejecutar_db($dbpfx . 'facturas_por_pagar', $sql_data_array, 'actualizar', $param);
				}
			} else {
				$error = 'si';
				$mensaje .= $lang['ElPagoMonto'] . ' $' . number_format($monto_pag['monto'],2) . ' ' . $lang['ExcedeMonto'] . ' $' . number_format($por_pagar,2) . ' ' . $lang['NoAsocFact'] . ' ' . $v . '<br>';
			}
		}
	}
	unset($sql_data_array);

// -------------------- Determinar el estatus del pedido -----------------------------

	actualiza_pedido($pedido_id, $dbpfx);
	if($error == 'si') {
		$_SESSION['msjerror'] = $mensaje;
	}
	redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
}

elseif($accion==="cancelafact") {

	if(validaAcceso('1050050', $dbpfx)) {
		$mensaje = 'Acceso Autorizado';
	} elseif($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')) {
		$mensaje = 'Acceso Autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	}
	$error = 'no';
	$mensaje = '';

	if ($error === 'no') {
		$param = " fact_id = '" . $fact_id . "'";
		$sql_data_array = array('pagada' => '2');
		ejecutar_db($dbpfx . 'facturas_por_pagar', $sql_data_array, 'actualizar', $param);
	
		$sql_data_array = array('fact_id' => '0');
		ejecutar_db($dbpfx . 'pedidos_pagos', $sql_data_array, 'actualizar', $param);
		bitacora($orden_id, 'Se canceló la factura ' . $fact_id . ' de Proveedor.', $dbpfx);
		unset($sql_data_array);

		$preg1 = "SELECT fact_id FROM " . $dbpfx . "facturas_por_pagar WHERE doc_int_id = '$pedido_id' AND tipo = '1' AND pagada < '2'";
		$matr1 = mysql_query($preg1);
		$fila1 = mysql_num_rows($matr1);

		if($fila1 < 1) {
			$param = " pedido_id = '$pedido_id' ";
			$sql_data_array = array('pedido_estatus' => '10');
			ejecutar_db($dbpfx . 'pedidos', $sql_data_array, 'actualizar', $param);
			bitacora($orden_id, 'Ya no quedaron facturas de Proveedor, se regresa el pedido ' . $pedido_id . ' a estatus 10', $dbpfx);
		}
		actualiza_pedido($pedido_id, $dbpfx);
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	} else {
		$_SESSION['orden']['mensaje'] = $mensaje;
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	}
}

elseif ($accion==="aplicarnc") {

	unset($_SESSION['microtime']);
// ------ Procesa devoluciones y genera registros para espera de las correspondientes Notas de Crédito.-------
	$op_id = explode('|', $opanc);
	$monto_nc = 0;
	foreach($op_id as $i) {
// ------ Genera un nuevo OP para solicitarlo a un nuevo proveedor
			$pregnc = "SELECT * FROM " . $dbpfx . "orden_productos WHERE op_id = '" . $op_id[$i] . "'";
			$matrnc = mysql_query($preg1) or die("ERROR: Fallo selección de orden_productos para NC! " . $pregnc);
				$nvo_op = mysql_fetch_assoc($matrnc);
				$nvo_op['op_item_seg'] = 'null';
				$nvo_op['op_fecha_promesa'] = 'null';
				$nvo_op['op_recibidos'] = 0;
				$nvo_op['op_ok'] = 0;
				$nvo_op['op_autosurtido'] = 0;
				$nvo_op['op_pedido'] = '';
				ejecutar_db($dbpfx . 'orden_productos', $nvo_op, 'insertar');
				$nvo_op_id = mysql_insert_id();
				bitacora($orden_id, 'Se crea nuevo ' . $nvo_op['op_nombre'] . ' ' . $nvo_op_id . ' para hacer pedido a otro proveedor.', $dbpfx);
				unset($nvo_op);
// ------ Modifica OP para marcarlo como devuelto 
				$parme = " op_id = '" . $op_id[$i] ."' ";
				$sqdat = [
					'op_item_seg' => 'null',
					'op_costo' => 0,
					'op_fecha_promesa' => date('Y-m-d H:i:s'),
					'op_recibidos' => 0,
					'op_ok' => 0,
					'op_autosurtido' => 0,
					'op_tangible' => 7 // Marcar como devolución.
				];
				ejecutar_db($dbpfx . 'orden_productos', $sqdat, 'actualizar', $parme);
				bitacora($orden_id, 'Refacción ' . $op_id[$i] . ' marcada para devolución del Pedido ' . $pedido_id, $dbpfx);
				unset($sqdat);
// ------ Determina monto de devulociones para crear registro de NC ------
				mysql_data_seek($matrnc,0);
				$op_devo = mysql_fetch_assoc($matrnc);
				$monto_nc = $monto_nc + ($op_devo['op_cantidad'] * $op_devo['op_costo']);
		}

		if(!isset($por_pagar) || $por_pagar == '') { $por_pagar = $_SESSION['ped']['por_pagar']; }
		if(!isset($pagar) || $pagar == '') { $pagar = $_SESSION['ped']['pagar']; }
		if(!isset($numero) || $numero == '') { $numero = $_SESSION['ped']['fact_num']; }
		if(!isset($fact_id) || $fact_id == '') { $fact_id = $_SESSION['ped']['fact_id']; }
		if(!isset($cuenta) || $cuenta == '') { $cuenta = $_SESSION['ped']['cuenta']; }

		echo '		<form action="pedidos.php?accion=procesanc" method="post" enctype="multipart/form-data">'."\n";
		echo '		<table cellpadding="0" cellspacing="0" border="0" class="agrega">
			<tr class="cabeza_tabla"><td colspan="2">Nota de Crédito para el Pedido: ' . $pedido_id . ' OT: ' . $orden_id . '</td></tr>'."\n";
		echo '			<tr><td>Monto por acreditar</td><td style="text-align:right;"><input type="hidden" name="pagar" value="' . $montonc . '"/>$' . number_format($montonc, 2) . '</td></tr>'."\n";
		echo '		<tr><td>Fecha del pago</td><td style="text-align:left;">';
		require_once("calendar/tc_calendar.php");
		//instantiate class and set properties
		$myCalendar = new tc_calendar("fechapago", true);
		$myCalendar->setPath("calendar/");
		$myCalendar->setIcon("calendar/images/iconCalendar.gif");
		if($_SESSION['ped']['fechapago'] != '') {
			$myCalendar->setDate(date("d", strtotime($_SESSION['ped']['fechapago'])), date("m", strtotime($_SESSION['ped']['fechapago'])), date("Y", strtotime($_SESSION['ped']['fechapago'])));
		} else {
			$myCalendar->setDate(date("d"), date("m"), date("Y"));
		}
//		$myCalendar->dateAllow(date("Y-m-d"), "2020-12-31");
		$myCalendar->disabledDay("sun");
		$myCalendar->setYearInterval(2015, 2022);
		$myCalendar->setAutoHide(true, 5000);

		//output the calendar
		$myCalendar->writeScript();

		echo '</td></tr>';

		echo '		<tr><td>Método de Pago</td><td style="text-align:left;"><input type="hidden" name="forma_pago" value="4" />' . constant("TIPO_PAGO_4") . '</td></tr>' . "\n";
		echo '		<tr><td>Motivo de Nota de Crédito (30)</td><td style="text-align:left;"><input type="text" name="referencia"';
		if($_SESSION['ped']['referencia'] != '') { echo ' value="' . $_SESSION['ped']['referencia'] . '" ';}
		echo ' size="25" /></td></tr>'."\n";
		echo '		<tr><td>Imagen de Nota de Crédito: </td><td style="text-align:left;"><input type="file" name="comprobante" size="30"';
		if($_SESSION['ped']['comprobante'] != '') { echo ' value="' . $_SESSION['ped']['comprobante'] . '" '; }
		echo ' /></td></tr>'."\n";
		echo '		<tr><td colspan="2" style="text-align:left;"><div class="control"><a href="pedidos.php?accion=consultar&pedido=' . $pedido_id . '"><img src="idiomas/' . $idioma . '/imagenes/regresar.png" alt="Regresar al Pedido" title="Regresar al Pedido"></a></div></td></tr>'."\n";
		unset($_SESSION['ped']);
		echo '		<tr class="cabeza_tabla"><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
			<input type="hidden" name="microtime" value="' . microtime() . '" />
			<input type="hidden" name="orden_id" value="' . $orden_id . '" />
			<input type="hidden" name="pedido_id" value="' . $pedido_id . '" />
			<input type="hidden" name="por_pagar" value="' . $monto_nc . '" />
			<input type="hidden" name="opanc" value="' . $opanc . '" />
			<input type="hidden" name="provid" value="' . $provid . '" />'."\n";
		echo '		</td></tr>
		<tr><td colspan="2" style="text-align:left;"><input type="submit" value="Enviar" /></td></tr>
	</table>
	</form>'."\n";
}

elseif ($accion==="procesanc") {

	if(validaAcceso('1050030', $dbpfx) == 1) {
		$mensaje = 'Acceso Autorizado';
	} elseif($solovalacc != 1 && ($_SESSION['rol02']=='1' || $_SESSION['rol13']=='1')) {
		$mensaje = 'Acceso Autorizado';
	} else {
		$_SESSION['msjerror'] = $lang['AccNoAut'];
		redirigir("pedidos.php?accion=consultar&pedido=" . $pedido_id);
	}

	$error = 'no';
	unset($_SESSION['ped']);
	$_SESSION['ped'] = array();
	$mensaje = '';
	$_SESSION['ped']['pagar'] = $pagar;
	$_SESSION['ped']['por_pagar'] = $por_pagar;
	$referencia = preparar_entrada_bd($referencia); $_SESSION['ped']['referencia'] = $referencia;
	$_SESSION['ped']['fechapago'] = $fechapago;

	if($_SESSION['microtime'] == $microtime) { $error = 'si'; $_SESSION['msjerror'] .= 'La tecla enter fue presionada más de una vez, sólo se procesó el primer envío.<br>';}
 	else { $_SESSION['microtime'] = $microtime; }
	if($referencia == '') {$error = 'si'; $mensaje .='Indique el motivo de la Nota de Crédito.<br>';}
	if($_FILES['comprobante']['name'] != '') {
		$subir = agrega_documento($orden_id, $_FILES['comprobante'], 'Comprobante de pago del Pedido '.$pedido_id, $dbpfx, '', '1');
	}
	if($subir['error'] == 'si') { $error = 'si'; $mensaje .= $subir['mensaje']; }

	if($error === 'no') {
		$subtotal = round(($pagar / (1 + $provs[$provid]['iva'])), 2);
		$iva = round(($pagar - $subtotal), 2);

		$sql_data_array = [
			'pago_monto' => $pagar,
			'pago_tipo' => $forma_pago,
			'pago_banco' => $ban['ban_banco'],
			'pago_cuenta' => $ban['ban_cuenta'],
			'pago_referencia' => $referencia,
			'pago_fecha' => $fechapago,
			'pago_documento' => $subir['nombre'],
			'usuario' => $_SESSION['usuario'],
			'prov_id' => $provid,

		];
		ejecutar_db($dbpfx . 'pedidos_pagos', $sql_data_array);
		$pago_id = mysql_insert_id();
		bitacora($orden_id, 'Pago de Factura ' . $fact_id . ' del Pedido '.$pedido_id, $dbpfx);

		unset($sql_data_array);
		$sql_data_array = [
			'pago_id' => $pago_id,
			'fact_id' => $fact_id,
			'monto' => $pagar,
			'pedido_id' => $pedido_id,
			'proveedor_id' => $provid,
			'usuario' => $_SESSION['usuario'],
			'fecha' => date('Y-m-d H:i:s', time()),
		];
		if($adelanto == 1) {
			$sql_data_array['adelanto'] = $pedido_id;
		}
		ejecutar_db($dbpfx . 'pagos_facturas', $sql_data_array);

		unset($sql_data_array);
		unset($_SESSION['ped']);

		// ---- Marcamos como pagada la factura si el pago cumple con el monto por pagar
		if($pagar == $por_pagar && $fact_id > 0){
			$param = "fact_id = '$fact_id'";
			$sql_data_array = array('pagada' => '1',
			'f_pago' => date('Y-m-d H:i:s', time()));
			ejecutar_db($dbpfx . 'facturas_por_pagar', $sql_data_array, 'actualizar', $param);
			unset($sql_data_array);
		}

		$preg1 = "SELECT * FROM " . $dbpfx . "pedidos WHERE pedido_id = '$pedido_id'";
		$matr1 = mysql_query($preg1) or die("ERROR: Fallo selección de pedido");
		$ped = mysql_fetch_array($matr1);
		$montoped = round(($ped['subtotal'] + $ped['impuesto']), 8);

		$preg2 = "SELECT monto FROM " . $dbpfx . "pagos_facturas WHERE pedido_id = ' $pedido_id'";

		$matr2 = mysql_query($preg2) or die("ERROR: Fallo selección de pagos" . $preg2);
		while ($pedpag = mysql_fetch_array($matr2)) {
			$totpag = $totpag + $pedpag['monto'];
		}
		$totpag = round($totpag, 8);
//		echo 'Monto pagos: ' . $totpag . '<br>';

		$param = "pedido_id = '$pedido_id'";
		unset($sql_data_array);

		if($totpag == $montoped) {
			$sql_data_array['pedido_pagado'] = '1';
			$sql_data_array['pedido_fecha_de_pago'] = date('Y-m-d H:i:s', time());
		}

// -------------------- Determinar el estatus del pedido -----------------------------

		actualiza_pedido($pedido_id, $dbpfx);

		if($asientos == 1) {
		// ----------- Asientos contables ----------------->
			$poliza = regPoliza('3', 'Pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $fact_num);

			$resultado = regAsiento('1', '0', '3', $poliza['ciclo'], $poliza['polnum'], $ped['prov_id'], 'Pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $pagar, $orden_id, $fact_num);

			$resultado = regAsiento('0', '1', '3', $poliza['ciclo'], $poliza['polnum'], '2000000', 'IVA acreditable al pago de la factura ' . $fact_num . ' del pedido ' . $pedido_id, $iva, $orden_id, $fact_num);

			$resultado = regAsiento('0', '0', '3', $poliza['ciclo'], $poliza['polnum'], '2000005', 'IVA acreditable pagado de la factura ' . $fact_num . ' del proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $iva, $orden_id, $fact_num);

			$resultado = regAsiento('5', '1', '3', $poliza['ciclo'], $poliza['polnum'], $ban['ban_id'], 'Registro en cuenta ' . $ban['ban_id'] . ' del pago de factura ' . $fact_num . ' al proveedor ' . $ped['prov_id'] . ' del pedido ' . $pedido_id, $pagar, $orden_id, $fact_num);
		}
		redirigir('pedidos.php?accion=consultar&pedido=' . $pedido_id);
	} else {
		$_SESSION['msjerror'] = $mensaje;
		redirigir('pedidos.php?accion=pagar&pedido_id=' . $pedido_id . '&orden_id=' .$orden_id);
	}
}

?>
		</div>
	</div>
<?php include('parciales/pie.php'); ?>